<?php

namespace App\Http\Controllers;

use App\Models\WatchDog;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class BaseWatchDogController extends Controller
{
    protected function getUser()
    {
        $token = session('token');
        if (!$token) abort(403, 'Unauthorized');

        return JWTAuth::setToken($token)->authenticate();
    }

    protected function fetchCurrentPrice($symbol)
    {
        try {
            $res = Http::get("https://api.coingecko.com/api/v3/simple/price", [
                'ids' => $symbol,
                'vs_currencies' => 'usd'
            ]);

            if ($res->ok() && isset($res->json()[$symbol]['usd'])) {
                return $res->json()[$symbol]['usd'];
            }
        } catch (\Exception $e) {
            \Log::error("Failed to fetch price for $symbol: " . $e->getMessage());
        }

        return null;
    }
}
