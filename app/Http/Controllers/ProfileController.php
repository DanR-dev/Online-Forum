<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function setAvatar(Request $request){ //profile ID, avatar.png
        $request->request->add(['logged_in' => strip_tags(Auth::check())]);

        $this->validate($request, [
            'logged_in' => 'accepted',
            'avatar' => 'image',
            'avatar' => 'dimensions:ratio=1',
        ]);

        $request->file('avatar')->storePubliclyAs('avatars', Auth::user()->profile->id.'.png', 'public');
        return back();
    }
}
