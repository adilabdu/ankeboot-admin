<?php

namespace App\Enums;

enum TransactionType : string
{
    case PURCHASE = 'purchase';
    case SALE = 'sale';
}
