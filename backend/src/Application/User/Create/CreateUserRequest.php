<?php

namespace Biblioteca\Application\User\Create;

final class CreateUserRequest
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $apellidos,
        public readonly string $dni
    ) {}
}
