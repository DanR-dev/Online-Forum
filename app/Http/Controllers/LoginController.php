<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles client requests related to user login
 */
class LoginController extends Controller
{

    /**
     * @param request http request sent by client
     * @return account options view
     */
    public function getAccountOptionsView(Request $request){
        return view('accountOptions', ['loggedIn' => Auth::check()]);
    }

    /**
     * attempts to authenticate the user's login request
     * @param request http request sent by client
     * @return redirected intended view if success, previous view with errors if failure
     */
    public function processLoginRequest(Request $request){
        try{
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        } else{
            return back()->withErrors([
                'credentials' => 'The provided credentials do not match our records.',
            ]);
        }
        }catch(\Exception $e){
            return back()->withErrors([
                'error' => 'unknown error',
            ]);
        }
    }

    /**
     * attempts to log the user out
     * @param request http request sent by client
     * @return reload previous view
     */
    public function processLogoutRequest(Request $request)
    {
        Auth::logout();
        $request->session()->regenerate();
        return back();
    }
}
