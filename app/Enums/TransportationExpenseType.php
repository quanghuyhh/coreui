<?php

namespace App\Enums;

enum TransportationExpenseType: int
{
    case NONE = 0;
    case FIXED = 1;
    case OTHER = 2;
}
