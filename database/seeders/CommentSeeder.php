<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\Post;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profile = Profile::find(2);
        $post = Post::find(1);
        $comment = new Comment;
        $comment->content = 'Actually, 2 + 2 = 4';
        $comment->profile_id = $profile->id;
        $comment->commentable_id = $post->id;
        $comment->commentable_type = get_class($post);
        $comment->save();
        
        $profile = Profile::find(1);
        $comment = Comment::find(1);
        $reply = new Comment;
        $reply->content = 'No, Dave said 2 + 2 = 5 and Dave is smart';
        $reply->profile_id = $profile->id;
        $reply->commentable_id = $comment->id;
        $reply->commentable_type = get_class($comment);
        $reply->save();

        Comment::factory(30)->create();
        // these 50 can now be commented on the previous 30
        Comment::factory(50)->create();
    }
}