<?php

namespace Biblioteca\Infrastructure\Persistence\Eloquent\Repositories;

use Biblioteca\Domain\Loan\Loan;
use Biblioteca\Domain\Loan\LoanRepository;
use Biblioteca\Domain\Loan\ValueObjects\LoanId;
use Biblioteca\Domain\User\ValueObjects\UserId;
use Biblioteca\Domain\Book\ValueObjects\BookId;
use Biblioteca\Infrastructure\Persistence\Eloquent\Models\LoanModel;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;

final class EloquentLoanRepository implements LoanRepository
{
    public function save(Loan $loan): Loan
    {
        if ($loan->id() && LoanModel::find($loan->id()->value())) {
            $model = LoanModel::findOrFail($loan->id()->value());
        } else {
            $model = new LoanModel();
        }

        $model->fill([
            'id' => $loan->id()->value(),
            'user_id' => $loan->userId()->value(),
            'book_id' => $loan->bookId()->value(),
            'loan_date' => $loan->loanDate()->format('Y-m-d H:i:s'),
            'return_date' => $loan->returnDate()?->format('Y-m-d H:i:s'),
        ]);

        $model->save();

        return $this->toDomain($model);
    }

    public function findById(LoanId $id): ?Loan
    {
        $model = LoanModel::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findAll(): array
    {
        return LoanModel::all()
            ->map(fn($model) => $this->toDomain($model))
            ->toArray();
    }

    public function countActiveByUserId(UserId $userId): int
    {
        return LoanModel::where('user_id', $userId->value())
            ->whereNull('return_date')
            ->count();
    }

    public function findActiveByUserId(UserId $userId): array
    {
        return LoanModel::where('user_id', $userId->value())
            ->whereNull('return_date')
            ->get()
            ->map(fn($model) => $this->toDomain($model))
            ->toArray();
    }

    public function findByDateRange(DateTimeImmutable $startDate, DateTimeImmutable $endDate): array
    {
        return LoanModel::whereBetween('loan_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d 23:59:59')
        ])
            ->get()
            ->map(fn($model) => $this->toDomain($model))
            ->toArray();
    }

    public function getUsersWithLoanCountByDateRange(DateTimeImmutable $startDate, DateTimeImmutable $endDate): array
    {
        return DB::table('loans')
            ->join('users', 'loans.user_id', '=', 'users.id')
            ->whereBetween('loans.loan_date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d 23:59:59')
            ])
            ->select(
                'users.id', 
                'users.nombre', 
                'users.apellidos', 
                'users.dni', 
                DB::raw('COUNT(loans.id) as total_loans'),
                DB::raw('MIN(loans.loan_date) as first_loan_date'),
                DB::raw('MAX(loans.loan_date) as last_loan_date')
            )
            ->groupBy('users.id', 'users.nombre', 'users.apellidos', 'users.dni')
            ->orderBy('total_loans', 'desc')
            ->get()
            ->map(fn($row) => [
                'user_id' => $row->id,
                'user_nombre' => $row->nombre,
                'user_apellidos' => $row->apellidos,
                'user_dni' => $row->dni,
                'total_loans' => $row->total_loans,
                'first_loan_date' => $row->first_loan_date,
                'last_loan_date' => $row->last_loan_date,
            ])
            ->toArray();
    }

    public function delete(LoanId $id): void
    {
        LoanModel::destroy($id->value());
    }

    private function toDomain(LoanModel $model): Loan
    {
        return new Loan(
            new LoanId($model->id),
            new UserId($model->user_id),
            new BookId($model->book_id),
            new DateTimeImmutable($model->loan_date->format('Y-m-d H:i:s')),
            $model->return_date ? new DateTimeImmutable($model->return_date->format('Y-m-d H:i:s')) : null,
            new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            $model->updated_at ? new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')) : null
        );
    }
}
