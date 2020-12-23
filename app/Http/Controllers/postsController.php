<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class postsController extends Controller
{
    public function getPosts(Request $request){
        try{
            $searchName = $request->only('searchName');
            $posts = Post::where('profile_id', Profile::where('name', $searchName)->first()->id)->paginate(10);
        }
        catch(\Exception $q){
            $posts = Post::paginate(10);
        }

        return view('posts', ['posts' => $posts, 'loggedIn' => Auth::check(), 'user' => Auth::user()]);
    }

    public function editPost(Request $request){
        return $request->postId .">". $request->title .">". $request->content;
    }

    public function deletePost(Request $request){
        return $request;
    }
}