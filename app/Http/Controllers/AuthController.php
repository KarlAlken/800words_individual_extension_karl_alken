<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // I show the login form
    public function showLogin()
    {
        return view('account');
    }

    // I handle the login form submission
    public function login(Request $request)
    {
        // I get email and password from the request
        $credentials = $request->only(['email', 'password']);

        // I try to authenticate the user
        if (auth()->attempt($credentials)) {
            // I redirect to home with success message
            return redirect()->route('home')->with('success', 'You are now logged in!');
        }

        // I redirect back with error if login fails
        return redirect()->route('account')->with('error', 'Invalid email or password.');
    }

    // I handle logout
    public function logout()
    {
        auth()->logout();
        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
