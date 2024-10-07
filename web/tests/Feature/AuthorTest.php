<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_index(): void
    {
        $authors = Author::factory()->count(20)->create();
        Book::factory()->count(2)->create(['author_id' => 2]);

        $response = $this->get('/api/authors');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.0.id', $authors[1]->getKey());
        $response->assertJsonPath('data.0.books_count', 2);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('current_page', 1);
        $response->assertJsonPath('per_page', 15);
    }
    
    public function test_store(): void
    {
        $response = $this->post(
            '/api/authors',
            [
                'name' => 'name',
                'information' => 'information',
                'birthday' => '31-12-2000'
            ]
        );

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(
            [
                'name' => 'name',
                'information' => 'information',
                'birthday' => '2000-12-31T00:00:00.000000Z'
            ]
        );
    }
    
    public function test_store_fail(): void
    {
        $response = $this->post(
            '/api/authors',
            [
                'name' => 'n',
                'birthday' => '2000-31-12'
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment(
            [
                "errors" => [
                    "birthday" => ["The birthday field must match the format d-m-Y."],
                    "name" => ["The name field must be at least 2 characters."]
                ]
            ]
        );
    }
    
    public function test_show(): void
    {
        $author = Author::factory()->create();
        Book::factory()->count(2)->create(['author_id' => $author->getKey()]);

        $response = $this->get('/api/authors/' . $author->getKey());

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(2, 'books');
    }
    
    public function test_update(): void
    {
        $author = Author::factory()->create();

        $response = $this->patch(
            '/api/authors/' . $author->getKey(),
            [
                'name' => 'name',
                'information' => 'information',
                'birthday' => '31-12-2000'
            ]
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(
            [
                'name' => 'name',
                'information' => 'information',
                'birthday' => '2000-12-31T00:00:00.000000Z'
            ]
        );
    }
    
    public function test_update_fail(): void
    {
        $author = Author::factory()->create();

        $response = $this->patch(
            '/api/authors/' . $author->getKey(),
            [
                'name' => 'n',
                'birthday' => '2000-31-12'
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment(
            [
                "errors" => [
                    "birthday" => ["The birthday field must match the format d-m-Y."],
                    "name" => ["The name field must be at least 2 characters."]
                ]
            ]
        );
    }
    
    public function test_delete(): void
    {
        $author = Author::factory()->create();

        $response = $this->delete('/api/authors/' . $author->getKey());

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertNull(Author::find($author->getKey()));
    }
}
