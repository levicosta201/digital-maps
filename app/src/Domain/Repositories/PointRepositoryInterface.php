<?php
namespace App\src\Domain\Repositories;

use App\src\Application\DTO\PointDto;
use Illuminate\Database\Eloquent\Collection;

interface PointRepositoryInterface
{
    public function create(PointDto $point): PointDto;

    public function all(): array;

    public function update(PointDto $pointDto): int;

    public function delete(string $uuid): int;

    public function getNear(float $latitude, float $longitude, int $distance, string $hour): array;
}
