<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'books_author';
    public $timestamps = false;

    protected $fillable = ['name', 'birth_year', 'death_year'];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'books_book_authors', 'author_id', 'book_id');
    }

  
    public function getLifespanAttribute()
    {
        if ($this->birth_year && $this->death_year) {
            return "({$this->birth_year} - {$this->death_year})";
        } elseif ($this->birth_year) {
            return "(b. {$this->birth_year})";
        }
        return '';
    }

   
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('name', 'ilike', "%{$searchTerm}%");
    }
}