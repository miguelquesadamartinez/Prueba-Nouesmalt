<?php

namespace Biblioteca\Application\Loan\GetByDateRange;

use Biblioteca\Domain\Loan\LoanRepository;
use DateTimeImmutable;
use InvalidArgumentException;

final class GetLoansByDateRangeUseCase
{
    public function __construct(
        private readonly LoanRepository $loanRepository
    ) {}

    public function execute(GetLoansByDateRangeRequest $request): array
    {
        try {
            $startDate = new DateTimeImmutable($request->startDate);
            $endDate = new DateTimeImmutable($request->endDate);
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid date format. Use Y-m-d format');
        }

        if ($startDate > $endDate) {
            throw new InvalidArgumentException('Start date must be before or equal to end date');
        }

        return $this->loanRepository->getUsersWithLoanCountByDateRange($startDate, $endDate);
    }
}
