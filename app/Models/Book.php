<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books_book';
    public $timestamps = false;

    protected $fillable = [
        'download_count', 
        'gutenberg_id', 
        'media_type', 
        'title'
    ];

    // Many-to-Many: Books have multiple authors
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'books_book_authors', 'book_id', 'author_id');
    }

    // Many-to-Many: Books are in multiple languages
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'books_book_languages', 'book_id', 'language_id');
    }

    // Many-to-Many: Books have multiple subjects
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'books_book_subjects', 'book_id', 'subject_id');
    }

    // Many-to-Many: Books belong to multiple bookshelves
    public function bookshelves()
    {
        return $this->belongsToMany(Bookshelf::class, 'books_book_bookshelves', 'book_id', 'bookshelf_id');
    }

    // One-to-Many: Books have multiple formats
    public function formats()
    {
        return $this->hasMany(Format::class, 'book_id');
    }

    // Scope for popular books
    public function scopePopular($query)
    {
        return $query->orderBy('download_count', 'desc');
    }

    // Scope for books with covers (images)
    public function scopeWithCovers($query)
    {
        return $query->whereHas('formats', function($q) {
            $q->where('mime_type', 'like', 'image%');
        });
    }

    // Accessor for preferred download format
    public function getPreferredFormatAttribute()
    {
        $preferredFormats = ['text/html', 'application/pdf', 'text/plain'];
        
        foreach ($preferredFormats as $format) {
            $formatUrl = $this->formats->firstWhere('mime_type', $format);
            if ($formatUrl && !str_contains($formatUrl->url, '.zip')) {
                return $formatUrl->url;
            }
        }
        
        return null;
    }
}