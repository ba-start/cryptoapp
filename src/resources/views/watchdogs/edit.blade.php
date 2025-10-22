@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 450px; margin: 3rem auto;">

    <div class="cosmic-card" style="
        background: linear-gradient(135deg, #1a1a33, #0d0d1a);
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(72, 61, 139, 0.7);
        color: #e0e0ff;
        display: flex;
        flex-direction: column;
        gap: 20px;
    ">

        {{-- Header with Coin Icon --}}
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 1rem;">
            @if($watchdog->currency && $watchdog->currency->image_url)
                <img src="{{ $watchdog->currency->image_url }}" 
                     alt="{{ $watchdog->currency->name }}" 
                     style="width:50px; height:50px; border-radius:50%; box-shadow: 0 0 10px rgba(138, 43, 226, 0.7);" 
                     onerror="this.src='{{ asset('images/fallback-coin.png') }}'">
            @endif
            <h2 style="
                font-size: 2rem;
                font-weight: bold;
                background: linear-gradient(90deg, #6a5acd, #4b0082, #00bfff);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                text-shadow: 0 0 6px #6a5acd, 0 0 8px #4b0082;
            ">
                Edit {{ $watchdog->currency->name ?? 'WatchDog' }}
            </h2>
        </div>

        {{-- Update Form --}}
        <form method="POST" action="{{ route('watchdogs.update', $watchdog->id) }}" style="display: flex; flex-direction: column; gap: 15px;">
            @csrf
            @method('PUT')

            <div class="mb-3" style="display: flex; flex-direction: column; gap: 5px;">
                <label for="currency" style="font-weight: bold; color: #b0b0ff;">Currency</label>
                <input type="text" class="form-control bg-dark text-light" value="{{ $watchdog->currency->name }}" disabled
                    style="
                        padding: 10px;
                        border-radius: 10px;
                        border: 1px solid #4b0082;
                        background: #0d0d1a;
                        color: #e0e0ff;
                        box-shadow: inset 0 0 6px rgba(72,61,139,0.5);
                    ">
            </div>

            <div class="mb-3" style="display: flex; flex-direction: column; gap: 5px;">
                <label for="target_price" style="font-weight: bold; color: #b0b0ff;">Target Price</label>
                <input type="number" step="0.01" name="target_price" value="{{ $watchdog->target_price }}" required
                    style="
                        padding: 10px;
                        border-radius: 10px;
                        border: 1px solid #4b0082;
                        background: #0d0d1a;
                        color: #e0e0ff;
                        box-shadow: inset 0 0 6px rgba(72,61,139,0.5);
                    ">
            </div>

            <button type="submit" class="btn-gradient" style="
                padding: 12px 20px;
                font-weight: bold;
                border-radius: 12px;
                background: linear-gradient(135deg, #8a2be2, #4b0082, #00ffff);
                color: #fff;
                text-shadow: 0 0 4px #8a2be2;
                transition: transform 0.2s, box-shadow 0.2s;
            "
            onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 18px rgba(138,43,226,0.8)'" 
            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 12px rgba(138,43,226,0.6)'">
                Update WatchDog
            </button>
        </form>

        {{-- Delete Form --}}
        <form method="POST" action="{{ route('watchdogs.destroy', $watchdog->id) }}" onsubmit="return confirm('Are you sure you want to delete this WatchDog?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-red" style="
                width: 100%;
                padding: 12px;
                font-weight: bold;
                border-radius: 12px;
                background: linear-gradient(135deg, #ff5252, #c70039);
                color: #fff;
                text-shadow: 0 0 4px #ff0000;
                transition: transform 0.2s, box-shadow 0.2s;
            "
            onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 18px rgba(255,82,82,0.8)'" 
            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 12px rgba(255,82,82,0.6)'">
                Delete WatchDog
            </button>
        </form>

        {{-- Back link --}}
        <p style="text-align: center; margin-top: 1rem;">
            <a href="{{ url('/dashboard') }}" style="color:#b0b0ff; text-decoration: underline;">← Back to WatchDogs</a>
        </p>

    </div>
</div>
@endsection
