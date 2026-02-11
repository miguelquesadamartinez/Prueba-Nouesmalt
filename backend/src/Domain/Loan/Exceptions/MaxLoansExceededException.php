<?php

namespace Biblioteca\Domain\Loan\Exceptions;

use DomainException;

final class MaxLoansExceededException extends DomainException
{
    public function __construct()
    {
        parent::__construct('El usuario ha alcanzado el número máximo de préstamos activos (3)');
    }
}
