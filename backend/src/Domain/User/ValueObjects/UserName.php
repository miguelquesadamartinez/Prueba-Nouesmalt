<?php

namespace Biblioteca\Domain\User\ValueObjects;

use InvalidArgumentException;

final class UserName
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
            throw new InvalidArgumentException('El nombre no puede estar vacío');
        }

        if (strlen($value) < 2) {
            throw new InvalidArgumentException('El nombre debe tener al menos 2 caracteres');
        }

        if (strlen($value) > 100) {
            throw new InvalidArgumentException('El nombre no puede exceder 100 caracteres');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UserName $other): bool
    {
        return $this->value === $other->value;
    }
}
