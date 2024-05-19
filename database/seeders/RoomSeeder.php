<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Room::factory()->count(5)->create();
        $buildings = Building::all();

        foreach($buildings as $building){
            for($idx = 1; $idx <= $building->floors; $idx++){
                for($idy = 1; $idy <= 10; $idy++){
                    Room::create([
                        "building_id" => $building->id,
                        "name" => $building->name.$idx.".".$idy,
                        "floor" => $idx,
                        "number" => $idy,
                    ]);
                }
            }
        }
    }
}
