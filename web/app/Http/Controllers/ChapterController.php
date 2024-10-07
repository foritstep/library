<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrUpdateChapterRequest;
use App\Models\Chapter;
use Illuminate\Http\Response;

class ChapterController extends Controller
{
    public function index(int $authorId, int $bookId)
    {
        return response()->json(Chapter::where('book_id', $bookId)->paginate(10));
    }

    public function store(CreateOrUpdateChapterRequest $request)
    {
        $chapter = Chapter::create($request->validated());
        return response()->json($chapter, Response::HTTP_CREATED);
    }

    public function show(int $authorId, int $bookId, int $chapterId)
    {
        $chapter = Chapter::with('book')->find($chapterId);
        return response()->json($chapter);
    }

    public function update(CreateOrUpdateChapterRequest $request, int $authorId, int $bookId, int $chapterId)
    {
        $chapter = Chapter::find($chapterId);
        $chapter->update($request->validated());
        $chapter->load('book');
        return response()->json($chapter, Response::HTTP_OK);
    }

    public function destroy(int $authorId, int $bookId, int $chapterId)
    {
        Chapter::destroy($chapterId);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
