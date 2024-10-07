<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    public $casts = [
        'birthday' => 'date'
    ];

    public $fillable = [
        'name',
        'information',
        'birthday',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
