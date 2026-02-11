<?php

namespace Biblioteca\Application\User\ListAll;

use Biblioteca\Domain\User\UserRepository;

final class ListAllUsersUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function execute(): array
    {
        $users = $this->userRepository->findAll();

        return array_map(fn($user) => $user->toArray(), $users);
    }
}
