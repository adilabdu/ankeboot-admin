<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Credit extends Model
{
    use HasFactory;

    protected $guarded = [
        'settled_on',
        'deposit_id',
        'created_at',
        'updated_at'
    ];

    public function deposit(): BelongsTo
    {
        return $this->BelongsTo(Deposit::class);
    }

    protected $casts = [
        'settled_on' => 'date'
    ];

}
