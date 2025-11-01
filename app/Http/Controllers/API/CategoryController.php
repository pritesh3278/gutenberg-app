<?php

namespace App\Http\Controllers\API;

use App\Models\Bookshelf;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller; 

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/categories",
     *     summary="Get list of categories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $categories = Bookshelf::select('id', 'name')
                ->orderBy('name')
                ->get();

            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch categories',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}