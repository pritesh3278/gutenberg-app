<?php

/**
 * @OA\Schema(
 *     schema="Book",
 *     type="object",
 *     title="Book",
 *     description="Book model",
 *     @OA\Property(property="id", type="integer", description="Gutenberg ID", example=11),
 *     @OA\Property(property="title", type="string", description="Book title", example="Alice's Adventures in Wonderland"),
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
 *     @OA\Property(property="languages", type="array", description="Language codes", @OA\Items(type="string", example="en")),
 *     @OA\Property(property="subjects", type="array", description="Book subjects", @OA\Items(type="string", example="Fantasy literature")),
 *     @OA\Property(property="bookshelves", type="array", description="Bookshelf categories", @OA\Items(type="string", example="Children's Literature")),
 *     @OA\Property(property="formats", type="object", description="Available formats with URLs"),
 *     @OA\Property(property="download_count", type="integer", description="Number of downloads", example=17114),
 *     @OA\Property(property="media_type", type="string", description="Media type", example="Text"),
 *     @OA\Property(property="preferred_format_url", type="string", description="Preferred format URL for viewing", nullable=true)
 * )
 */

/**
 * @OA\Schema(
 *     schema="Author",
 *     type="object",
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="birth_year", type="integer", nullable=true),
 *     @OA\Property(property="death_year", type="integer", nullable=true)
 * )
 */

/**
 * @OA\Schema(
 *     schema="Error",
 *     type="object",
 *     @OA\Property(property="error", type="string"),
 *     @OA\Property(property="message", type="string")
 * )
 */