<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Lecturer;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//    Lecturer::factory()->count(5)->create();
        $faker = Faker::create("id_ID");
        $department_id = Department::where('abbreviation', 'DSI')->first()->id;
        $lectures = [
            [
                "id" => 1,
                "nik" => $faker->nik(),
                "name" => "Prof. Ir. Surya Afnarius, PhD",
                "nip" => "196404091995121001",
                "gender" => "M",
                "department_id" => $department_id,
            ],
            [
                "id" => 2,
                "nik" => $faker->nik(),
                "name" => "Husnil Kamil, M.T",
                "nip" => "198201182008121002",
                "gender" => "M",
                "department_id" => $department_id,
            ],
            [
                "id" => 3,
                "nik" => $faker->nik(),
                "name" => "Hasdi Putra, M.T",
                "nip" => "198307272008121003",
                "gender" => "M",
                "department_id" => $department_id,
            ],
            [
                "id" => 4,
                "nik" => $faker->nik(),
                "name" => "Ricky Akbar, M. Kom",
                "nip" => "198410062012121001",
                "gender" => "M",
                "department_id" => $department_id,
            ],
            [
                "id" => 5,
                "nik" => $faker->nik(),
                "name" => "Fajril Akbar, M.Sc",
                "nip" => "198001102008121002",
                "gender" => "M",
                "department_id" => $department_id,
            ],
            [
                "id" => 6,
                "nik" => $faker->nik(),
                "name" => "Haris Suryamen, M.Sc",
                "nip" => "197503232012121001",
                "gender" => "M",
                "department_id" => $department_id,
            ],
            [
                "id" => 7,
                "nik" => $faker->nik(),
                "name" => "Jefril Rahmadoni, M.Kom",
                "nip" => "198904152019031009",
                "gender" => "M",
                "department_id" => $department_id,
            ],
            [
                "id" => 8,
                "nik" => $faker->nik(),
                "name" => "Adi Arga Afrinur, M.Kom",
                "nip" => "199208202019031005",
                "gender" => "M",
                "department_id" => $department_id,
            ],
            [
                "id" => 9,
                "nik" => $faker->nik(),
                "name" => "Afriyanti Dwi Kartika, M.T",
                "nip" => "198904212019032024",
                "gender" => "F",
                "department_id" => $department_id,
            ],
            [
                "id" => 10,
                "nik" => $faker->nik(),
                "name" => "Dwi Welly Sukma Nirad, M.T",
                "nip" => "199108122019032018",
                "gender" => "F",
                "department_id" => $department_id,
            ],
            [
                "id" => 11,
                "nik" => $faker->nik(),
                "name" => "Hafizah Hanim, M.Kom",
                "nip" => "199309292019032022",
                "gender" => "F",
                "department_id" => $department_id,
            ],
            [
                "id" => 12,
                "nik" => $faker->nik(),
                "name" => "Ullya Mega Wahyuni, M.Kom",
                "nip" => "199011032019032008",
                "gender" => "F",
                "department_id" => $department_id,
            ],
            [
                "id" => 13,
                "nik" => $faker->nik(),
                "name" => "Rahmatika Pratama Santi, M.T",
                "gender" => "F",
                "department_id" => $department_id,
            ],
            [
                "id" => 14,
                "nik" => $faker->nik(),
                "name" => "Febby Apri Wenando, M.Eng",
                "nip" => "199104172022031007",
                "gender" => "M",
                "department_id" => $department_id,
            ],
            [
                "id" => 15,
                "nik" => $faker->nik(),
                "name" => "Aina Hubby Aziira, M.Eng",
                "nip" => "199504302022032013",
                "gender" => "F",
                "department_id" => $department_id,
            ],
        ];

        foreach ($lectures as $lecture) {
            $user = User::factory()->count(1)->create();
            $lecture["id"] = $user[0]->id;
            Lecturer::create($lecture);
        }

    }
}
