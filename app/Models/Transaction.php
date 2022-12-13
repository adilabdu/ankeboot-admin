<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(
            Store::class,
            'store_transactions'
        )
            ->withTimestamps();
    }

    public function daily_sale(): BelongsTo
    {
        return $this->belongsTo(DailySale::class);
    }


    public function logs(): MorphMany
    {
        return $this->morphMany(Log::class, 'loggable');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'transaction_on' => 'date',
        'type' => TransactionType::class,
    ];
}
