<?php

namespace Database\Factories;

use App\Enums\TodoStatus;
use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TodoFactory extends Factory
{
    protected $model = Todo::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'status' => $this->faker->randomElement(TodoStatus::cases()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function cancelled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TodoStatus::CANCELLED,
            ];
        });
    }

    public function inprogress(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TodoStatus::INPROGRESS,
            ];
        });
    }

    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TodoStatus::COMPLETED,
            ];
        });
    }

    public function hold(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TodoStatus::HOLD,
            ];
        });
    }
}
