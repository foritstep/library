<?php

namespace Database\Factories;

use App\Models\Author;
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
            'author_id' => $this->faker->randomElement(Author::pluck('id')->all()),
            'title' => $this->faker->sentence(5),
            'annotation' => $this->faker->optional(0.9, null)->paragraph(),
            'publication_date' => $this->faker->date(),
        ];
    }
}
