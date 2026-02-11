<?php

namespace Biblioteca\Application\User\Create;

use Biblioteca\Domain\User\User;
use Biblioteca\Domain\User\UserRepository;
use Biblioteca\Domain\User\ValueObjects\UserApellidos;
use Biblioteca\Domain\User\ValueObjects\UserDni;
use Biblioteca\Domain\User\ValueObjects\UserName;
use InvalidArgumentException;

final class CreateUserUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function execute(CreateUserRequest $request): array
    {
        $dni = new UserDni($request->dni);
        
        if ($this->userRepository->existsByDni($dni)) {
            throw new InvalidArgumentException('El DNI ya existe');
        }

        $user = User::create(
            new UserName($request->nombre),
            new UserApellidos($request->apellidos),
            $dni
        );

        $savedUser = $this->userRepository->save($user);

        return $savedUser->toArray();
    }
}
