<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    public $fillable = [
        'book_id',
        'title',
        'text',
        'char_count',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
