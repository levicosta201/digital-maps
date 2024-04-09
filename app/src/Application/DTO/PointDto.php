<?php
declare(strict_types=1);

namespace App\src\Application\DTO;

use Ramsey\Uuid\Uuid;

class PointDto
{
    public function __construct(
        public string $uuid,
        public string $name,
        public int $latitude,
        public int $longitude,
        public ?string $openHour,
        public ?string $closeHour,
        public ?int $isClosed = 0
    ) { }

    public static function fromRequest(array $data): self
    {
        return new self(
            Uuid::uuid4()->toString(),
            $data['name'],
            $data['latitude'],
            $data['longitude'],
            $data['open_hour'] ?? null,
            $data['close_hour'] ?? null,
            $data['isClosed'] ?? 0,
        );
    }

    public static function fromArray(array $data): PointDto
    {
        return new self(
            $data['uuid'],
            $data['name'],
            $data['latitude'],
            $data['longitude'],
            $data['open_hour'] ?? null,
            $data['close_hour'] ?? null,
            $data['isClosed'] ?? 0,
        );
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'open_hour' => $this->openHour ?? null,
            'close_hour' => $this->closeHour ?? null,
            'isClosed' => $this->isClosed ?? 0,
        ];
    }

    public function setIsClosed(int $isClosed): self
    {
        $this->isClosed = $isClosed;
        return $this;
    }
}
