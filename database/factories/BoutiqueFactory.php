<?php

namespace Database\Factories;

use App\Models\Boutique;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Boutique>
 */
class BoutiqueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nom' => fake()->company().' Boutique',
            'slug' => Str::slug(fake()->unique()->company()),
        ];
    }
}
