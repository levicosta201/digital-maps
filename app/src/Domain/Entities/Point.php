<?php
declare(strict_types=1);

namespace App\src\Domain\Entities;

use Carbon\Carbon;

class Point
{
    public function __construct(
        public string $uuid,
        public string $name,
        public int $latitude,
        public int $longitude,
        public Carbon $openHour,
        public Carbon $closeHour
    ) { }
}
