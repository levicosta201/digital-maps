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
            $pointDto = PointDto::fromArray($point->toArray());
            $this->cache->delete(CacheKeyEnum::POINTS->value);
            $this->cache->delete(CacheKeyEnum::POINTS_NEAR->value);
            return $pointDto;
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

    /**
     * @throws \Exception
     */
    public function update(PointDto $pointDto): PointDto
    {
        try {
            DB::beginTransaction();
            $point = $this->pointRepository->update($pointDto);
            DB::commit();

            if ($point === 0) {
                throw new \Exception('Point not found', 404);
            }
            $this->cache->delete(CacheKeyEnum::POINTS->value);
            $this->cache->delete(CacheKeyEnum::POINTS_NEAR->value);
            return $pointDto;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @throws \Exception
     */
    public function delete(string $uuid): bool
    {
        try {
            DB::beginTransaction();
            $this->pointRepository->delete($uuid);
            DB::commit();
            $this->cache->delete(CacheKeyEnum::POINTS->value);
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function getNear(float $latitude, float $longitude, int $distance, string $hour): array
    {
        try {
            $pointsNear = $this->cache->get(CacheKeyEnum::POINTS_NEAR->value);
            if ($pointsNear) {
                return $pointsNear;
            }
            $pointsNear = $this->pointRepository->getNear($latitude, $longitude, $distance, $hour);
            $this->cache->set(CacheKeyEnum::POINTS_NEAR->value, $pointsNear, 5);

            return $pointsNear;
        } catch (\Exception $exception) {
            return $this->cache->get(CacheKeyEnum::POINTS_NEAR->value);
        }
    }
}
