<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function editComment(Request $request){
        return $request->commentId .">". $request->content;
    }

    public function deleteComment(Request $request){
        return "True";
    }

    public function createComment(Request $request){
        return "99>". $request->content;
    }
}
