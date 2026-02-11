<?php

namespace Biblioteca\Application\User\Find;

use Biblioteca\Domain\User\UserRepository;
use Biblioteca\Domain\User\ValueObjects\UserId;

final class FindUserUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function execute(string $id): ?array
    {
        $user = $this->userRepository->findById(new UserId($id));

        return $user?->toArray();
    }
}
