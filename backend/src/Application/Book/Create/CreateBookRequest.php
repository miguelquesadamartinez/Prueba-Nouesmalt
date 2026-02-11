<?php

namespace Biblioteca\Application\Book\Create;

final class CreateBookRequest
{
    public function __construct(
        public readonly string $titulo,
        public readonly string $autor,
        public readonly string $isbn
    ) {}
}
