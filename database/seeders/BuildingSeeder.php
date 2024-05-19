<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Building::factory()->count(5)->create();

        for($asciiCode = 65; $asciiCode <= 74; $asciiCode++){
            Building::create([
                "name" => chr($asciiCode),
                "floors" => 2,
            ]);
        }
    }
}
