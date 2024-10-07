<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ChapterTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_index(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->getKey()]);
        Chapter::factory()->count(10)->create(['book_id' => $book->getKey()]);

        $response = $this->get("/api/authors/{$author->getKey()}/books/{$book->getKey()}/chapters");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.0.book_id', $book->getKey());
        $response->assertJsonCount(10, 'data');
        $response->assertJsonPath('current_page', 1);
        $response->assertJsonPath('per_page', 10);
    }
    
    public function test_store(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->getKey()]);

        $response = $this->post(
            "/api/authors/{$author->getKey()}/books/{$book->getKey()}/chapters",
            [
                'book_id' => $book->getKey(),
                'title' => 'title',
                'text' => 'text',
            ]
        );

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(
            [
                'book_id' => $book->getKey(),
                'title' => 'title',
                'text' => 'text',
            ]
        );
    }
    
    public function test_store_fail(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->getKey()]);

        $response = $this->post(
            "/api/authors/{$author->getKey()}/books/{$book->getKey()}/chapters",
            [
                'title' => 't',
                'text' => 't',
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment(
            [
                "errors" => [
                    "book_id" => ["The book id field is required."],
                    "text" => ["The text field must be at least 2 characters."],
                    "title" => ["The title field must be at least 2 characters."]
                ]
            ]
        );
    }
    
    public function test_show(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->getKey()]);
        $chapter = Chapter::factory()->create(['book_id' => $book->getKey()]);

        $response = $this->get("/api/authors/{$author->getKey()}/books/{$book->getKey()}/chapters/{$chapter->getKey()}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('id', $chapter->getKey());
        $response->assertJsonPath('book.id', $book->getKey());
    }
    
    public function test_update(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->getKey()]);
        $new_book = Book::factory()->create(['author_id' => $author->getKey()]);
        $chapter = Chapter::factory()->create(['book_id' => $book->getKey()]);

        $response = $this->patch(
            "/api/authors/{$author->getKey()}/books/{$book->getKey()}/chapters/{$chapter->getKey()}",
            [
                'book_id' => $new_book->getKey(),
                'title' => 'title',
                'text' => 'text',
            ]
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(
            [
                'book_id' => $new_book->getKey(),
                'title' => 'title',
                'text' => 'text',
            ]
        );
    }
    
    public function test_update_fail(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->getKey()]);
        $chapter = Chapter::factory()->create(['book_id' => $book->getKey()]);

        $response = $this->patch(
            "/api/authors/{$author->getKey()}/books/{$book->getKey()}/chapters/{$chapter->getKey()}",
            [
                'title' => 'n',
                'text' => 'n',
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment(
            [
                "errors" => [
                    "book_id" => ["The book id field is required."],
                    "text" => ["The text field must be at least 2 characters."],
                    "title" => ["The title field must be at least 2 characters."]
                ]
            ]
        );
    }
    
    public function test_delete(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->getKey()]);
        $chapter = Chapter::factory()->create(['book_id' => $book->getKey()]);

        $response = $this->delete("/api/authors/{$author->getKey()}/books/{$book->getKey()}/chapters/{$chapter->getKey()}",);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertNull(Chapter::find($chapter->getKey()));
    }
}
