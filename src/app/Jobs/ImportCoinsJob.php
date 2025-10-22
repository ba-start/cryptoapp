<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ImportCoinsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $top;
    public int $perPage;

    public function __construct(int $top = 500, int $perPage = 50)
    {
        $this->top = $top;
        $this->perPage = $perPage;
    }

    public function handle(): void
    {
        // Dispatch the command with the given options
        Artisan::call('coins:import', [
            '--top' => $this->top,
            '--per_page' => $this->perPage,
        ]);

        Log::info("ImportCoinsJob completed: top={$this->top}, per_page={$this->perPage}");
    }
}
