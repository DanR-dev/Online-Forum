<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function getPosts(Request $request){
        try{
            $search_name = $request->only('searchName');
            $posts = Post::where('profile_id', Profile::where('name', $search_name)->first()->id)->paginate(10);
        }
        catch(\Exception $e){
            $posts = Post::paginate(10);
        }

        return view('posts', ['posts' => $posts, 'loggedIn' => Auth::check(), 'user' => Auth::user()]);
    }

    public function editPost(Request $request){
        try{
            $request->request->add(['id' => strip_tags($request->post_id)]);
            $request->request->remove('post_id');
            $request->request->add(['title' => strip_tags($request->title)]);
            $request->request->add(['content' => strip_tags($request->content)]);            

            $this->validate($request, [
                'id' => 'exists:posts',
                'title' => 'required',
                'content' => 'required',
            ]);

            $profile = Auth::user()->profile;
            $post = Post::where('id', $request->request->get('id'))->first();
            $author = $post->profile;
            $request->request->add(['authorised' => ($profile->auth == 'admin' || $profile == $author)]);
            
            $this->validate($request, [
                'authorised' => 'accepted',
            ]);

            $post->title = $request->request->get('title');
            $post->content = $request->request->get('content');
            $post->save();
            return $post->id.">". $post->title .">". $post->content;

        }catch(\Exception $e){
            return back()->withErrors([
                'credentials' => 'Some of the required data is missing',
            ]);
        }
    }

    public function deletePost(Request $request){
        try{
            $request->request->add(['id' => strip_tags($request->post_id)]);
            $request->request->remove('post_id');

            $this->validate($request, [
                'id' => 'exists:posts',
            ]);

            $profile = Auth::user()->profile;
            $post = Post::where('id', $request->request->get('id'))->first();
            $author = $post->profile;
            $request->request->add(['authorised' => ($profile->auth == 'admin' || $profile == $author)]);
            
            $this->validate($request, [
                'authorised' => 'accepted',
            ]);

            $post->delete();
            return "True";

        }catch(\Exception $e){
            return back()->withErrors([
                'credentials' => 'Some of the required data is missing',
            ]);
        }
    }

    public function createPost(Request $request){

        try{
            $request->request->add(['title' => strip_tags($request->title)]);
            $request->request->add(['content' => strip_tags($request->content)]);
            $request->request->add(['authorised' => Auth::check()]);

            $this->validate($request, [
                'title' => 'required',
                'content' => 'required',
                'authorised' => 'accepted',
            ]);

            $post = new Post;
            $post->title = $request->request->get('title');
            $post->content = $request->request->get('content');
            $post->profile_id = Auth::user()->profile->id;
            $post->save();
            return $post->id.">". $post->title .">". $post->content;
        } catch(\Exception $e){
            return back()->withErrors([
                'credentials' => 'Some of the required data is missing',
            ]);
        }
    }
}