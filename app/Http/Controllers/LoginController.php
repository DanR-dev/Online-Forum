<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{

    public function getLoginForm(Request $request){
        return view('loginForm', ['loggedIn' => Auth::check()]);
    }

    public function processLogin(Request $request)
    {
        if($request->mode == 'login')
        {
            $credentials = $request->only('email', 'password');
    
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
    
                return redirect()->intended('/');
            }
    
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        } else{
            Auth::logout();
            return back();
        }
    }
}
