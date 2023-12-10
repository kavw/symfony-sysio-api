<?php

declare(strict_types=1);

namespace App\Entity;

enum CouponType: int
{
    case FIXED = 1;
    case PERCENT = 2;
}
