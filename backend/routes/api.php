<?php

use Illuminate\Support\Facades\Route;
use Biblioteca\Infrastructure\Http\Controllers\UserController;
use Biblioteca\Infrastructure\Http\Controllers\BookController;
use Biblioteca\Infrastructure\Http\Controllers\LoanController;

// User routes
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);

// Book routes
Route::get('/books', [BookController::class, 'index']);
Route::post('/books', [BookController::class, 'store']);
Route::get('/books/{id}', [BookController::class, 'show']);

// Loan routes
Route::get('/loans', [LoanController::class, 'index']);
Route::post('/loans', [LoanController::class, 'store']);
Route::post('/loans/{id}/return', [LoanController::class, 'returnBook']);
Route::get('/loans/report', [LoanController::class, 'getLoansByDateRange']);
