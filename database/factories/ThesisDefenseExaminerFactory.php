<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Lecturer;
use App\Models\ThesisDefense;
use App\Models\ThesisDefenseExaminer;

class ThesisDefenseExaminerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ThesisDefenseExaminer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'thesis_defense_id' => ThesisDefense::factory(),
            'examiner_id' => Lecturer::factory(),
            'status' => $this->faker->numberBetween(-10000, 10000),
            'position' => $this->faker->numberBetween(-10000, 10000),
            'notes' => $this->faker->text(),
            'lecturer_id' => Lecturer::factory(),
        ];
    }
}
