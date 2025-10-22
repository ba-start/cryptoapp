<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WatchDog;
use App\Models\Currency;
use App\Jobs\SendWatchdogAlert;
use Illuminate\Support\Facades\Http;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class WatchDogController extends Controller
{
    protected function getUser()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    // List user's watchdogs
    public function index()
    {
        $user = $this->getUser();
        $watchdogs = WatchDog::where('user_id', $user->id)->with('currency')->get();

        return response()->json($watchdogs);
    }

    // Show single watchdog
    public function show(WatchDog $watchdog)
    {
        $user = $this->getUser();
        abort_if($watchdog->user_id !== $user->id, 403);

        return response()->json($watchdog->load('currency'));
    }

    // Create a new watchdog
    public function store(Request $request)
    {
        $user = $this->getUser();

        $request->validate([
            'currency_id' => 'required|exists:currencies,id',
            'target_price' => 'required|numeric|min:0',
        ]);

        $watchdog = WatchDog::create([
            'user_id' => $user->id,
            'currency_id' => $request->currency_id,
            'target_price' => $request->target_price,
        ]);

        return response()->json($watchdog, 201);
    }

    // Update a watchdog
    public function update(Request $request, WatchDog $watchdog)
    {
        $user = $this->getUser();
        abort_if($watchdog->user_id !== $user->id, 403);

        $request->validate([
            'target_price' => 'required|numeric|min:0',
        ]);

        $watchdog->update(['target_price' => $request->target_price]);

        return response()->json($watchdog);
    }

    // Delete a watchdog
    public function destroy(WatchDog $watchdog)
    {
        $user = $this->getUser();
        abort_if($watchdog->user_id !== $user->id, 403);

        $watchdog->delete();

        return response()->json(null, 204);
    }

    // Check and queue all unnotified watchdogs
    public function checkAndQueueWatchdogs()
    {
        $watchdogs = WatchDog::with('currency')->whereNull('notified_at')->get();

        foreach ($watchdogs as $wd) {
            $currency = $wd->currency;
            if (!$currency || !$currency->coingecko_id) continue;

            $currentPrice = $this->fetchCurrentPrice($currency->coingecko_id);
            if ($currentPrice === null) continue;

            if ($currentPrice != $currency->price) {
                $currency->update(['price' => $currentPrice]);
            }

            SendWatchdogAlert::dispatch($wd, $currentPrice);
            $wd->update(['notified_at' => now()]);
        }

        return response()->json(['status' => 'watchdogs checked']);
    }

    // Fetch current price from CoinGecko
    protected function fetchCurrentPrice($symbol)
    {
        try {
            $res = Http::get("https://api.coingecko.com/api/v3/simple/price", [
                'ids' => $symbol,
                'vs_currencies' => 'usd',
            ]);

            return $res->ok() && isset($res->json()[$symbol]['usd'])
                ? $res->json()[$symbol]['usd']
                : null;

        } catch (\Exception $e) {
            \Log::error("Failed to fetch price for $symbol: " . $e->getMessage());
        }

        return null;
    }
}
