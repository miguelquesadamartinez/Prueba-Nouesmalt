<?php

namespace Biblioteca\Domain\User;

use Biblioteca\Domain\User\ValueObjects\UserId;
use Biblioteca\Domain\User\ValueObjects\UserDni;

interface UserRepository
{
    public function save(User $user): User;
    
    public function findById(UserId $id): ?User;
    
    public function findByDni(UserDni $dni): ?User;
    
    public function findAll(): array;
    
    public function delete(UserId $id): void;
    
    public function existsByDni(UserDni $dni): bool;
}
