<?php

namespace Biblioteca\Infrastructure\Persistence\Eloquent\Models;

use Database\Factories\UserModelFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class UserModel extends Model
{
    use HasFactory;
    
    protected $table = 'users';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'nombre',
        'apellidos',
        'dni',
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
        return UserModelFactory::new();
    }

    public function loans(): HasMany
    {
        return $this->hasMany(LoanModel::class, 'user_id');
    }
}
