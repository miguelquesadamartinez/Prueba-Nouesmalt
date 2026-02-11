<?php

namespace Biblioteca\Application\Book\Find;

use Biblioteca\Domain\Book\BookRepository;
use Biblioteca\Domain\Book\ValueObjects\BookId;

final class FindBookUseCase
{
    public function __construct(
        private readonly BookRepository $bookRepository
    ) {}

    public function execute(string $id): ?array
    {
        $book = $this->bookRepository->findById(new BookId($id));

        return $book?->toArray();
    }
}
