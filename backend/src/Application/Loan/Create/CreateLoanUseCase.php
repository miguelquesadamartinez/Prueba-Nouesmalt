<?php

namespace Biblioteca\Application\Loan\Create;

use Biblioteca\Domain\Loan\Loan;
use Biblioteca\Domain\Loan\LoanRepository;
use Biblioteca\Domain\Loan\Exceptions\MaxLoansExceededException;
use Biblioteca\Domain\User\UserRepository;
use Biblioteca\Domain\Book\BookRepository;
use Biblioteca\Domain\User\ValueObjects\UserId;
use Biblioteca\Domain\Book\ValueObjects\BookId;
use InvalidArgumentException;

final class CreateLoanUseCase
{
    private const MAX_ACTIVE_LOANS = 3;

    public function __construct(
        private readonly LoanRepository $loanRepository,
        private readonly UserRepository $userRepository,
        private readonly BookRepository $bookRepository
    ) {}

    public function execute(CreateLoanRequest $request): array
    {
        $userId = new UserId($request->userId);
        $bookId = new BookId($request->bookId);

        // Validate user exists
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new InvalidArgumentException('User not found');
        }

        // Validate book exists
        $book = $this->bookRepository->findById($bookId);
        if (!$book) {
            throw new InvalidArgumentException('Book not found');
        }

        // Check active loans limit (Strategy Pattern for validation)
        $activeLoansCount = $this->loanRepository->countActiveByUserId($userId);
        if ($activeLoansCount >= self::MAX_ACTIVE_LOANS) {
            throw new MaxLoansExceededException();
        }

        $loan = Loan::create($userId, $bookId);
        $savedLoan = $this->loanRepository->save($loan);

        return $savedLoan->toArray();
    }
}
