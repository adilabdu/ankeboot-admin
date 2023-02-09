<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsignmentReturn extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    protected $casts = [
        'transaction_on' => 'date',
    ];
}
