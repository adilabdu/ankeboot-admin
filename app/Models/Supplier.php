<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

}
