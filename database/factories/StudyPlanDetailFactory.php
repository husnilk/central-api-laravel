<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ClassCourse;
use App\Models\StudyPlan;
use App\Models\StudyPlanDetail;

class StudyPlanDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudyPlanDetail::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'study_plan_id' => StudyPlan::factory(),
            'class_id' => ClassCourse::factory(),
            'status' => $this->faker->numberBetween(-10000, 10000),
            'in_transcript' => $this->faker->numberBetween(-10000, 10000),
            'weight' => $this->faker->randomFloat(0, 0, 9999999999.),
            'grade' => $this->faker->randomFloat(0, 0, 9999999999.),
            'class_course_id' => ClassCourse::factory(),
        ];
    }
}
