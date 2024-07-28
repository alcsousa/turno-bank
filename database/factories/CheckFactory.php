<?php

namespace Database\Factories;

use App\Models\CheckStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Check>
 */
class CheckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(),
            'check_status_id' => CheckStatus::PENDING,
            'amount' => $this->faker->numberBetween(1000, 100000),
            'description' => $this->faker->word,
            'image_path' => '/check-placeholder.png'
        ];
    }

    public function pending(): self
    {
        return $this->state(function () {
            return ['check_status_id' => CheckStatus::PENDING];
        });
    }

    public function accepted(): self
    {
        return $this->state(function () {
            return ['check_status_id' => CheckStatus::ACCEPTED];
        });
    }

    public function rejected(): self
    {
        return $this->state(function () {
            return ['check_status_id' => CheckStatus::REJECTED];
        });
    }
}
