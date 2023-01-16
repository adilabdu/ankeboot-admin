<?php

namespace App\Models;

use App\Enums\ConsignmentAction;
use App\Enums\PurchaseType;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class Book extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $guarded = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function logs(): MorphMany
    {
        return $this->morphMany(Log::class, 'loggable');
    }

    public function settlements(): HasMany
    {
        return $this->hasMany(ConsignmentSettlement::class);
    }

    public function settled(): int
    {

        if (PurchaseType::from($this['type']) === PurchaseType::CONSIGNMENT) {

            return $this['settlements']->pluck('quantity')->sum();

        }

        return -1;
    }

    public function max_payable(): int|null
    {
        if (PurchaseType::from($this['type']) === PurchaseType::CONSIGNMENT) {

            return $this['balance'] + $this->payable();

        }

        return null;
    }

    public function max_returnable(): int|null
    {

        if (PurchaseType::from($this['type']) === PurchaseType::CONSIGNMENT) {

            return $this['balance'] + min($this->payable(), 0);

        }

        return null;
    }

    public function payable(): int|null
    {

        if (PurchaseType::from($this['type']) === PurchaseType::CONSIGNMENT) {

            return $this['transactions']->where(
                'type', TransactionType::SALE
            )->sum(function ($transaction) {
                return $transaction->quantity;
            }) - $this->settled();

        }

        return null;
    }

}
