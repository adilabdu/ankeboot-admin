<?php

namespace App\Enums;

enum ConsignmentAction: string
{
    case RECEIVED = 'received';
    case SOLD = 'sold';
    case SETTLED = 'settled';
}
