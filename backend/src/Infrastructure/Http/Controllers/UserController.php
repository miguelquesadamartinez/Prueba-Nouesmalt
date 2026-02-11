<?php

namespace Biblioteca\Infrastructure\Http\Controllers;

use Biblioteca\Application\User\Create\CreateUserRequest;
use Biblioteca\Application\User\Create\CreateUserUseCase;
use Biblioteca\Application\User\Find\FindUserUseCase;
use Biblioteca\Application\User\ListAll\ListAllUsersUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

final class UserController
{
    public function __construct(
        private readonly CreateUserUseCase $createUserUseCase,
        private readonly FindUserUseCase $findUserUseCase,
        private readonly ListAllUsersUseCase $listAllUsersUseCase
    ) {}

    public function index(): JsonResponse
    {
        try {
            $users = $this->listAllUsersUseCase->execute();

            return response()->json([
                'success' => true,
                'data' => $users,
            ]);
        } catch (Throwable $e) {
            Log::error('Error listing users', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving users',
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|min:2|max:100',
            'apellidos' => 'required|string|min:2|max:150',
            'dni' => 'required|string|min:5|max:20',
        ]);

        try {
            $createRequest = new CreateUserRequest(
                $validated['nombre'],
                $validated['apellidos'],
                $validated['dni']
            );

            $user = $this->createUserUseCase->execute($createRequest);

            Log::info('User created', ['user_id' => $user['id']]);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Usuario creado exitosamente',
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (Throwable $e) {
            Log::error('Error creating user', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear usuario',
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $user = $this->findUserUseCase->execute($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        } catch (Throwable $e) {
            Log::error('Error finding user', ['user_id' => $id, 'error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving user',
            ], 500);
        }
    }
}
