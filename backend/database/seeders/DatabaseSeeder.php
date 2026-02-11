<?php

namespace Database\Seeders;

use Biblioteca\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Biblioteca\Infrastructure\Persistence\Eloquent\Models\BookModel;
use Biblioteca\Infrastructure\Persistence\Eloquent\Models\LoanModel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create users (idempotent - won't fail if already exists)
        $users = [
            ['nombre' => 'Juan', 'apellidos' => 'Pérez García', 'dni' => '12345678A'],
            ['nombre' => 'María', 'apellidos' => 'García López', 'dni' => '23456789B'],
            ['nombre' => 'Carlos', 'apellidos' => 'López Martínez', 'dni' => '34567890C'],
            ['nombre' => 'Ana', 'apellidos' => 'Martínez Ruiz', 'dni' => '45678901D'],
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $createdUsers[] = UserModel::updateOrCreate(
                ['dni' => $userData['dni']], // Buscar por DNI
                $userData // Datos a crear o actualizar
            );
        }

        // Create books (idempotent - won't fail if already exists)
        $books = [
            ['titulo' => 'Clean Code', 'autor' => 'Robert C. Martin', 'isbn' => '9780132350884'],
            ['titulo' => 'Domain-Driven Design', 'autor' => 'Eric Evans', 'isbn' => '9780321125217'],
            ['titulo' => 'Design Patterns', 'autor' => 'Gang of Four', 'isbn' => '9780201633610'],
            ['titulo' => 'The Pragmatic Programmer', 'autor' => 'Andrew Hunt', 'isbn' => '9780135957059'],
            ['titulo' => 'Refactoring', 'autor' => 'Martin Fowler', 'isbn' => '9780201485677'],
        ];

        $createdBooks = [];
        foreach ($books as $bookData) {
            $createdBooks[] = BookModel::updateOrCreate(
                ['isbn' => $bookData['isbn']], // Buscar por ISBN
                $bookData // Datos a crear o actualizar
            );
        }

        // Create some loans (only if users and books were created/found)
        if (count($createdUsers) >= 2 && count($createdBooks) >= 3) {
            // Solo crear préstamos si no existen ya (evitar duplicados)
            LoanModel::firstOrCreate([
                'user_id' => $createdUsers[0]->id,
                'book_id' => $createdBooks[0]->id,
            ], [
                'loan_date' => now()->subDays(10),
                'return_date' => now()->subDays(5),
            ]);

            LoanModel::firstOrCreate([
                'user_id' => $createdUsers[0]->id,
                'book_id' => $createdBooks[1]->id,
            ], [
                'loan_date' => now()->subDays(5),
                'return_date' => null,
            ]);

            LoanModel::firstOrCreate([
                'user_id' => $createdUsers[1]->id,
                'book_id' => $createdBooks[2]->id,
            ], [
                'loan_date' => now()->subDays(3),
                'return_date' => null,
            ]);
        }
    }
}
