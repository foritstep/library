<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrUpdateAuthorRequest;
use App\Models\Author;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    public function index()
    {
        return response()->json(
            Author::withCount('books')
                ->orderBy('books_count', 'desc')
                ->paginate(15)
            );
    }

    public function store(CreateOrUpdateAuthorRequest $request)
    {
        $author = Author::create($request->validated());
        return response()->json($author, Response::HTTP_CREATED);
    }

    public function show(int $authorId)
    {
        $author = Author::with('books')->find($authorId);
        return response()->json($author);
    }

    public function update(CreateOrUpdateAuthorRequest $request, int $authorId)
    {
        $author = Author::find($authorId);
        $author->update($request->validated());
        return response()->json($author, Response::HTTP_OK);
    }

    public function destroy(int $authorId)
    {
        Author::destroy($authorId);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
