<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Register new user
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

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Login user
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(['token' => $token]);
    }

    // Logout
    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logged out successfully']);
    }

    // Refresh token
    public function refresh()
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());
        return response()->json(['token' => $token]);
    }

    // Current user
    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    // Issue token (optional custom endpoint)
    public function issueToken(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::fromUser($user);
        return response()->json(['token' => $token]);
    }

    // Revoke token (optional)
    public function revokeToken()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Token revoked']);
    }
}
