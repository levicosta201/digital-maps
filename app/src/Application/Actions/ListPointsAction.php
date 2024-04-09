<?php
declare(strict_types=1);

namespace App\src\Application\Actions;

use App\src\Domain\Services\PointsService;

class ListPointsAction
{
    public function __construct(
        protected PointsService $pointsService
    ) { }

    public function execute(): array
    {
        return $this->pointsService->getPoints();
    }
}
