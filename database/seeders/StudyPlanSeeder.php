<?php

namespace Database\Seeders;

use App\Models\StudyPlan;
use Illuminate\Database\Seeder;

class StudyPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudyPlan::factory()->count(5)->create();
    }
}
