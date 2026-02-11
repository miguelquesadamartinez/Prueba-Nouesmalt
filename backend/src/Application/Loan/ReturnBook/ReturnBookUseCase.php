<?php

namespace Biblioteca\Application\Loan\ReturnBook;

use Biblioteca\Domain\Loan\LoanRepository;
use Biblioteca\Domain\Loan\ValueObjects\LoanId;
use InvalidArgumentException;

final class ReturnBookUseCase
{
    public function __construct(
        private readonly LoanRepository $loanRepository
    ) {}

    public function execute(string $loanId): array
    {
        $loan = $this->loanRepository->findById(new LoanId($loanId));

        if (!$loan) {
            throw new InvalidArgumentException('Loan not found');
        }

        $loan->returnBook();
        $updatedLoan = $this->loanRepository->save($loan);

        return $updatedLoan->toArray();
    }
}
