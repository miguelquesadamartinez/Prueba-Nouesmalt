<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('titulo', 255);
            $table->string('autor', 100);
            $table->string('isbn', 20)->unique();
            $table->timestamps();

            $table->index('isbn');
            $table->index('titulo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
