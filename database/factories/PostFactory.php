<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Profile;
use App\Models\Post;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // randomly pick an author
        $profile = Profile::get()->random();
        return [
            'title' => $this->faker->text(20),
            'content' => $this->faker->text(200),
            'profile_id' => $profile,
        ];
    }
}
