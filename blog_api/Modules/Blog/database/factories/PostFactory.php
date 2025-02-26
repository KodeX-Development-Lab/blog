<?php

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\Post;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'content' => fake()->realText(),
        ];
    }
}

