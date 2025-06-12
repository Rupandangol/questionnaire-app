<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        $slug = Str::slug($title, '-');
        return [
            'title' => $title,
            'content' => $this->faker->paragraph(),
            'slug'=>$slug,
            'user_id' => $this->faker->randomElement(User::pluck('id'))
        ];
    }
}
