<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Store extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at'
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(
            Book::class,
            'store_books'
        )
            ->withPivot('balance')
            ->withTimestamps();
    }

    public function transactions(): BelongsToMany
    {
        return $this->belongsToMany(
            Transaction::class,
            'store_transactions'
        )
            ->withTimestamps();
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public static function primary(): Store
    {
        return Store::where('primary', true)->first();
    }
}
