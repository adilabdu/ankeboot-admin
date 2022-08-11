<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    use HasFactory;

    protected $guarded = [
        'daily_sale_id',
        'created_at',
        'updated_at'
    ];

    public function daily_sale(): BelongsTo
    {
        return $this->belongsTo(DailySale::class);
    }

    protected $casts = [
        'deposited_on' => 'date'
    ];

}
