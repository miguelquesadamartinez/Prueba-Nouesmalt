<?php

namespace Tests\Feature;

use Biblioteca\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Biblioteca\Infrastructure\Persistence\Eloquent\Models\BookModel;
use Biblioteca\Infrastructure\Persistence\Eloquent\Models\LoanModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_loans(): void
    {
        $user = UserModel::factory()->create();
        $book = BookModel::factory()->create();
        
        LoanModel::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => now(),
        ]);

        $response = $this->getJson('/api/loans');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'user_id', 'book_id', 'loan_date', 'return_date', 'created_at']
                ]
            ]);
    }

    public function test_can_create_loan(): void
    {
        $user = UserModel::factory()->create();
        $book = BookModel::factory()->create();

        $loanData = [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ];

        $response = $this->postJson('/api/loans', $loanData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'user_id', 'book_id', 'loan_date', 'return_date', 'created_at'],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                ]
            ]);

        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    public function test_cannot_create_loan_with_nonexistent_user(): void
    {
        $book = BookModel::factory()->create();

        $loanData = [
            'user_id' => '00000000-0000-0000-0000-000000000000',
            'book_id' => $book->id,
        ];

        $response = $this->postJson('/api/loans', $loanData);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'User not found',
            ]);
    }

    public function test_cannot_create_loan_with_nonexistent_book(): void
    {
        $user = UserModel::factory()->create();

        $loanData = [
            'user_id' => $user->id,
            'book_id' => '11111111-1111-1111-1111-111111111111',
        ];

        $response = $this->postJson('/api/loans', $loanData);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Book not found',
            ]);
    }

    public function test_cannot_create_more_than_3_active_loans_per_user(): void
    {
        $user = UserModel::factory()->create();
        $books = BookModel::factory()->count(4)->create();

        // Create 3 active loans
        for ($i = 0; $i < 3; $i++) {
            LoanModel::create([
                'user_id' => $user->id,
                'book_id' => $books[$i]->id,
                'loan_date' => now(),
                'return_date' => null,
            ]);
        }

        // Try to create a 4th loan
        $loanData = [
            'user_id' => $user->id,
            'book_id' => $books[3]->id,
        ];

        $response = $this->postJson('/api/loans', $loanData);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'El usuario ha alcanzado el número máximo de préstamos activos (3)',
            ]);
    }

    public function test_can_create_loan_after_returning_books(): void
    {
        $user = UserModel::factory()->create();
        $books = BookModel::factory()->count(4)->create();

        // Create 3 active loans and return 1
        for ($i = 0; $i < 3; $i++) {
            $loan = LoanModel::create([
                'user_id' => $user->id,
                'book_id' => $books[$i]->id,
                'loan_date' => now(),
                'return_date' => $i === 0 ? now() : null, // Return the first one
            ]);
        }

        // Should be able to create another loan
        $loanData = [
            'user_id' => $user->id,
            'book_id' => $books[3]->id,
        ];

        $response = $this->postJson('/api/loans', $loanData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_can_return_book(): void
    {
        $user = UserModel::factory()->create();
        $book = BookModel::factory()->create();
        
        $loan = LoanModel::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => now(),
            'return_date' => null,
        ]);

        $response = $this->postJson("/api/loans/{$loan->id}/return");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Book returned successfully',
            ]);

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
        ]);

        $this->assertDatabaseMissing('loans', [
            'id' => $loan->id,
            'return_date' => null,
        ]);
    }

    public function test_cannot_return_book_twice(): void
    {
        $user = UserModel::factory()->create();
        $book = BookModel::factory()->create();
        
        $loan = LoanModel::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => now(),
            'return_date' => now(),
        ]);

        $response = $this->postJson("/api/loans/{$loan->id}/return");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Book has already been returned',
            ]);
    }

    public function test_can_get_loans_by_date_range(): void
    {
        $user = UserModel::factory()->create();
        $book = BookModel::factory()->create();
        
        LoanModel::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => now()->subDays(5),
        ]);

        $response = $this->getJson('/api/loans/report?start_date=' . now()->subDays(10)->format('Y-m-d') . '&end_date=' . now()->format('Y-m-d'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['user_id', 'user_nombre', 'user_apellidos', 'user_dni', 'total_loans', 'first_loan_date', 'last_loan_date']
                ],
                'period' => ['start_date', 'end_date']
            ]);
    }

    public function test_date_range_query_requires_valid_dates(): void
    {
        $response = $this->getJson('/api/loans/report?start_date=invalid&end_date=2024-01-01');

        $response->assertStatus(422);
    }

    public function test_date_range_query_requires_end_date_after_start_date(): void
    {
        $response = $this->getJson('/api/loans/report?start_date=2024-01-10&end_date=2024-01-05');

        $response->assertStatus(422);
    }
}
