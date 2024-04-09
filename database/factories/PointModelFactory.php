<?php

namespace Database\Factories;

use App\Models\PointModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Nonstandard\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PointModelFactory extends Factory
{
    protected $model = PointModel::class;

    public const FAKE_LOCALES_NAME = [
        'Loja 1 ',
        'Loja 2 ',
        'Loja 3 ',
        'Supermercado 1 ',
        'Supermercado 2 '
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4(),
            'name' => $this->faker->randomElement(self::FAKE_LOCALES_NAME) .
                $this->faker->numberBetween(1, 999),
            'latitude' => $this->faker->numberBetween(10, 150),
            'longitude' => $this->faker->numberBetween(10, 150),
            'open_hour' => $this->faker->time('H:i:s', 'now-1hour'),
            'close_hour' => $this->faker->time('H:i:s', 'now+8hour'),
        ];
    }
}
