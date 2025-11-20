<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // show the login form
    public function showLogin()
    {
        return view('account');
    }

    // handle login
    public function login(Request $request)
    {
        // get email and password from request
        $credentials = $request->only(['email', 'password']);

        // try to login
        if (auth()->attempt($credentials)) {
            return redirect()->route('home')->with('success', 'You are now logged in!');
        }

        // login failed
        return redirect()->route('account')->with('error', 'Invalid email or password.');
    }

    // handle logout
    public function logout()
    {
        auth()->logout();
        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
