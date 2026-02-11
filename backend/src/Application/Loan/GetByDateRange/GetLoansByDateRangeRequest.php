<?php

namespace Biblioteca\Application\Loan\GetByDateRange;

final class GetLoansByDateRangeRequest
{
    public function __construct(
        public readonly string $startDate,
        public readonly string $endDate
    ) {}
}
