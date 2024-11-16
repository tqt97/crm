<?php

namespace App\Enums;

enum ReasonType: int
{
    case RETURNED = 0;
    
    case CHANGE = 1;

    case REPAIR = 2;

    case OTHER = 3;
}
