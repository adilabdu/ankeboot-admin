<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Address extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class);
    }

    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

    public function logs(): MorphMany
    {
        return $this->morphMany(Log::class, 'loggable');
    }
}
