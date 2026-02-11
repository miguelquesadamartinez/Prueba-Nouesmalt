<?php

namespace Biblioteca\Domain\User\ValueObjects;

use InvalidArgumentException;

final class UserDni
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
            throw new InvalidArgumentException('El DNI no puede estar vacío');
        }

        if (strlen($value) < 5) {
            throw new InvalidArgumentException('El DNI debe tener al menos 5 caracteres');
        }

        if (strlen($value) > 20) {
            throw new InvalidArgumentException('El DNI no puede exceder 20 caracteres');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UserDni $other): bool
    {
        return $this->value === $other->value;
    }
}
