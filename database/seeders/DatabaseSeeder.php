<?php

namespace Database\Seeders;

use App\Models\PointModel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        PointModel::factory(50)->create();
    }
}
