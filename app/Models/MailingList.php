<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailingList extends Model
{
    use HasFactory;

    protected $table = 'mailing_list';

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

}
