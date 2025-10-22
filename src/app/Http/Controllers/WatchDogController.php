<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WatchDog;
use App\Models\Currency;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Jobs\SendWatchdogAlert;

class WatchDogController extends Controller
{

    public function __construct()
    {
        // Only apply API guard if you are building API routes
        // For web, rely on VerifyJWT middleware
    }

   
    protected function getUser()
    {
        $token = Session::get('token');
        if (!$token) {
            abort(403, 'Unauthorized');
        }
        return JWTAuth::setToken($token)->authenticate();
    }

    public function index()
    {
        $user = $this->getUser();
        $watchdogs = WatchDog::where('user_id', $user->id)
            ->with('currency')
            ->latest()
            ->paginate(9);

        return view('watchdogs.index', compact('user', 'watchdogs'));
    }

    public function create(Request $request)
{
    $user = $this->getUser();
    $currencies = Currency::all();
    $selectedCurrency = $request->query('currency_id');

    return view('watchdogs.create', [
        'user' => $user,
        'currencies' => $currencies,
        'selectedCurrency' => $selectedCurrency
    ]);
}

    public function store(Request $request)
{
    $user = $this->getUser();

    $request->validate([
        'currency_id' => 'required|exists:currencies,id',
        'target_price' => 'required|numeric|min:0',
    ]);

    Watchdog::create([
        'user_id' => $user->id,
        'currency_id' => $request->currency_id,
        'target_price' => $request->target_price,
    ]);

    return redirect()->route('dashboard')->with('success', 'Watchdog created!');
}

    
public function edit(Watchdog $watchdog)
{
    $user = $this->getUser();

    if ($watchdog->user_id !== $user->id) {
        abort(403);
    }

    return view('watchdogs.edit', compact('watchdog'));
}

public function update(Request $request, Watchdog $watchdog)
{
    $user = $this->getUser();

    if ($watchdog->user_id !== $user->id) {
        abort(403);
    }

    $request->validate([
        'target_price' => 'required|numeric|min:0',
    ]);

    $watchdog->update(['target_price' => $request->target_price]);

    // Redirect to dashboard (or index) with success message
    return redirect()->route('dashboard')->with('success', 'WatchDog updated successfully!');
}

public function destroy(Watchdog $watchdog)
{
    $user = $this->getUser();

    if ($watchdog->user_id !== $user->id) {
        abort(403);
    }

    $watchdog->delete();

    // Redirect to dashboard (or index) with success message
    return redirect()->route('dashboard')->with('success', 'WatchDog deleted successfully!');
}

public function checkAndQueueWatchdogs()
{
    // Fetch only currencies that have active watchdogs
    $watchdogs = WatchDog::with('currency')
        ->whereNull('notified_at') // only unnotified
        ->get();

    foreach ($watchdogs as $wd) {
        $currency = $wd->currency;

        if (!$currency || !$currency->coingecko_id) continue;

        $currentPrice = $this->fetchCurrentPrice($currency->coingecko_id);
        if ($currentPrice === null) continue;

        // Update the currency price if it changed
        if ($currentPrice != $currency->price) {
            $currency->update(['price' => $currentPrice]);
        }

        // Dispatch the alert
        \App\Jobs\SendWatchdogAlert::dispatch($wd, $currentPrice);

        // Optionally mark as notified
        $wd->update(['notified_at' => now()]);
    }

    return response()->json(['status' => 'watchdogs checked']);
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
        \Log::error("Failed to fetch price for $symbol: ".$e->getMessage());
    }

    return null;
}


}