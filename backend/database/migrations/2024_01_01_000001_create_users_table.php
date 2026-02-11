<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre', 100);
            $table->string('apellidos', 150);
            $table->string('dni', 20)->unique();
            $table->timestamps();

            $table->index('dni');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
