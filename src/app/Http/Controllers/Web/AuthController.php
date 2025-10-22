<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\WatchDog;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login page
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login POST
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'Invalid credentials');
        }

        // Store JWT in session
        $token = JWTAuth::fromUser($user);
        Session::put('token', $token);

        return redirect()->route('dashboard');
    }

    /**
     * Show registration page
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration POST
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);
        Session::put('token', $token);

        return redirect()->route('dashboard');
    }

    /**
     * Logout
     */
    public function logout()
    {
        Session::forget('token');
        return redirect()->route('login');
    }

    /**
     * Dashboard
     */
public function dashboard()
{
    $user = $this->getUser();

    // Get user's WatchDogs
    $watchdogs = WatchDog::where('user_id', $user->id)
        ->with('currency')
        ->latest()
        ->paginate(9);

    return view('dashboard', compact('user', 'watchdogs'));
}


    /**
     * Helper: get authenticated user from session JWT
     */
    protected function getUser()
    {
        $token = Session::get('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Not logged in')->send();
        }

        try {
            return JWTAuth::setToken($token)->authenticate();
        } catch (\Exception $e) {
            Session::forget('token');
            return redirect()->route('login')->with('error', 'Not logged in')->send();
        }
    }
}
