<?php

namespace Database\Seeders;

use App\Models\ThesisDefenseScore;
use Illuminate\Database\Seeder;

class ThesisDefenseScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ThesisDefenseScore::factory()->count(5)->create();
    }
}
