<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Lecturer;
use App\Models\Period;
use App\Models\Student;
use App\Models\StudyPlan;

class StudyPlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudyPlan::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'periode_id' => Period::factory(),
            'counselor_id' => Lecturer::factory(),
            'status' => $this->faker->numberBetween(-10000, 10000),
            'registered_at' => $this->faker->date(),
            'gpa' => $this->faker->randomFloat(2, 0, 999999.99),
            'period_id' => Period::factory(),
        ];
    }
}
