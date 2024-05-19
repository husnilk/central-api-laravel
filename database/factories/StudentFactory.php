<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Student;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'id' => User::factory(),
            'nik' => $this->faker->word(),
            'nim' => $this->faker->word(),
            'name' => $this->faker->name(),
            'year' => $this->faker->numberBetween(-10000, 10000),
            'gender' => $this->faker->randomElement(["M","F"]),
            'birthday' => $this->faker->date(),
            'birthplace' => $this->faker->word(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->text(),
            'department_id' => Department::factory(),
            'photo' => $this->faker->word(),
            'marital_status' => $this->faker->numberBetween(1,3),
            'religion' => $this->faker->numberBetween(1, 7),
            'status' => $this->faker->numberBetween(1, 2),
            'counselor_id' => Lecturer::factory(),
        ];
    }
}
