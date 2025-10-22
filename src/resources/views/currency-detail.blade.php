@extends('layouts.app')
    
@section('content')

<h1 style="
    font-size: 2.5rem; 
    font-weight: bold; 
    text-align: center; 
    background: linear-gradient(90deg, #32cd32, #00fa9a, #008000); 
    -webkit-background-clip: text; 
    -webkit-text-fill-color: transparent; 
    text-shadow: 0 0 4px #32cd32, 0 0 6px #00fa9a;
">
{{ $coin->name }} ({{ strtoupper($coin->symbol) }})</h1>
    
<div class="flex items-center my-4">
    @if($coin->image_url)
        <img src="{{ $coin->image_url }}" alt="{{ $coin->name }}" 
             style="width:48px; height:48px; border-radius:50%;">
    @endif

    {{-- Push button to the right --}}
    <a href="{{ route('watchdogs.create', ['currency_id' => $coin->id]) }}" 
       class="btn-gradient flex items-center justify-center ml-auto"
       style="height:48px; line-height:1; padding:0 1rem;">
       + Add Watchdog
    </a>
</div>



    @if($details)
        <h2>Description</h2>
        <p>{!! $details['description']['en'] ?? 'No description available.' !!}</p>

        <h2>Market Data</h2>
        <table>
            <tr><th>Current Price (USD)</th><td>${{ $details['market_data']['current_price']['usd'] ?? 'N/A' }}</td></tr>
            <tr><th>ATH (USD)</th><td>${{ $details['market_data']['ath']['usd'] ?? 'N/A' }}</td></tr>
            <tr><th>ATL (USD)</th><td>${{ $details['market_data']['atl']['usd'] ?? 'N/A' }}</td></tr>
            <tr><th>Market Cap</th><td>${{ $details['market_data']['market_cap']['usd'] ?? 'N/A' }}</td></tr>
            <tr><th>Circulating Supply</th><td>{{ $details['market_data']['circulating_supply'] ?? 'N/A' }}</td></tr>
            <tr><th>Total Supply</th><td>{{ $details['market_data']['total_supply'] ?? 'N/A' }}</td></tr>
        </table>

        <h2>Links</h2>
        <ul>
            @foreach($details['links']['homepage'] ?? [] as $link)
                @if($link)<li><a href="{{ $link }}" target="_blank">{{ $link }}</a></li>@endif
            @endforeach
        </ul>

        <h2>Community & Developer</h2>
        <table>
            <tr><th>Twitter Followers</th><td>{{ $details['community_data']['twitter_followers'] ?? 'N/A' }}</td></tr>
            <tr><th>Reddit Subscribers</th><td>{{ $details['community_data']['reddit_subscribers'] ?? 'N/A' }}</td></tr>
            <tr><th>GitHub Stars</th><td>{{ $details['developer_data']['stars'] ?? 'N/A' }}</td></tr>
            <tr><th>GitHub Forks</th><td>{{ $details['developer_data']['forks'] ?? 'N/A' }}</td></tr>
        </table>
    @else
        <p>Details not available. CoinGecko API may be rate-limiting requests.</p>
    @endif

    <p><a href="{{ url('/currencies') }}">← Back to list</a></p>
@endsection
