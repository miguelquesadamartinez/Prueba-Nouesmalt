<?php

namespace Biblioteca\Domain\Loan;

use Biblioteca\Domain\Loan\ValueObjects\LoanId;
use Biblioteca\Domain\User\ValueObjects\UserId;
use Biblioteca\Domain\Book\ValueObjects\BookId;
use DateTimeImmutable;

interface LoanRepository
{
    public function save(Loan $loan): Loan;
    
    public function findById(LoanId $id): ?Loan;
    
    public function findAll(): array;
    
    public function countActiveByUserId(UserId $userId): int;
    
    public function findActiveByUserId(UserId $userId): array;
    
    public function findByDateRange(DateTimeImmutable $startDate, DateTimeImmutable $endDate): array;
    
    public function getUsersWithLoanCountByDateRange(DateTimeImmutable $startDate, DateTimeImmutable $endDate): array;
    
    public function delete(LoanId $id): void;
}
