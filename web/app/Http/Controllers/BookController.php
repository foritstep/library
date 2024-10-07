<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrUpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\Response;

class BookController extends Controller
{
    public function index(int $authorId)
    {
        return response()->json(Book::with('author')->where('author_id', $authorId)->paginate(10));
    }

    public function store(CreateOrUpdateBookRequest $request)
    {
        $book = Book::create($request->validated());
        return response()->json($book, Response::HTTP_CREATED);
    }

    public function show(int $authorId, int $bookId)
    {
        $book = Book::with([
            'author',
            'chapters' => fn ($query) => $query->limit(10)
        ])->withCount('chapters')->find($bookId);
        return response()->json($book);
    }

    public function update(CreateOrUpdateBookRequest $request, int $authorId, int $bookId)
    {
        $book = Book::find($bookId);
        $book->update($request->validated());
        $book->load('author');
        return response()->json($book, Response::HTTP_OK);
    }

    public function destroy(int $authorId, int $bookId)
    {
        Book::destroy($bookId);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
