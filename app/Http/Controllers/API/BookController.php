<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use App\Http\Resources\BookResource;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="Gutenberg Books API",
 *     version="1.0.0",
 *     description="API for accessing Project Gutenberg books data",
 *     @OA\Contact(
 *         email="admin@gutenberg.org"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local API Server"
 * )
 *
 * @OA\Tag(
 *     name="Books",
 *     description="API Endpoints for Books"
 * )
 * 
 * @OA\Schema(
 *     schema="Book",
 *     type="object",
 *     title="Book",
 *     description="Book model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Gutenberg ID",
 *         example=11
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Book title",
 *         example="Alice's Adventures in Wonderland"
 *     ),
 *     @OA\Property(
 *         property="authors",
 *         type="array",
 *         description="List of authors",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="Carroll, Lewis"),
 *             @OA\Property(property="birth_year", type="integer", nullable=true, example=1832),
 *             @OA\Property(property="death_year", type="integer", nullable=true, example=1898)
 *         )
 *     ),
 *     @OA\Property(
 *         property="languages",
 *         type="array",
 *         description="Language codes",
 *         @OA\Items(type="string", example="en")
 *     ),
 *     @OA\Property(
 *         property="subjects",
 *         type="array",
 *         description="Book subjects",
 *         @OA\Items(type="string", example="Fantasy literature")
 *     ),
 *     @OA\Property(
 *         property="bookshelves",
 *         type="array",
 *         description="Bookshelf categories",
 *         @OA\Items(type="string", example="Children's Literature")
 *     ),
 *     @OA\Property(
 *         property="formats",
 *         type="object",
 *         description="Available formats with URLs",
 *         additionalProperties={
 *             "type": "string",
 *             "example": "http://www.gutenberg.org/files/11/11.txt.utf-8"
 *         }
 *     ),
 *     @OA\Property(
 *         property="download_count",
 *         type="integer",
 *         description="Number of downloads",
 *         example=17114
 *     ),
 *     @OA\Property(
 *         property="media_type",
 *         type="string",
 *         description="Media type",
 *         example="Text"
 *     ),
 *     @OA\Property(
 *         property="preferred_format_url",
 *         type="string",
 *         description="Preferred format URL for viewing",
 *         nullable=true,
 *         example="http://www.gutenberg.org/files/11/11-h/11-h.htm"
 *     )
 * )
 */
class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/books",
     *     summary="Get list of books",
     *     description="Returns a paginated list of books with optional filtering",
     *     operationId="getBooks",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="ids",
     *         in="query",
     *         description="Comma-separated list of Gutenberg IDs",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="languages",
     *         in="query",
     *         description="Comma-separated language codes (e.g., en,fr)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="mime_type",
     *         in="query",
     *         description="MIME type filter (e.g., text/html)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="topic",
     *         in="query",
     *         description="Topic filter for subjects or bookshelves",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="Author name filter",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Title filter",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search in titles and authors",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="count", type="integer", example=100),
     *             @OA\Property(property="next", type="string", nullable=true, example="http://localhost:8000/api/books?page=2"),
     *             @OA\Property(property="previous", type="string", nullable=true),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Book")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Book::with(['authors', 'languages', 'subjects', 'bookshelves', 'formats'])
                ->withCovers()
                ->popular();

            // Apply filters
            $this->applyFilters($query, $request);

            $books = $query->paginate(25);

            return response()->json([
                'count' => $books->total(),
                'next' => $this->buildPageUrl($books->nextPageUrl(), $request),
                'previous' => $this->buildPageUrl($books->previousPageUrl(), $request),
                'results' => BookResource::collection($books->items())
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch books',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/books/{id}",
     *     summary="Get book by ID",
     *     description="Returns a single book by Gutenberg ID",
     *     operationId="getBookById",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Gutenberg ID of the book",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Book")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            $book = Book::with(['authors', 'languages', 'subjects', 'bookshelves', 'formats'])
                ->where('gutenberg_id', $id)
                ->firstOrFail();

            return response()->json(new BookResource($book));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Book not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->has('ids')) {
            $ids = array_map('intval', explode(',', $request->get('ids')));
            $query->whereIn('gutenberg_id', $ids);
        }

        if ($request->has('languages')) {
            $languages = explode(',', $request->get('languages'));
            $query->whereHas('languages', function($q) use ($languages) {
                $q->whereIn('code', $languages);
            });
        }

        if ($request->has('mime_type')) {
            $mimeType = $request->get('mime_type');
            $query->whereHas('formats', function($q) use ($mimeType) {
                $q->where('mime_type', 'like', $mimeType . '%');
            });
        }

        if ($request->has('topic')) {
            $topics = explode(',', $request->get('topic'));
            $query->where(function($q) use ($topics) {
                foreach ($topics as $topic) {
                    $q->orWhereHas('subjects', function($sq) use ($topic) {
                        $sq->where('name', 'ilike', '%' . $topic . '%');
                    })->orWhereHas('bookshelves', function($bq) use ($topic) {
                        $bq->where('name', 'ilike', '%' . $topic . '%');
                    });
                }
            });
        }

        if ($request->has('author')) {
            $authors = explode(',', $request->get('author'));
            $query->whereHas('authors', function($q) use ($authors) {
                foreach ($authors as $author) {
                    $q->orWhere('name', 'ilike', '%' . $author . '%');
                }
            });
        }

        if ($request->has('title')) {
            $titles = explode(',', $request->get('title'));
            $query->where(function($q) use ($titles) {
                foreach ($titles as $title) {
                    $q->orWhere('title', 'ilike', '%' . $title . '%');
                }
            });
        }

        if ($request->has('search')) {
            $searchTerms = explode(' ', $request->get('search'));
            $query->where(function($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('title', 'ilike', '%' . $term . '%')
                      ->orWhereHas('authors', function($aq) use ($term) {
                          $aq->where('name', 'ilike', '%' . $term . '%');
                      });
                }
            });
        }
    }

    private function buildPageUrl($pageUrl, Request $request)
    {
        if (!$pageUrl) return null;
        
        $queryParams = $request->except('page');
        return $pageUrl . '&' . http_build_query($queryParams);
    }
}