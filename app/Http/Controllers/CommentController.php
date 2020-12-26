<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function editComment(Request $request){
        try{
            $request->request->add(['id' => strip_tags($request->comment_id)]);
            $request->request->remove('comment_id');
            $request->request->add(['content' => strip_tags($request->content)]);            

            $this->validate($request, [
                'id' => 'exists:comments',
                'content' => 'required',
            ]);

            $profile = Auth::user()->profile;
            $comment = Comment::where('id', $request->request->get('id'))->first();
            $author = $comment->profile;
            $request->request->add(['authorised' => ($profile->auth == 'admin' || $profile == $author)]);
            
            
            $this->validate($request, [
                'authorised' => 'accepted',
            ]);

            $comment->content = $request->request->get('content');
            $comment->save();
            return $comment->id.">".$comment->content;

        }catch(\Exception $e){
            dd($e);
        }
    }

    public function deleteComment(Request $request){
        try{
            $request->request->add(['id' => strip_tags($request->comment_id)]);
            $request->request->remove('comment_id');

            $this->validate($request, [
                'id' => 'exists:comments',
            ]);

            $profile = Auth::user()->profile;
            $comment = Comment::where('id', $request->request->get('id'))->first();
            $author = $comment->profile;
            $request->request->add(['authorised' => ($profile->auth == 'admin' || $profile == $author)]);
            
            $this->validate($request, [
                'authorised' => 'accepted',
            ]);

            $comment->delete();
            return "True";

        }catch(\Exception $e){
            
        }
    }

    public function createComment(Request $request){
        $request->request->add(['content' => strip_tags($request->content)]);
        $request->request->add(['authorised' => Auth::check()]);

        if($request->request->get('post_id') != null){
            $request->request->add(['commentable_id' => strip_tags($request->post_id)]);
            $request->request->add(['commentable_type' => Post::class]);
        } else if($request->request->get('comment_id') != null){
            $request->request->add(['commentable_id' => strip_tags($request->comment_id)]);
            $request->request->add(['commentable_type' => Comment::class]);
        }

        $this->validate($request, [
            'content' => 'required',
            'authorised' => 'accepted',
        ]);

        $comment = new Comment;
        $comment->content = $request->request->get('content');
        $comment->commentable_id = $request->request->get('commentable_id');
        $comment->commentable_type = $request->request->get('commentable_type');
        $comment->profile_id = Auth::user()->profile->id;
        $comment->save();
        return $comment->id.">". $comment->content;
    }
}
