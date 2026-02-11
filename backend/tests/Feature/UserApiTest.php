<?php

namespace Tests\Feature;

use Biblioteca\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_users(): void
    {
        UserModel::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'nombre', 'apellidos', 'dni', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_can_create_user(): void
    {
        $userData = [
            'nombre' => 'Juan',
            'apellidos' => 'García López',
            'dni' => '12345678A',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'nombre', 'apellidos', 'dni', 'created_at'],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'nombre' => 'Juan',
                    'apellidos' => 'García López',
                    'dni' => '12345678A',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'dni' => '12345678A',
            'nombre' => 'Juan',
            'apellidos' => 'García López',
        ]);
    }

    public function test_cannot_create_user_with_duplicate_dni(): void
    {
        UserModel::create([
            'nombre' => 'Usuario Existente',
            'apellidos' => 'Apellido Test',
            'dni' => '87654321B',
        ]);

        $userData = [
            'nombre' => 'Nuevo Usuario',
            'apellidos' => 'Nuevo Apellido',
            'dni' => '87654321B',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'El DNI ya existe',
            ]);
    }

    public function test_cannot_create_user_with_short_dni(): void
    {
        $userData = [
            'nombre' => 'Test',
            'apellidos' => 'Usuario',
            'dni' => 'ABC',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422);
    }

    public function test_cannot_create_user_with_short_nombre(): void
    {
        $userData = [
            'nombre' => 'A',
            'apellidos' => 'García López',
            'dni' => '12345678A',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422);
    }

    public function test_can_get_user_by_id(): void
    {
        $user = UserModel::create([
            'nombre' => 'María',
            'apellidos' => 'Fernández Ruiz',
            'dni' => '11223344C',
        ]);

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'nombre', 'apellidos', 'dni', 'created_at']
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'nombre' => 'María',
                    'apellidos' => 'Fernández Ruiz',
                    'dni' => '11223344C',
                ]
            ]);
    }

    public function test_returns_404_when_user_not_found(): void
    {
        $response = $this->getJson('/api/users/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'User not found',
            ]);
    }
}
