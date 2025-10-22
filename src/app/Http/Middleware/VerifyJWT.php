<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class VerifyJWT
{
    public function handle(Request $request, Closure $next)
    {
        $token = Session::get('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        try {
            // Authenticate user via JWT
            $user = JWTAuth::setToken($token)->authenticate();

            // Share user in request and views
            $request->merge(['jwt_user' => $user]);
            view()->share('user', $user);

        } catch (\Exception $e) {
            Session::forget('token');
            return redirect()->route('login')->with('error', 'Session expired, please login again.');
        }

        return $next($request);
    }
}
