<?php

namespace App\Providers;

use Biblioteca\Domain\User\UserRepository;
use Biblioteca\Domain\Book\BookRepository;
use Biblioteca\Domain\Loan\LoanRepository;
use Biblioteca\Infrastructure\Persistence\Eloquent\Repositories\EloquentUserRepository;
use Biblioteca\Infrastructure\Persistence\Eloquent\Repositories\EloquentBookRepository;
use Biblioteca\Infrastructure\Persistence\Eloquent\Repositories\EloquentLoanRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register repository bindings (Dependency Injection Pattern)
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(BookRepository::class, EloquentBookRepository::class);
        $this->app->bind(LoanRepository::class, EloquentLoanRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
