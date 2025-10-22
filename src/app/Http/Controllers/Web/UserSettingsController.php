<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserSettingsController extends Controller
{
    public function __construct()
    {
        // For web, rely on VerifyJWT middleware
    }

    /**
     * Get the authenticated user via session-stored JWT token
     */
    protected function getUser()
    {
        $token = session('token');
        if (!$token) {
            abort(403, 'Unauthorized');
        }

        return JWTAuth::setToken($token)->authenticate();
    }

    /**
     * Show user settings page
     */
    public function edit()
    {
        $user = $this->getUser();
        return view('settings.edit', compact('user'));
    }

    /**
     * Update user settings
     */
    public function update(Request $request)
    {
        $user = $this->getUser();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('settings.edit')->with('success', 'Settings updated successfully!');
    }
}
