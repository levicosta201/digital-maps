<?php
declare(strict_types=1);

namespace App\src\Domain\Services;

use App\src\Application\DTO\PointDto;
use App\src\Domain\Cache\CacheInterface;
use App\src\Domain\Enum\CacheKeyEnum;
use App\src\Domain\Repositories\PointRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PointsService
{
    public function __construct(
       protected PointRepositoryInterface $pointRepository,
        protected CacheInterface $cache
    ){ }

    public function create(PointDto $pointDto): PointDto
    {
        try {
            DB::beginTransaction();
            $point = $this->pointRepository->create($pointDto);
            DB::commit();
            $this->cache->delete(CacheKeyEnum::POINTS->value);
            return $point;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function getPoints(): array
    {
        try {
            $points = $this->cache->get(CacheKeyEnum::POINTS->value);
            if ($points) {
                return $points;
            }
            $points = $this->pointRepository->all();
            $this->cache->set(CacheKeyEnum::POINTS->value, $points, 5);
            return $points;
        } catch (\Exception $exception) {
            return $this->cache->get(CacheKeyEnum::POINTS->value);
        }
    }
}
