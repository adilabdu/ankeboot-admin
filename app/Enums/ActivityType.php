<?php

namespace App\Enums;

enum ActivityType : string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
