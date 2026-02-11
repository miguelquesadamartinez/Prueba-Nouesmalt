<?php

namespace Biblioteca\Domain\Loan;

use Biblioteca\Domain\Loan\ValueObjects\LoanId;
use Biblioteca\Domain\User\ValueObjects\UserId;
use Biblioteca\Domain\Book\ValueObjects\BookId;
use DateTimeImmutable;

final class Loan
{
    private ?LoanId $id;
    private UserId $userId;
    private BookId $bookId;
    private DateTimeImmutable $loanDate;
    private ?DateTimeImmutable $returnDate;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public function __construct(
        ?LoanId $id,
        UserId $userId,
        BookId $bookId,
        ?DateTimeImmutable $loanDate = null,
        ?DateTimeImmutable $returnDate = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->bookId = $bookId;
        $this->loanDate = $loanDate ?? new DateTimeImmutable();
        $this->returnDate = $returnDate;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt;
    }

    public static function create(UserId $userId, BookId $bookId): self
    {
        return new self(new LoanId(\Illuminate\Support\Str::uuid()->toString()), $userId, $bookId);
    }

    public function id(): ?LoanId
    {
        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function bookId(): BookId
    {
        return $this->bookId;
    }

    public function loanDate(): DateTimeImmutable
    {
        return $this->loanDate;
    }

    public function returnDate(): ?DateTimeImmutable
    {
        return $this->returnDate;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isActive(): bool
    {
        return $this->returnDate === null;
    }

    public function returnBook(): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('Book has already been returned');
        }

        $this->returnDate = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id?->value(),
            'user_id' => $this->userId->value(),
            'book_id' => $this->bookId->value(),
            'loan_date' => $this->loanDate->format('Y-m-d H:i:s'),
            'return_date' => $this->returnDate?->format('Y-m-d H:i:s'),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
