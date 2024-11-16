<?php

namespace App\Enums;

enum ProductStatus: int
{
    case AVAILABLE = 0;

    case ASSIGNED = 1;

    case REPAIR = 2;

    case NOT_AVAILABLE = 3;
}
