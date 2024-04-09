<?php
declare(strict_types=1);

namespace App\src\Domain\Enum;

enum CacheKeyEnum: string
{
    case POINTS = 'points';
    case POINTS_NEAR = 'points_near';
}
