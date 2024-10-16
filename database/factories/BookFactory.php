<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'isbn' => $this->faker->unique()->isbn13,
            'title' => $this->faker->sentence,
            'author' => $this->faker->name,
            'genre' => $this->faker->word,
            'year' => $this->faker->year,
            'publisher' => $this->faker->company,
        ];
    }
}
