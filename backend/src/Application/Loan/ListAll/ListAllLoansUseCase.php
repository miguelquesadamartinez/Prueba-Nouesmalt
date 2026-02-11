<?php

namespace Biblioteca\Application\Loan\ListAll;

use Biblioteca\Domain\Loan\LoanRepository;

final class ListAllLoansUseCase
{
    public function __construct(
        private readonly LoanRepository $loanRepository
    ) {}

    public function execute(): array
    {
        $loans = $this->loanRepository->findAll();

        return array_map(fn($loan) => $loan->toArray(), $loans);
    }
}
