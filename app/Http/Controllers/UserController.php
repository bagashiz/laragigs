<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // create show a form to create a new user
    public function create()
    {
        return view('users.register');
    }

    // store save a new user to the database
    public function store()
    {
        $formFields = request()->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:8|max:255'
        ]);

        // hash password
        $formFields['password'] = bcrypt($formFields['password']);

        $user = User::create($formFields);

        auth()->login($user);

        return redirect('/')->with('success', 'User created and logged in!');
    }

    // login show a form to login a user
    public function login()
    {
        return view('users.login');
    }

    // auth authenticate a user
    public function auth()
    {
        $formFields = request()->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        // attempt to log the user in
        if (auth()->attempt($formFields)) {
            // generate a new session id
            request()->session()->regenerate();

            return redirect('/')->with('success', 'You are logged in!');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password'
        ])->onlyInput('email');
    }

    // logout log the user out
    public function logout()
    {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out!');
    }
}
