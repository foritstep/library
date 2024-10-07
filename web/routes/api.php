<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ChapterController;
use Illuminate\Support\Facades\Route;

Route::get('/authors', [AuthorController::class, 'index']);
Route::post('/authors', [AuthorController::class, 'store']);
Route::get('/authors/{authorId}', [AuthorController::class, 'show']);
Route::patch('/authors/{authorId}', [AuthorController::class, 'update']);
Route::delete('/authors/{authorId}', [AuthorController::class, 'destroy']);

Route::get('/authors/{authorId}/books', [BookController::class, 'index']);
Route::post('/authors/{authorId}/books', [BookController::class, 'store']);
Route::get('/authors/{authorId}/books/{bookId}', [BookController::class, 'show']);
Route::patch('/authors/{authorId}/books/{bookId}', [BookController::class, 'update']);
Route::delete('/authors/{authorId}/books/{bookId}', [BookController::class, 'destroy']);

Route::get('/authors/{authorId}/books/{bookId}/chapters', [ChapterController::class, 'index']);
Route::post('/authors/{authorId}/books/{bookId}/chapters', [ChapterController::class, 'store']);
Route::get('/authors/{authorId}/books/{bookId}/chapters/{chapterId}', [ChapterController::class, 'show']);
Route::patch('/authors/{authorId}/books/{bookId}/chapters/{chapterId}', [ChapterController::class, 'update']);
Route::delete('/authors/{authorId}/books/{bookId}/chapters/{chapterId}', [ChapterController::class, 'destroy']);