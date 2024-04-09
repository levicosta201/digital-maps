<?php
declare(strict_types=1);

namespace App\src\Application\Actions;

use App\src\Domain\Services\PointsService;

class ListNearPointsAction
{
    public function __construct(
        protected PointsService $pointsService
    ) { }

    public function execute(int $latitude, int $longitude, int $distance, string $hour): array
    {
        return $this->pointsService->getNear($latitude, $longitude, $distance, $hour);
    }
}
