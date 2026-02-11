<?php

namespace Biblioteca\Infrastructure\Persistence\Eloquent\Models;

use Database\Factories\LoanModelFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LoanModel extends Model
{
    use HasFactory;
    
    protected $table = 'loans';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'user_id',
        'book_id',
        'loan_date',
        'return_date',
    ];

    protected $casts = [
        'loan_date' => 'datetime',
        'return_date' => 'datetime',
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
        return LoanModelFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(BookModel::class, 'book_id');
    }
}
