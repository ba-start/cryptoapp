<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\WatchDog;

class WebAuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        dd('Validation passed', $request->all()); // <-- check if you reach here

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        Session::put('token', $token);

        return redirect()->route('dashboard');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return back()->with('error', 'Invalid credentials.');
        }

        Session::put('token', $token);

        return redirect()->route('dashboard');
    }

    // Show Dashboard
    public function dashboard()
    {
        $token = Session::get('token');

        if (!$token) {
            return redirect()->route('login');
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();
        } catch (\Exception $e) {
            Session::forget('token');
            return redirect()->route('login')->with('error', 'Session expired, please login again.');
        }

        // Load this user's WatchDogs, paginated
        $watchdogs = WatchDog::where('user_id', $user->id)
            ->with('currency')   // eager load currency relation
            ->latest()
            ->paginate(10);

        return view('dashboard', compact('user', 'watchdogs'));
    }

    // Logout
    public function logout()
    {
        Session::forget('token');
        return redirect()->route('login');
    }

    public function create()
    {
        $user = JWTAuth::parseToken()->authenticate(); // ensures session token is valid
        $currencies = Currency::all();
        return view('watchdogs.create', compact('currencies', 'user'));
    }

}
