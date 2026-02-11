<?php

namespace Biblioteca\Domain\Book;

use Biblioteca\Domain\Book\ValueObjects\BookId;
use Biblioteca\Domain\Book\ValueObjects\ISBN;

interface BookRepository
{
    public function save(Book $book): Book;
    
    public function findById(BookId $id): ?Book;
    
    public function findByIsbn(ISBN $isbn): ?Book;
    
    public function findAll(): array;
    
    public function delete(BookId $id): void;
    
    public function existsByIsbn(ISBN $isbn): bool;
}
