<div class="container">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        {{-- Header --}}
        <h1 style="
            font-size: 2.5rem; 
            font-weight: bold; 
            text-align: center; 
            background: linear-gradient(90deg, #6a5acd, #4b0082, #00bfff); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            text-shadow: 0 0 4px #6a5acd, 0 0 6px #4b0082;
        ">
            My WatchDogs
        </h1>

        {{-- Add WatchDog Button --}}
        @if($user)
            <a href="{{ route('watchdogs.create') }}" class="btn-gradient" style="
                padding: 12px 20px; 
                font-size: 1rem; 
                font-weight: bold; 
                border-radius: 10px; 
                background: linear-gradient(135deg, #8a2be2, #4b0082, #00ffff);
                color: #fff; 
                text-decoration: none;
                box-shadow: 0 4px 12px rgba(138,43,226,0.6);
                transition: transform 0.2s, box-shadow 0.2s;
            " onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 18px rgba(138,43,226,0.8)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 12px rgba(138,43,226,0.6)'">
                + Add WatchDog
            </a>
        @endif
    </div>

    <style>
        @keyframes shimmer {
            0% { background-position: -500px 0; }
            100% { background-position: 500px 0; }
        }
        h1 {
            background-size: 1000px 100%;
        }

        /* Green glow animation for target hit */
        @keyframes glow-green {
            0% { box-shadow: 0 0 5px #0f0; }
            100% { box-shadow: 0 0 20px #0f0; }
        }
    </style>

    {{-- Watchdog cards --}}
    <div class="watchdogs-container" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
        @forelse($watchdogs as $watchdog)
            @php
                $currentPrice = $watchdog->currency->current_price ?? 0;
                $targetPrice = $watchdog->target_price;
                $difference = $currentPrice - $targetPrice;
                $percentToTarget = $targetPrice > 0 ? min(($currentPrice / $targetPrice) * 100, 100) : 0;

                // Check if price hits exactly (use bccomp for precision)
                $watchdogHit = $targetPrice > 0 && bccomp((string)$currentPrice, (string)$targetPrice, 8) === 0;

                $cardStyle = "
                    width: 300px;
                    height: 222px;
                    padding: 20px;
                    border-radius: 15px;
                    background: linear-gradient(135deg, #1a1a33, #0d0d1a);
                    color: #e0e0ff;
                    display: flex;
                    flex-direction: column;
                    gap: 5px;
                    transition: transform 0.2s, box-shadow 0.2s;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.5);
                ";

                if($watchdogHit) {
                    $cardStyle .= " animation: glow-green 1.5s infinite alternate; box-shadow: 0 0 15px #0f0;";
                }
            @endphp


            <a href="{{ route('watchdogs.edit', $watchdog->id) }}" style="text-decoration: none; color: inherit;">
                <div class="watchdog-card" style="{{ $cardStyle }}"
                    onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 25px rgba(72, 61, 139, 0.7)'" 
                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.5)'">

                    <div class="watchdog-header" style="display: flex; align-items: center; gap: 10px;">
                        @if($watchdog->currency && $watchdog->currency->image_url)
                            <img src="{{ $watchdog->currency->image_url }}" 
                                alt="{{ $watchdog->currency->name }}" 
                                style="width:40px;height:40px;border-radius:50%; box-shadow: 0 0 8px rgba(138, 43, 226, 0.6);" 
                                onerror="this.src='{{ asset('images/fallback-coin.png') }}'">
                        @endif
                        <span class="watchdog-name" style="font-size: 1.2rem; font-weight: bold; text-shadow: 0 0 4px #8a2be2;">
                            {{ $watchdog->currency->name ?? 'No currency' }}
                        </span>
                    </div>

                    <div class="watchdog-body" style="display: flex; flex-direction: column; gap: 10px;">
                        <div class="watchdog-price" style="font-size: 1.4rem; font-weight: bold; color: #e0e0ff; text-shadow: 0 0 6px #8a2be2;">
                            ${{ number_format($currentPrice, 2) }}
                            <small style="font-size: 0.85rem; color: #aaa;">(Target: ${{ number_format($targetPrice, 2) }})</small>
                        </div>

                        <div class="watchdog-diff" style="font-size: 0.9rem; color: #b0b0ff; text-shadow: 0 0 2px #444;">
                            ${{ number_format(abs($difference), 2) }} {{ $watchdogHit ? 'above' : 'away from' }} target 
                            ({{ number_format($percentToTarget, 1) }}%)
                        </div>

                        <div class="watchdog-progress-bar" style="width: 100%; height: 10px; background: #111; border-radius: 5px; box-shadow: inset 0 0 4px #4b0082;">
                            <div class="progress-fill" style="height: 100%; width: {{ $percentToTarget }}%; background: linear-gradient(90deg, #8a2be2, #4b0082); border-radius: 5px; box-shadow: 0 0 6px #8a2be2;"></div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <p style="color:#aaa; text-align:center;">No WatchDogs set.</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrapper">
        {{ $watchdogs->links('vendor.pagination.futuristic') }}
    </div>
</div>
