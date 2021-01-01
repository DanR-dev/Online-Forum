<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function getAccountOptionsView(Request $request){
        return view('accountOptions', ['loggedIn' => Auth::check()]);
    }

    public function processLoginRequest(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
    
            return redirect()->intended('/');
        } else{
            return back()->withErrors([
                'credentials' => 'The provided credentials do not match our records.',
            ]);
        }
    }

    public function processLogoutRequest(Request $request)
    {
        Auth::logout();
        $request->session()->regenerate();
        return back();
    }
}
