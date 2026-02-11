<?php

namespace Biblioteca\Infrastructure\Persistence\Eloquent\Repositories;

use Biblioteca\Domain\Book\Book;
use Biblioteca\Domain\Book\BookRepository;
use Biblioteca\Domain\Book\ValueObjects\BookId;
use Biblioteca\Domain\Book\ValueObjects\BookTitle;
use Biblioteca\Domain\Book\ValueObjects\BookAuthor;
use Biblioteca\Domain\Book\ValueObjects\ISBN;
use Biblioteca\Infrastructure\Persistence\Eloquent\Models\BookModel;
use DateTimeImmutable;

final class EloquentBookRepository implements BookRepository
{
    public function save(Book $book): Book
    {
        $bookId = $book->id()->value();
        
        $model = BookModel::find($bookId);

        if (!$model) {
            $model = new BookModel();
            $model->id = $bookId;
        }

        $model->fill([
            'titulo' => $book->titulo()->value(),
            'autor' => $book->autor()->value(),
            'isbn' => $book->isbn()->value(),
        ]);

        $model->save();

        return $this->toDomain($model);
    }

    public function findById(BookId $id): ?Book
    {
        $model = BookModel::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findByIsbn(ISBN $isbn): ?Book
    {
        $model = BookModel::where('isbn', $isbn->value())->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findAll(): array
    {
        return BookModel::all()
            ->map(fn($model) => $this->toDomain($model))
            ->toArray();
    }

    public function delete(BookId $id): void
    {
        BookModel::destroy($id->value());
    }

    public function existsByIsbn(ISBN $isbn): bool
    {
        return BookModel::where('isbn', $isbn->value())->exists();
    }

    private function toDomain(BookModel $model): Book
    {
        return new Book(
            new BookId($model->id),
            new BookTitle($model->titulo),
            new BookAuthor($model->autor),
            new ISBN($model->isbn),
            new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            $model->updated_at ? new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')) : null
        );
    }
}
