<?php

namespace App\Enums;

enum PurchaseType : string
{
    case CASH = 'cash';
    case CONSIGNMENT = 'consignment';
}
