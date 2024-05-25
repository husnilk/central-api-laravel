<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Publisher;

class PublisherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Publisher::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->randomElement(["journal","conference","proceeding","book","thesis"]),
            'international' => $this->faker->numberBetween(-10000, 10000),
            'indexed' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
