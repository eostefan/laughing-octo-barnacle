<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    public function create()
    {
        if (Auth::user()) {
            return redirect()->route('dashboard');
        }

        return inertia('Auth/Register');
    }

    public function store(Request $request)
    {
        $user = User::make(
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|confirmed'
            ])
        );

        $user->save();
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome to your dashboard!');
    }
}
