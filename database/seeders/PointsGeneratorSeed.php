<?php

namespace Database\Seeders;

use App\Models\PointModel;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PointsGeneratorSeed extends Seeder
{
    public function __construct(
        protected Factory $faker
    )
    {
        $this->faker::create();
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numberOfPoints = 50;

        for ($i = 0; $i < $numberOfPoints; $i++) {
            PointModel::create([
                'uuid' => Str::uuid(),
                'name' => $this->faker->name,
                'latitude' => $this->faker->latitude,
                'longitude' => $this->faker->longitude,
                'open_hour' => $this->faker->time($format = 'H:i:s', $max = 'now'),
                'close_hour' => $this->faker->time($format = 'H:i:s', $max = 'now'),
            ]);
        }
    }
}
