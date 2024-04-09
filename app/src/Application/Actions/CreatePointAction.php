<?php
declare(strict_types=1);

namespace App\src\Application\Actions;

use App\src\Application\DTO\PointDto;
use App\src\Domain\Services\PointsService;

class CreatePointAction
{
    public function __construct(
        protected PointsService $pointsService
    ){ }

    public function execute(PointDto $pointDto): PointDto
    {
        return $this->pointsService->create($pointDto);
    }
}
