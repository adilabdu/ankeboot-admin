<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreTransfer extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    public function from(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'from');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'to');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
