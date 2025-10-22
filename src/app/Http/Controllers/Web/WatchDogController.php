<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\WatchDog;
use App\Models\Currency;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class WatchDogController extends Controller
{
    /**
     * Get logged-in user from session
     */
    protected function getUser()
    {
        $token = Session::get('token');

        if (!$token) {
            abort(403, 'Not logged in');
        }

        try {
            return JWTAuth::setToken($token)->authenticate();
        } catch (\Exception $e) {
            Session::forget('token');
            abort(403, 'Not logged in');
        }
    }

    /**
     * List WatchDogs
     */
    public function index()
    {
        $user = $this->getUser();
        $watchdogs = WatchDog::where('user_id', $user->id)
            ->with('currency')
            ->latest()
            ->paginate(9);

        return view('watchdogs.index', compact('user', 'watchdogs'));
    }

    /**
     * Show create form
     */
    public function create(Request $request)
    {
        $user = $this->getUser();
        $currencies = Currency::all();
        $selectedCurrency = $request->query('currency_id');

        return view('watchdogs.create', compact('user', 'currencies', 'selectedCurrency'));
    }

    /**
     * Store new WatchDog
     */
    public function store(Request $request)
    {
        $user = $this->getUser();

        $request->validate([
            'currency_id' => 'required|exists:currencies,id',
            'target_price' => 'required|numeric|min:0',
        ]);

        WatchDog::create([
            'user_id' => $user->id,
            'currency_id' => $request->currency_id,
            'target_price' => $request->target_price,
        ]);

        return redirect()->route('dashboard')->with('success', 'WatchDog created!');
    }

    /**
     * Show edit form
     */
    public function edit(WatchDog $watchdog)
    {
        $user = $this->getUser();

        if ($watchdog->user_id !== $user->id) {
            abort(403);
        }

        return view('watchdogs.edit', compact('watchdog'));
    }

    /**
     * Update WatchDog
     */
    public function update(Request $request, WatchDog $watchdog)
    {
        $user = $this->getUser();

        if ($watchdog->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'target_price' => 'required|numeric|min:0',
        ]);

        $watchdog->update(['target_price' => $request->target_price]);

        return redirect()->route('dashboard')->with('success', 'WatchDog updated!');
    }

    /**
     * Delete WatchDog
     */
    public function destroy(WatchDog $watchdog)
    {
        $user = $this->getUser();

        if ($watchdog->user_id !== $user->id) {
            abort(403);
        }

        $watchdog->delete();

        return redirect()->route('dashboard')->with('success', 'WatchDog deleted!');
    }
}
