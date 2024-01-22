<?php

namespace App\Enums;

enum WorkTimeStatus: int
{
    case IN_PROGRESS = 1;
    case NEED_FIX = 2;
    case APPROVED = 3;
}
