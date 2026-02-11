<?php

namespace Biblioteca\Infrastructure\Persistence\Eloquent\Models;

use Database\Factories\BookModelFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BookModel extends Model
{
    use HasFactory;
    
    protected $table = 'books';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'titulo',
        'autor',
        'isbn',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    protected static function newFactory(): Factory
    {
        return BookModelFactory::new();
    }

    public function loans(): HasMany
    {
        return $this->hasMany(LoanModel::class, 'book_id');
    }
}
