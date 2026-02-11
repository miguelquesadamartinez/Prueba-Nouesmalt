<?php

namespace Biblioteca\Application\Loan\Create;

final class CreateLoanRequest
{
    public function __construct(
        public readonly string $userId,
        public readonly string $bookId
    ) {}
}
