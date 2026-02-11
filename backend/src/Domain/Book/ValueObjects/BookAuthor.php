<?php

namespace Biblioteca\Domain\Book\ValueObjects;

use InvalidArgumentException;

final class BookAuthor
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    private function validate(string $value): void
    {
        if (empty(trim($value))) {
            throw new InvalidArgumentException('Book author cannot be empty');
        }

        if (strlen($value) < 2) {
            throw new InvalidArgumentException('Book author must be at least 2 characters');
        }

        if (strlen($value) > 100) {
            throw new InvalidArgumentException('Book author cannot exceed 100 characters');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(BookAuthor $other): bool
    {
        return $this->value === $other->value;
    }
}
