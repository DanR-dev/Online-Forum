<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profile = Profile::find(1);
        $post = new Post;
        $post->title = 'math';
        $post->content = '2 + 2 = 5';
        $post->profile_id = $profile->id;
        $post->save();

        Post::factory(50)->create();
    }
}
