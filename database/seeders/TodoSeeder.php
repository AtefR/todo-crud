<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        Todo::factory()
            ->count(10)
            ->for(User::factory()->create())
            ->create();

        Todo::factory()
            ->count(10)
            ->for(User::factory()->create())
            ->create();

        Todo::factory()
            ->count(10)
            ->for(User::factory()->create())
            ->create();

        Todo::factory()
            ->count(10)
            ->for(User::factory()->create())
            ->create();

        Todo::factory()
            ->count(10)
            ->for(User::factory()->create())
            ->create();
    }
}
