<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Profile;
use App\Models\Post;
use App\Models\Comment;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $profiles = Profile::get()->random();
        if(mt_rand(0, 1) == 0){
            $commentable = Comment::get()->random();
        } else{
            $commentable = Post::get()->random();
        }

        return [
            'content' => $this->faker->text(100),
            'profile_id' => $profiles,
            'commentable_id' => $commentable,
            'commentable_type' => get_class($commentable),
        ];
    }
}
