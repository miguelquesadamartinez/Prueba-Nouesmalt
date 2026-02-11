<?php

namespace Biblioteca\Domain\User\ValueObjects;

use InvalidArgumentException;

final class UserApellidos
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
            throw new InvalidArgumentException('Los apellidos no pueden estar vacíos');
        }

        if (strlen($value) < 2) {
            throw new InvalidArgumentException('Los apellidos deben tener al menos 2 caracteres');
        }

        if (strlen($value) > 150) {
            throw new InvalidArgumentException('Los apellidos no pueden exceder 150 caracteres');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UserApellidos $other): bool
    {
        return $this->value === $other->value;
    }
}
