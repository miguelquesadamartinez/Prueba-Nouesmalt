<?php

namespace Biblioteca\Infrastructure\Http\Controllers;

use Biblioteca\Application\Loan\Create\CreateLoanRequest;
use Biblioteca\Application\Loan\Create\CreateLoanUseCase;
use Biblioteca\Application\Loan\ReturnBook\ReturnBookUseCase;
use Biblioteca\Application\Loan\GetByDateRange\GetLoansByDateRangeRequest;
use Biblioteca\Application\Loan\GetByDateRange\GetLoansByDateRangeUseCase;
use Biblioteca\Application\Loan\ListAll\ListAllLoansUseCase;
use Biblioteca\Domain\Loan\Exceptions\MaxLoansExceededException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

final class LoanController
{
    public function __construct(
        private readonly CreateLoanUseCase $createLoanUseCase,
        private readonly ReturnBookUseCase $returnBookUseCase,
        private readonly GetLoansByDateRangeUseCase $getLoansByDateRangeUseCase,
        private readonly ListAllLoansUseCase $listAllLoansUseCase
    ) {}

    public function index(): JsonResponse
    {
        try {
            $loans = $this->listAllLoansUseCase->execute();

            return response()->json([
                'success' => true,
                'data' => $loans,
            ]);
        } catch (Throwable $e) {
            Log::error('Error listing loans', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving loans',
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|string',
                'book_id' => 'required|string',
            ]);

            $createRequest = new CreateLoanRequest(
                $validated['user_id'],
                $validated['book_id']
            );

            $loan = $this->createLoanUseCase->execute($createRequest);

            Log::info('Loan created', ['loan_id' => $loan['id'], 'user_id' => $loan['user_id']]);

            return response()->json([
                'success' => true,
                'data' => $loan,
                'message' => 'Loan created successfully',
            ], 201);
        } catch (ValidationException $e) {
            Log::error('Error creating loan', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (MaxLoansExceededException $e) {
            Log::error('Error creating loan', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\InvalidArgumentException $e) {
            Log::error('Error creating loan', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (Throwable $e) {
            Log::error('Error creating loan', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating loan',
            ], 500);
        }
    }

    public function returnBook(string $id): JsonResponse
    {
        try {
            $loan = $this->returnBookUseCase->execute($id);

            Log::info('Book returned', ['loan_id' => $id]);

            return response()->json([
                'success' => true,
                'data' => $loan,
                'message' => 'Book returned successfully',
            ]);
        } catch (\DomainException $e) {
            Log::error('Error returning book', ['loan_id' => $id, 'error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\InvalidArgumentException $e) {
            Log::error('Error returning book', ['loan_id' => $id, 'error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        } catch (Throwable $e) {
            Log::error('Error returning book', ['loan_id' => $id, 'error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error returning book',
            ], 500);
        }
    }

    public function getLoansByDateRange(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            ]);

            $dateRangeRequest = new GetLoansByDateRangeRequest(
                $validated['start_date'],
                $validated['end_date']
            );

            $result = $this->getLoansByDateRangeUseCase->execute($dateRangeRequest);

            return response()->json([
                'success' => true,
                'data' => $result,
                'period' => [
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\InvalidArgumentException $e) {
            Log::error('Error getting loans by date range', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (Throwable $e) {
            Log::error('Error getting loans by date range', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving loans',
            ], 500);
        }
    }
}
