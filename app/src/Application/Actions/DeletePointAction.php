<?php
declare(strict_types=1);

namespace App\src\Application\Actions;

use App\src\Application\DTO\PointDto;
use App\src\Domain\Services\PointsService;

class DeletePointAction
{
    public function __construct(
        protected PointsService $pointsService
    ) { }

    /**
     * @throws \Exception
     */
    public function execute(string $pointDto): bool
    {
        return $this->pointsService->delete($pointDto);
    }
}
