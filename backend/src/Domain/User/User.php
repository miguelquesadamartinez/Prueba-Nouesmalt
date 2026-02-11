<?php

namespace Biblioteca\Domain\User;

use Biblioteca\Domain\User\ValueObjects\UserId;
use Biblioteca\Domain\User\ValueObjects\UserApellidos;
use Biblioteca\Domain\User\ValueObjects\UserDni;
use Biblioteca\Domain\User\ValueObjects\UserName;
use DateTimeImmutable;

final class User
{
    private ?UserId $id;
    private UserName $nombre;
    private UserApellidos $apellidos;
    private UserDni $dni;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public function __construct(
        ?UserId $id,
        UserName $nombre,
        UserApellidos $apellidos,
        UserDni $dni,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->dni = $dni;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt;
    }

    public static function create(UserName $nombre, UserApellidos $apellidos, UserDni $dni): self
    {
        return new self(new UserId(\Illuminate\Support\Str::uuid()->toString()), $nombre, $apellidos, $dni);
    }

    public function id(): ?UserId
    {
        return $this->id;
    }

    public function nombre(): UserName
    {
        return $this->nombre;
    }

    public function apellidos(): UserApellidos
    {
        return $this->apellidos;
    }

    public function dni(): UserDni
    {
        return $this->dni;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id?->value(),
            'nombre' => $this->nombre->value(),
            'apellidos' => $this->apellidos->value(),
            'dni' => $this->dni->value(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
