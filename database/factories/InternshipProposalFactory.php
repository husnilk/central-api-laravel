<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\InternshipCompany;
use App\Models\InternshipProposal;

class InternshipProposalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InternshipProposal::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'company_id' => InternshipCompany::factory(),
            'title' => $this->faker->sentence(4),
            'start_at' => $this->faker->date(),
            'end_at' => $this->faker->date(),
            'status' => $this->faker->randomElement(["draft","proposed","need_revision","revised","rejected","accepted"]),
            'note' => $this->faker->text(),
            'active' => $this->faker->numberBetween(-10000, 10000),
            'response_letter' => $this->faker->word(),
            'background' => $this->faker->text(),
            'internship_company_id' => InternshipCompany::factory(),
        ];
    }
}
