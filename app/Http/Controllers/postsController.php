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

        try{
            $posts = Post::where('profile_id', Profile::where('name', $searchName)->first()->id)->get();
        }
        catch(\Exception $q){
            $posts = Post::get();
        }

        return view('posts', ['posts' => $posts, 'loggedIn' => Auth::check(), 'user' => Auth::user()]);
    }
}