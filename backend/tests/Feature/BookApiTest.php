<?php

namespace Tests\Feature;

use Biblioteca\Infrastructure\Persistence\Eloquent\Models\BookModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_books(): void
    {
        BookModel::factory()->count(3)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'titulo', 'autor', 'isbn', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_can_create_book(): void
    {
        $bookData = [
            'titulo' => 'Don Quijote de la Mancha',
            'autor' => 'Miguel de Cervantes',
            'isbn' => '9780132350884',
        ];

        $response = $this->postJson('/api/books', $bookData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'titulo', 'autor', 'isbn', 'created_at'],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'titulo' => 'Don Quijote de la Mancha',
                    'autor' => 'Miguel de Cervantes',
                    'isbn' => '9780132350884',
                ]
            ]);

        $this->assertDatabaseHas('books', [
            'titulo' => 'Don Quijote de la Mancha',
            'autor' => 'Miguel de Cervantes',
            'isbn' => '9780132350884',
        ]);
    }

    public function test_cannot_create_book_with_duplicate_isbn(): void
    {
        BookModel::create([
            'titulo' => 'Libro Existente',
            'autor' => 'Autor Existente',
            'isbn' => '9780132350884',
        ]);

        $bookData = [
            'titulo' => 'Libro Nuevo',
            'autor' => 'Autor Nuevo',
            'isbn' => '9780132350884',
        ];

        $response = $this->postJson('/api/books', $bookData);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'El ISBN ya existe',
            ]);
    }

    public function test_cannot_create_book_with_invalid_isbn(): void
    {
        $bookData = [
            'titulo' => 'Libro de Prueba',
            'autor' => 'Autor de Prueba',
            'isbn' => 'invalid',
        ];

        $response = $this->postJson('/api/books', $bookData);

        $response->assertStatus(422);
    }

    public function test_can_get_book_by_id(): void
    {
        $book = BookModel::create([
            'titulo' => 'Cien Años de Soledad',
            'autor' => 'Gabriel García Márquez',
            'isbn' => '9780132350884',
        ]);

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'titulo', 'autor', 'isbn', 'created_at']
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $book->id,
                    'titulo' => 'Cien Años de Soledad',
                    'autor' => 'Gabriel García Márquez',
                    'isbn' => '9780132350884',
                ]
            ]);
    }

    public function test_returns_404_when_book_not_found(): void
    {
        $response = $this->getJson('/api/books/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Book not found',
            ]);
    }
}
