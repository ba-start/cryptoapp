<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Http\Resources\CurrencyResource;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\ImportCoinsJob;

class CurrencyController extends Controller {
public function index()
{
    $page = request()->query('page', 1);
    $perPage = 100;

    // Cache paginated currencies for 15 minutes
    $cacheKey = "currencies_page_{$page}_per_{$perPage}";
    $currencies = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($page, $perPage) {
        return \App\Models\Currency::orderBy('market_cap', 'desc')
                    ->paginate($perPage, ['*'], 'page', $page);
    });

    // Return JSON if requested (for AJAX or API calls)
    if (request()->query('json')) {
        return response()->json($currencies);
    }

    // Return Blade view; $user is automatically available via AppServiceProvider
    return view('currencies', compact('currencies'));
}

public function show(Currency $coin)
{
    $details = Cache::remember("coin_details_{$coin->coin_id}", now()->addMinutes(30), function () use ($coin) {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.coingecko.com/api/v3/']);

        try {
            $resp = $client->get("coins/{$coin->coin_id}", [
                'query' => [
                    'localization' => 'false',
                    'tickers' => 'false',
                    'market_data' => 'true',
                    'community_data' => 'true',
                    'developer_data' => 'true',
                    'sparkline' => 'false'
                ]
            ]);

            return json_decode((string)$resp->getBody(), true);

        } catch (\Exception $e) {
            return null;
        }
    });

    return view('currency-detail', compact('coin', 'details'));
}

}