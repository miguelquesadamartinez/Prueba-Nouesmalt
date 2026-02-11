<?php

namespace Biblioteca\Infrastructure\Http\Controllers;

use Biblioteca\Application\Book\Create\CreateBookRequest;
use Biblioteca\Application\Book\Create\CreateBookUseCase;
use Biblioteca\Application\Book\Find\FindBookUseCase;
use Biblioteca\Application\Book\ListAll\ListAllBooksUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

final class BookController
{
    public function __construct(
        private readonly CreateBookUseCase $createBookUseCase,
        private readonly FindBookUseCase $findBookUseCase,
        private readonly ListAllBooksUseCase $listAllBooksUseCase
    ) {}

    public function index(): JsonResponse
    {
        try {
            $books = $this->listAllBooksUseCase->execute();

            return response()->json([
                'success' => true,
                'data' => $books,
            ]);
        } catch (Throwable $e) {
            Log::error('Error listing books', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving books',
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'titulo' => 'required|string|min:1|max:255',
            'autor' => 'required|string|min:2|max:100',
            'isbn' => 'required|string|regex:/^[\d\-\sX]{10,17}$/',
        ]);

        try {
            $createRequest = new CreateBookRequest(
                $validated['titulo'],
                $validated['autor'],
                $validated['isbn']
            );

            $book = $this->createBookUseCase->execute($createRequest);

            Log::info('Book created', ['book_id' => $book['id']]);

            return response()->json([
                'success' => true,
                'data' => $book,
                'message' => 'Libro creado exitosamente',
            ], 201);
        } catch (\InvalidArgumentException $e) {
            Log::error('Error creating book', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (Throwable $e) {
            Log::error('Error creating book', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear libro',
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $book = $this->findBookUseCase->execute($id);

            if (!$book) {
                return response()->json([
                    'success' => false,
                    'message' => 'Book not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $book,
            ]);
        } catch (\InvalidArgumentException $e) {
            Log::error('Error finding book', ['book_id' => $id, 'error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Book not found',
            ], 404);
        } catch (Throwable $e) {
            Log::error('Error finding book', ['book_id' => $id, 'error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving book',
            ], 500);
        }
    }
}
