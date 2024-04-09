<?php
declare(strict_types=1);

namespace App\src\Infrastructure\Persistence\Eloquent;

use App\Models\PointModel;
use App\src\Application\DTO\PointDto;
use App\src\Domain\Repositories\PointRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PointRepositoryEoloquent implements PointRepositoryInterface
{

    public function __construct() {

    }

    public function create(PointDto $point): PointDto
    {
        PointModel::create($point->toArray());
        return $point;
    }

    public function all(): array
    {
        return PointModel::all()->toArray();
    }

    public function update(PointDto $pointDto): int
    {
        return PointModel::where('uuid', $pointDto->uuid)->update($pointDto->toArray());
    }

    public function delete(string $uuid): int
    {
        return PointModel::where('uuid', $uuid)->delete();
    }

    public function getNear(float $latitude, float $longitude, int $distance, string $hour): array
    {
        return PointModel::where('latitude', '>=', $latitude - $distance)
            ->where('latitude', '<=', $latitude + $distance)
            ->where('longitude', '>=', $longitude - $distance)
            ->where('longitude', '<=', $longitude + $distance)
            ->get()
            ->toArray();
    }
}
