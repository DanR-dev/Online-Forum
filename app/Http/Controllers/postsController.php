<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class postsController extends Controller
{
    public function getPosts(Request $request){
        $searchName = $request->only('searchName');

        $searchTarget = Profile::where('name', $searchName);
        if($searchTarget->count() > 0){
            $posts = Post::where('profile_id', $searchTarget->first()->id)->get();
        }else{
            $posts = Post::get();
        }

        return view('posts', ['posts' => $posts, 'loggedIn' => Auth::check()]);
    }
}