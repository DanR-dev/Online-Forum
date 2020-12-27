<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{

    public function getLoginForm(Request $request){
        return view('accountOptions', ['loggedIn' => Auth::check()]);
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
    
            return redirect()->intended('/');
        }
    
        return back()->withErrors([
            'credentials' => 'The provided credentials do not match our records.',
        ]);
    }

    public function processLogout(Request $request)
    {
        Auth::logout();
        return back();
    }
}
