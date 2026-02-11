<?php

namespace Biblioteca\Domain\Book\ValueObjects;

use InvalidArgumentException;

final class ISBN
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    private function validate(string $value): void
    {
        // Remove hyphens and spaces
        $cleanValue = preg_replace('/[\s\-]/', '', $value);

        // Validate ISBN-10 or ISBN-13 format
        if (!preg_match('/^(?:\d{9}X|\d{10}|\d{13})$/', $cleanValue)) {
            throw new InvalidArgumentException('Invalid ISBN format. Must be ISBN-10 or ISBN-13');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(ISBN $other): bool
    {
        return $this->value === $other->value;
    }
}
