<?php

namespace Biblioteca\Application\Book\Create;

use Biblioteca\Domain\Book\Book;
use Biblioteca\Domain\Book\BookRepository;
use Biblioteca\Domain\Book\ValueObjects\BookTitle;
use Biblioteca\Domain\Book\ValueObjects\BookAuthor;
use Biblioteca\Domain\Book\ValueObjects\ISBN;
use InvalidArgumentException;

final class CreateBookUseCase
{
    public function __construct(
        private readonly BookRepository $bookRepository
    ) {}

    public function execute(CreateBookRequest $request): array
    {
        $isbn = new ISBN($request->isbn);
        
        if ($this->bookRepository->existsByIsbn($isbn)) {
            throw new InvalidArgumentException('El ISBN ya existe');
        }

        $book = Book::create(
            new BookTitle($request->titulo),
            new BookAuthor($request->autor),
            $isbn
        );

        $savedBook = $this->bookRepository->save($book);

        return $savedBook->toArray();
    }
}
