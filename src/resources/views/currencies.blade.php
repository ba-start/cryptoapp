@extends('layouts.app')

@section('content')

<h1 style="
    font-size: 2.5rem; 
    font-weight: bold; 
    text-align: center; 
    background: linear-gradient(90deg, #7f00ff, #e100ff, #ff00ff); 
    -webkit-background-clip: text; 
    -webkit-text-fill-color: transparent; 
    text-shadow: 0 0 6px #7f00ff, 0 0 8px #e100ff;
">
    Crypto Currencies
</h1>

<div style="overflow-x:auto;">
<table style="width:100%; border-collapse: collapse; font-size:0.9rem; background-color:#0b0c1c; color:#ffffff;">
    <thead>
        <tr style="background-color:#1c1d36; color:#ffffff;">
            <th style="padding:8px 4px; text-align:center;">#</th>
            <th style="padding:8px 4px; text-align:left;">Coin</th>
            <th style="padding:8px 4px; text-align:center;">Symbol</th>
            <th style="padding:8px 4px; text-align:right;">Price</th>
            <th style="padding:8px 4px; text-align:right;">24h Change %</th>
            @if($user)
                <th style="padding:8px 4px; text-align:center;">Watchdog</th>
            @endif
            <th style="padding:8px 4px; text-align:right;">Market Cap</th>
        </tr>
    </thead>
    <tbody>
        @foreach($currencies as $index => $coin)
        <tr style="background-color: {{ $index % 2 == 0 ? '#14142c' : '#1c1d36' }};">
            <td style="text-align:center; padding:8px 4px;">{{ $currencies->firstItem() + $index }}</td>

            <!-- Coin image + name -->
            <td style="padding:8px 4px;">
                <a href="{{ route('currencies.show', $coin->id) }}" style="display:flex; align-items:center; gap:10px; width:100%; text-decoration:none; color:#0af;">
                    @if($coin->image_url)
                        <img src="{{ $coin->image_url }}" alt="{{ $coin->name }}" style="width:40px; height:40px; border-radius:50%;">
                    @endif
                    <span>{{ $coin->name }}</span>
                </a>
            </td>

            <!-- Symbol -->
            <td style="text-transform:uppercase; text-align:center; padding:8px 4px;">{{ $coin->symbol }}</td>

            <!-- Current price -->
            <td style="text-align:right; padding:8px 4px;">${{ number_format($coin->current_price, 4) }}</td>

            <!-- 24h change -->
            <td style="text-align:right; padding:8px 4px;" class="{{ $coin->price_change_percentage_24h >= 0 ? 'price-up' : 'price-down' }}">
                {{ number_format($coin->price_change_percentage_24h ?? 0, 2) }}%
            </td>

            <!-- Watchdog button -->
            @if($user)
            <td style="text-align:center; padding:8px 4px;">
                <a href="{{ route('watchdogs.create', ['currency_id' => $coin->id]) }}" 
                    class="btn btn-primary btn-sm btn-gradient px-2 py-1 text-sm inline-block">
                    Add Watchdog
                </a>
            </td>
            @endif

            <!-- Market cap -->
            <td style="text-align:right; padding:8px 4px;">${{ number_format($coin->market_cap ?? 0) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $currencies->links('vendor.pagination.futuristic') }}
</div>

@endsection
