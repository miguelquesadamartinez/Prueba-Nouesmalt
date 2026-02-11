<?php

namespace Biblioteca\Domain\Loan\ValueObjects;

use InvalidArgumentException;

final class LoanId
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    private function validate(string $value): void
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Loan ID cannot be empty');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(LoanId $other): bool
    {
        return $this->value === $other->value;
    }
    
    public function __toString(): string
    {
        return $this->value;
    }
}
