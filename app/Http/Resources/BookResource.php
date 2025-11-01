<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="BookResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(
 *         property="authors",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="birth_year", type="integer", nullable=true),
 *             @OA\Property(property="death_year", type="integer", nullable=true)
 *         )
 *     ),
 *     @OA\Property(property="languages", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="subjects", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="bookshelves", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="formats", type="object"),
 *     @OA\Property(property="download_count", type="integer"),
 *     @OA\Property(property="media_type", type="string"),
 *     @OA\Property(property="preferred_format_url", type="string", nullable=true)
 * )
 */

class BookResource extends JsonResource
{
    public function toArray($request)
    {
        // Build formats array
        $formats = [];
        foreach ($this->formats as $format) {
            $formats[$format->mime_type] = $format->url;
        }

        return [
            'id' => $this->gutenberg_id,
            'title' => $this->title,
            'authors' => $this->authors->map(function($author) {
                return [
                    'name' => $author->name,
                    'birth_year' => $author->birth_year,
                    'death_year' => $author->death_year,
                ];
            }),
            'languages' => $this->languages->pluck('code'),
            'subjects' => $this->subjects->pluck('name'),
            'bookshelves' => $this->bookshelves->pluck('name'),
            'formats' => $formats,
            'download_count' => $this->download_count,
            'media_type' => $this->media_type,
            'preferred_format_url' => $this->preferred_format,
        ];
    }
}