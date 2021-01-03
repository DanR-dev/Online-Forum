<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Comment;
use App\Events\ItemDeleted;
use App\Events\ItemCommented;
use Illuminate\Support\Facades\Auth;

/**
 * Handles client request based creation, editing and deletion of comments
 */
class CommentController extends Controller
{

    /**
     * checks that the edit request is valid and authorised, if so, edits the specified comment.
     * @param request http request sent by client
     * @return formatted string of new comment data if success, errors if known errors, nothing if unknown error
     */
    public function editComment(Request $request){
        try{
            // strip html tags and re-order input for validation
            $request->request->add(['id' => strip_tags($request->comment_id)]);
            $request->request->remove('comment_id');
            $request->request->add(['content' => strip_tags($request->content)]);            

            // validate the target comment exists and replacement content has been submitted
            $this->validate($request, [
                'id' => 'exists:comments',
                'content' => 'required',
            ]);

            // get the details of the comment being edited and the user trying to edit it
            $profile = Auth::user()->profile;
            $comment = Comment::where('id', $request->request->get('id'))->first();
            $author = $comment->profile;

            // add authorisation data to the request (user must be the author to edit a comment)
            $request->request->add(['authorised' => $profile == $author]);
            
            // validate that the user is authorised to edit the comment
            $this->validate($request, [
                'authorised' => 'accepted',
            ]);

            // update the comment with the new content
            $comment->content = $request->request->get('content');
            $comment->save();

            // return the comment to be displayed by the website
            // (seperate fields with "<a>", guaranteed no mistakes as html tags were stripped from input data)
            return $comment->id."<a>".$comment->content;

        }catch(\Exception $e){
            //return nothing (caught as an error on client side)
            return "";
        }
    }

    /**
     * checks that the delete request is authorised, if so, deletes the specified comment.
     * @param request http request sent by client
     * @return "True" if success, errors if known errors, nothing if unknown error
     */
    public function deleteComment(Request $request){
        try{
            // strip html tags and re-order input for validation
            $request->request->add(['id' => strip_tags($request->comment_id)]);
            $request->request->remove('comment_id');

            // validate the target comment exists
            $this->validate($request, [
                'id' => 'exists:comments',
            ]);

            // get the details of the comment being deleted and the user trying to delete it
            $profile = Auth::user()->profile;
            $comment = Comment::where('id', $request->request->get('id'))->first();
            $author = $comment->profile;

            
            // add authorisation data to the request (user must be the author or an admin to delete a comment)
            $request->request->add(['authorised' => ($profile->auth == 'admin' || $profile == $author)]);
            
            // validate that the user is authorised to edit the comment
            $this->validate($request, [
                'authorised' => 'accepted',
            ]);
            
            // check if deletion was authorised by a user who isnt the author
            if($comment->profile->id != $profile->id){
                // if so, notify author of deletion via ItemDeleted event
                ItemDeleted::dispatch($comment->profile);
            }
            
            //delete the comment
            $comment->delete();

            // return "True" to indicate success
            return "True";

        }catch(\Exception $e){
            // return nothing to indicate unknown error
            return "";
        }
    }

    /**
     * checks that the create request is valid and authorised, if so, creates the requested comment
     * @param request http request sent by client
     * @return formatted string of new comment data if success, errors if known errors, nothing if unknown error
     */
    public function createComment(Request $request){
        try{
            // strip html tags and add authorisation data to request
            $request->request->add(['content' => strip_tags($request->content)]);
            $request->request->add(['authorised' => Auth::check()]);

            // parse wether the comment is being made on a post or a comment
            if($request->request->get('post_id') != null){
                // add comment target type to request data
                $request->request->add(['commentable_type' => Post::class]);
                // strip html tags and re-order input for validation
                $request->request->add(['id' => strip_tags($request->post_id)]);
                $request->request->remove('post_id');
                // check the post being commented on exists
                $this->validate($request, [
                    'id' => 'exists:posts',
                ]);
            } else if($request->request->get('comment_id') != null){
                // add comment target type to request data
                $request->request->add(['commentable_type' => Comment::class]);
                // strip html tags and re-order input for validation
                $request->request->add(['id' => strip_tags($request->comment_id)]);
                $request->request->remove('comment_id');
                // check the comment being commented on exists
                $this->validate($request, [
                    'id' => 'exists:comments',
                ]);
            } else{
                // return nothing for unknown error
                return "";
            }

            // validate the new comment is not empty and the user is authorised (logged in)
            $this->validate($request, [
                'content' => 'required',
                'authorised' => 'accepted',
            ]);

            // create the new comment and save it to the DB
            $comment = new Comment;
            $comment->content = $request->request->get('content');
            $comment->commentable_id = $request->request->get('id');
            $comment->commentable_type = $request->request->get('commentable_type');
            $comment->profile_id = Auth::user()->profile->id;
            $comment->save();

            
            // check if the comment is on an item written by a different user
            if($comment->profile != $comment->commentable->profile){
                // if so, notify the author of the commented item via ItemCommented event
                ItemCommented::dispatch($comment->commentable->profile);
            }

            // return the comment to be displayed by the website
            // (seperate fields with "<a>", guaranteed no mistakes as html tags were stripped from input data)
            return $comment->id."<a>". $comment->content;
        }catch(\Exception $e){
            // return nothing to indicate unknown error
            return "";
        }
    }
}
