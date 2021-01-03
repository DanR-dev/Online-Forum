<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

/**
 * Handles client request based creation, editing and deletion of posts
 * As well as fetching / searching of posts
 */
class PostController extends Controller{

    /**
     * Attempts to search for a profile by name and get their posts
     * If the profile cannot be found, get all posts
     * @param request http request sent by client
     * @return view displaying the paginated posts
     */
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

    /**
     * checks that the edit request is valid and authorised, if so, edits the specified post.
     * @param request http request sent by client
     * @return formatted string of new post data if success, errors if known errors, nothing if unknown error
     */
    public function editPost(Request $request){
        try{
            // strip html tags and re-order input for validation
            $request->request->add(['id' => strip_tags($request->post_id)]);
            $request->request->remove('post_id');
            $request->request->add(['title' => strip_tags($request->title)]);
            $request->request->add(['content' => strip_tags($request->content)]);            

            // validate the target post exists and replacement data has been submitted
            $this->validate($request, [
                'id' => 'exists:posts',
                'title' => 'required',
                'content' => 'required',
            ]);

            // get the details of the post being edited and the user trying to edit it
            $profile = Auth::user()->profile;
            $post = Post::where('id', $request->request->get('id'))->first();
            $author = $post->profile;

            // add authorisation data to the request (user must be the author to edit a post)
            $request->request->add(['authorised' => ($profile->auth == 'admin' || $profile == $author)]);
            
            // validate that the user is authorised to edit the post
            $this->validate($request, [
                'authorised' => 'accepted',
            ]);

            // update the post with the new data
            $post->title = $request->request->get('title');
            $post->content = $request->request->get('content');
            $post->save();
            
            // return the post to be displayed by the website
            // (seperate fields with "<a>", guaranteed no mistakes as html tags were stripped from input data)
            return $post->id."<a>". $post->title ."<a>". $post->content;

        }catch(\Exception $e){
            //return nothing (caught as an error on client side)
            return "";
        }
    }

    /**
     * checks that the delete request is authorised, if so, deletes the specified post.
     * @param request http request sent by client
     * @return "True" if success, errors if known errors, nothing if unknown error
     */
    public function deletePost(Request $request){
        try{
            // strip html tags and re-order input for validation
            $request->request->add(['id' => strip_tags($request->post_id)]);
            $request->request->remove('post_id');

            // validate the target post exists
            $this->validate($request, [
                'id' => 'exists:posts',
            ]);

            // get the details of the post being deleted and the user trying to delete it
            $profile = Auth::user()->profile;
            $post = Post::where('id', $request->request->get('id'))->first();
            $author = $post->profile;
            
            // add authorisation data to the request (user must be the author or an admin to delete a post)
            $request->request->add(['authorised' => ($profile->auth == 'admin' || $profile == $author)]);
            
            // validate that the user is authorised to edit the post
            $this->validate($request, [
                'authorised' => 'accepted',
            ]);
            
            // check if deletion was authorised by a user who isnt the author
            if($post->profile->id != $profile->id){
                // if so, notify author of deletion via ItemDeleted event
                ItemDeleted::dispatch($post->profile);
            }

            //delete the post
            $post->delete();
            
            // return "True" to indicate success
            return "True";

        }catch(\Exception $e){
            // return nothing to indicate unknown error
            return "";
        }
    }

    /**
     * checks that the create request is valid and authorised, if so, creates the requested post
     * @param request http request sent by client
     * @return formatted string of new post data if success, errors if known errors, nothing if unknown error
     */
    public function createPost(Request $request){

        try{
            // strip html tags and add authorisation data to request
            $request->request->add(['title' => strip_tags($request->title)]);
            $request->request->add(['content' => strip_tags($request->content)]);
            $request->request->add(['authorised' => Auth::check()]);

            // validate the new post is not empty and the user is authorised (logged in)
            $this->validate($request, [
                'title' => 'required',
                'content' => 'required',
                'authorised' => 'accepted',
            ]);

            // create the new post and save it to the DB
            $post = new Post;
            $post->title = $request->request->get('title');
            $post->content = $request->request->get('content');
            $post->profile_id = Auth::user()->profile->id;
            $post->save();

            // return the post to be displayed by the website
            // (seperate fields with "<a>", guaranteed no mistakes as html tags were stripped from input data)
            return $post->id."<a>". $post->title ."<a>". $post->content;
        } catch(\Exception $e){
            // return nothing to indicate unknown error
            return "";
        }
    }
}