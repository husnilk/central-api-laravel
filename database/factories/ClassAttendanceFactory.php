<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ClassAttendance;
use App\Models\ClassMeeting;
use App\Models\StudyPlanDetail;

class ClassAttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClassAttendance::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'study_plan_detail_id' => StudyPlanDetail::factory(),
            'class_meeting_id' => ClassMeeting::factory(),
            'meet_no' => $this->faker->numberBetween(-10000, 10000),
            'device_id' => $this->faker->word(),
            'device_name' => $this->faker->word(),
            'lattitude' => $this->faker->randomFloat(0, 0, 9999999999.),
            'longitude' => $this->faker->longitude(),
            'attendance_status' => $this->faker->numberBetween(-10000, 10000),
            'need_attention' => $this->faker->numberBetween(-10000, 10000),
            'information' => $this->faker->text(),
        ];
    }
}
