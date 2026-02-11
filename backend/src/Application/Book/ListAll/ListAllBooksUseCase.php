<?php

namespace Biblioteca\Application\Book\ListAll;

use Biblioteca\Domain\Book\BookRepository;

final class ListAllBooksUseCase
{
    public function __construct(
        private readonly BookRepository $bookRepository
    ) {}

    public function execute(): array
    {
        $books = $this->bookRepository->findAll();

        return array_map(fn($book) => $book->toArray(), $books);
    }
}
