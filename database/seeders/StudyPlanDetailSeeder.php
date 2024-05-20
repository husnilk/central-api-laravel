<?php

namespace Database\Seeders;

use App\Models\StudyPlanDetail;
use Illuminate\Database\Seeder;

class StudyPlanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudyPlanDetail::factory()->count(5)->create();
    }
}
