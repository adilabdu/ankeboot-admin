<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailySale extends Model
{
    use HasFactory;

    protected $guarded = [
        'date_of',
        'created_at',
        'updated_at',
    ];

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function sales_receipts(): HasMany
    {
        return $this->hasMany(SalesReceipt::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(DailySaleExpense::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    protected $casts = [
        'date_of' => 'date',
    ];
}
