<?php

namespace Biblioteca\Domain\Book\ValueObjects;

use InvalidArgumentException;

final class BookTitle
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
            throw new InvalidArgumentException('Book title cannot be empty');
        }

        if (strlen($value) < 1) {
            throw new InvalidArgumentException('Book title must be at least 1 character');
        }

        if (strlen($value) > 255) {
            throw new InvalidArgumentException('Book title cannot exceed 255 characters');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(BookTitle $other): bool
    {
        return $this->value === $other->value;
    }
}
