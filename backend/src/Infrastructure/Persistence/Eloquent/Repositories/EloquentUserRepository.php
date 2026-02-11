<?php

namespace Biblioteca\Infrastructure\Persistence\Eloquent\Repositories;

use Biblioteca\Domain\User\User;
use Biblioteca\Domain\User\UserRepository;
use Biblioteca\Domain\User\ValueObjects\UserId;
use Biblioteca\Domain\User\ValueObjects\UserApellidos;
use Biblioteca\Domain\User\ValueObjects\UserDni;
use Biblioteca\Domain\User\ValueObjects\UserName;
use Biblioteca\Infrastructure\Persistence\Eloquent\Models\UserModel;
use DateTimeImmutable;

final class EloquentUserRepository implements UserRepository
{
    public function save(User $user): User
    {
        $userId = $user->id()->value();
        
        $model = UserModel::find($userId);

        if (!$model) {
            $model = new UserModel();
            $model->id = $userId;
        }

        $model->fill([
            'nombre' => $user->nombre()->value(),
            'apellidos' => $user->apellidos()->value(),
            'dni' => $user->dni()->value(),
        ]);

        $model->save();

        return $this->toDomain($model);
    }

    public function findById(UserId $id): ?User
    {
        $model = UserModel::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findByDni(UserDni $dni): ?User
    {
        $model = UserModel::where('dni', $dni->value())->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findAll(): array
    {
        return UserModel::all()
            ->map(fn($model) => $this->toDomain($model))
            ->toArray();
    }

    public function delete(UserId $id): void
    {
        UserModel::destroy($id->value());
    }

    public function existsByDni(UserDni $dni): bool
    {
        return UserModel::where('dni', $dni->value())->exists();
    }

    private function toDomain(UserModel $model): User
    {
        return new User(
            new UserId($model->id),
            new UserName($model->nombre),
            new UserApellidos($model->apellidos),
            new UserDni($model->dni),
            new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            $model->updated_at ? new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')) : null
        );
    }
}
