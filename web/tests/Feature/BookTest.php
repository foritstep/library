<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_index(): void
    {
        $author = Author::factory()->create();
        Book::factory()->count(11)->create(['author_id' => $author->getKey()]);

        $response = $this->get("/api/authors/{$author->getKey()}/books");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.0.author.id', $author->getKey());
        $response->assertJsonCount(10, 'data');
        $response->assertJsonPath('current_page', 1);
        $response->assertJsonPath('per_page', 10);
    }
    
    public function test_store(): void
    {
        $author = Author::factory()->create();

        $response = $this->post(
            "/api/authors/{$author->getKey()}/books",
            [
                'author_id' => $author->getKey(),
                'title' => 'title',
                'annotation' => 'annotation',
                'publication_date' => '31-12-2000',
            ]
        );

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(
            [
                'author_id' => $author->getKey(),
                'title' => 'title',
                'annotation' => 'annotation',
                'publication_date' => '2000-12-31T00:00:00.000000Z'
            ]
        );
    }
    
    public function test_store_fail(): void
    {
        $author = Author::factory()->create();

        $response = $this->post(
            "/api/authors/{$author->getKey()}/books",
            [
                'title' => 't',
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment(
            [
                "errors" => [
                    "author_id" => ["The author id field is required."],
                    "publication_date" => ["The publication date field is required."],
                    "title" => ["The title field must be at least 2 characters."]
                ]
            ]
        );
    }
    
    public function test_show(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->getKey()]);
        $chapter = Chapter::factory()->count(15)->create(['book_id' => $book->getKey()]);

        $response = $this->get("/api/authors/{$author->getKey()}/books/{$book->getKey()}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('id', $book->getKey());
        $response->assertJsonPath('author.id', $author->getKey());
        $response->assertJsonPath('chapters_count', 15);
        $response->assertJsonCount(10, 'chapters');
    }
    
    public function test_update(): void
    {
        $author = Author::factory()->create();
        $new_author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->getKey()]);

        $response = $this->patch(
            "/api/authors/{$author->getKey()}/books/{$book->getKey()}",
            [
                'author_id' => $new_author->getKey(),
                'title' => 'title',
                'annotation' => 'annotation',
                'publication_date' => '31-12-2000',
            ]

        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(
            [
                'author_id' => $new_author->getKey(),
                'title' => 'title',
                'annotation' => 'annotation',
                'publication_date' => '2000-12-31T00:00:00.000000Z'
            ]
        );
    }
    
    public function test_update_fail(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->getKey()]);

        $response = $this->patch(
            "/api/authors/{$author->getKey()}/books/{$book->getKey()}",
            [
                'name' => 'n',
                'birthday' => '2000-31-12'
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment(
            [
                "errors" => [
                    "author_id" => ["The author id field is required."],
                    "publication_date" => ["The publication date field is required."],
                    "title" => ["The title field is required."]
                ]
            ]
        );
    }
    
    public function test_delete(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->getKey()]);

        $response = $this->delete("/api/authors/{$author->getKey()}/books/{$book->getKey()}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertNull(Book::find($book->getKey()));
    }
}
