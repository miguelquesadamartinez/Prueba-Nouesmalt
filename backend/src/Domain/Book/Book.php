<?php

namespace Biblioteca\Domain\Book;

use Biblioteca\Domain\Book\ValueObjects\BookId;
use Biblioteca\Domain\Book\ValueObjects\BookTitle;
use Biblioteca\Domain\Book\ValueObjects\BookAuthor;
use Biblioteca\Domain\Book\ValueObjects\ISBN;
use DateTimeImmutable;

final class Book
{
    private ?BookId $id;
    private BookTitle $titulo;
    private BookAuthor $autor;
    private ISBN $isbn;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public function __construct(
        ?BookId $id,
        BookTitle $titulo,
        BookAuthor $autor,
        ISBN $isbn,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->isbn = $isbn;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt;
    }

    public static function create(BookTitle $titulo, BookAuthor $autor, ISBN $isbn): self
    {
        return new self(new BookId(\Illuminate\Support\Str::uuid()->toString()), $titulo, $autor, $isbn);
    }

    public function id(): ?BookId
    {
        return $this->id;
    }

    public function titulo(): BookTitle
    {
        return $this->titulo;
    }

    public function autor(): BookAuthor
    {
        return $this->autor;
    }

    public function isbn(): ISBN
    {
        return $this->isbn;
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
            'titulo' => $this->titulo->value(),
            'autor' => $this->autor->value(),
            'isbn' => $this->isbn->value(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
