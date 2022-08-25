<?php

namespace App\Enums;

enum DepositType : string
{
    case CHEQUE = 'cheque';
    case WITHHOLDING = 'withholding';
    case CASH = 'cash';
    case CARD = 'card';
    case TRANSFER = 'transfer';
    case CREDIT = 'credit';
}
