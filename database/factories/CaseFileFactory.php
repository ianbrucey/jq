<?php

namespace Database\Factories;

use App\Models\CaseFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CaseFileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CaseFile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'case_number' => fake()->unique()->numerify('CASE-####'),
            'desired_outcome' => fake()->paragraph(),
            'status' => 'open',
            'openai_assistant_id' => null,
            'openai_vector_store_id' => null,
            'openai_project_id' => null,
        ];
    }
}