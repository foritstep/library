<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public $casts = [
        'publication_date' => 'date'
    ];

    public $fillable = [
        'author_id',
        'title',
        'annotation',
        'publication_date',
        'characters_count',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}
