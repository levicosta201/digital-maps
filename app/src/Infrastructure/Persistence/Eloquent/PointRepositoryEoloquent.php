<?php
declare(strict_types=1);

namespace App\src\Infrastructure\Persistence\Eloquent;

use App\Models\PointModel;
use App\src\Application\DTO\PointDto;
use App\src\Domain\Repositories\PointRepositoryInterface;

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
}
