<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Department::factory()->count(5)->create();
        $faker = Faker::create("id_ID");
        $faculty_id = Faculty::get()->first()->id;
        $departments = [
            [
                "name" => "Sistem Informasi",
                "faculty_id" => $faculty_id,
                "abbreviation" => "DSI",
                'national_code' => $faker->word(),
            ],
            [
                "name" => "Teknik Komputer",
                "faculty_id" => $faculty_id,
                "abbreviation" => "DTK",
                'national_code' => $faker->word(),
            ],
            [
                "name" => "Teknik Informatika",
                "faculty_id" => $faculty_id,
                "abbreviation" => "DIF",
                'national_code' => $faker->word(),
            ],
        ];

        foreach($departments as $department){
            Department::create($department);
        }
    }
}
