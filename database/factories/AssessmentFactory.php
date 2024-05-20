<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Assessment;
use App\Models\AssessmentDetail;
use App\Models\StudyPlanDetail;

class AssessmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Assessment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'assessment_detail_id' => AssessmentDetail::factory(),
            'study_plan_detail_id' => StudyPlanDetail::factory(),
            'grade' => $this->faker->randomFloat(2, 0, 999999.99),
        ];
    }
}
