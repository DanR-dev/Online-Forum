<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Handles client requests to change profile avatar image
 */
class ProfileController extends Controller
{
    /**
     * checks that the request is valid and authorised, if so, saves the input image as that users avatar.
     * @param request http request sent by client
     * @return previous view
     */
    public function setAvatar(Request $request){
        // add authorisation data to request
        $request->request->add(['authorised' => strip_tags(Auth::check())]);

        // validate that the user is authorised (logged in)
        //and they have uploaded a square image
        $this->validate($request, [
            'authorised' => 'accepted',
            'avatar' => 'image',
            'avatar' => 'dimensions:ratio=1',
        ]);

        // save/overwrite the users avatar with new image
        $request->file('avatar')->storePubliclyAs('avatars', Auth::user()->profile->id.'.png', 'public');
        return back();
    }
}
