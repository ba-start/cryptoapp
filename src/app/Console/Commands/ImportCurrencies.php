<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Currency;

class ImportCurrencies extends Command
{
    protected $signature = 'coins:import {--top=500} {--per_page=50}';
    protected $description = 'Import/update top currencies from CoinGecko';

    public function handle(): void
    {
        $top = (int)$this->option('top');
        $perPage = (int)$this->option('per_page');
        $pages = ceil($top / $perPage);

        for ($page = 1; $page <= $pages; $page++) {
            try {
                $res = Http::get('https://api.coingecko.com/api/v3/coins/markets', [
                    'vs_currency' => 'usd',
                    'order' => 'market_cap_desc',
                    'per_page' => $perPage,
                    'page' => $page,
                    'sparkline' => false,
                ]);

                if (!$res->ok()) {
                    $this->warn("Failed to fetch page $page, skipping...");
                    continue;
                }

                foreach ($res->json() as $coin) {
                    \App\Models\Currency::updateOrCreate(
                        ['coin_id' => $coin['id']], // ✅ fill coin_id here
                        [
                            'name' => $coin['name'] ?? 'Unknown',
                            'symbol' => strtoupper($coin['symbol'] ?? ''),
                            'current_price' => $coin['current_price'] ?? 0,
                            'price_change_percentage_24h' => $coin['price_change_percentage_24h'] ?? 0,
                            'market_cap' => $coin['market_cap'] ?? 0,
                            'image_url' => $coin['image'] ?? null,
                        ]
                    );
                }

                $progress = min($page * $perPage, $top);
                Cache::put('import_progress', round($progress / $top * 100));

                $this->info("Page $page imported successfully. Progress: " . Cache::get('import_progress') . "%");

            } catch (\Exception $e) {
                $this->error("Failed to import page $page: " . $e->getMessage());
            }
        }

        Cache::put('import_progress', 100);
        $this->info("Import complete. All coins updated.");
    }
}
