<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ClassCourse;
use App\Models\ClassMeeting;
use App\Models\CoursePlanDetail;
use App\Models\Room;

class ClassMeetingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClassMeeting::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'meet_no' => $this->faker->numberBetween(-10000, 10000),
            'class_id' => ClassCourse::factory(),
            'course_plan_detail_id' => CoursePlanDetail::factory(),
            'material_real' => $this->faker->text(),
            'assessment_real' => $this->faker->text(),
            'method' => $this->faker->numberBetween(-10000, 10000),
            'ol_platform' => $this->faker->word(),
            'ol_links' => $this->faker->word(),
            'room_id' => Room::factory(),
            'meeting_start_at' => $this->faker->dateTime(),
            'meeting_end_at' => $this->faker->dateTime(),
            'class_course_id' => ClassCourse::factory(),
        ];
    }
}
