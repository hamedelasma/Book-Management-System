<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name,
        ];
    }

    public function withBooks(int $count = 1): self
    {
        return $this->hasBooks($count);
    }

    public function hasBooks(int $count = 1): self
    {
        return $this->afterCreating(function (\App\Models\Author $author) use ($count) {
            \App\Models\Book::factory($count)->create(['author_id' => $author->id]);
        });
    }
}
