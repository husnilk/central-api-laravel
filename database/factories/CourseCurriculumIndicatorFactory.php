<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\CourseCurriculumIndicator;
use App\Models\CurriculumIndicator;

class CourseCurriculumIndicatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CourseCurriculumIndicator::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'curriculum_indicator_id' => CurriculumIndicator::factory(),
        ];
    }
}
