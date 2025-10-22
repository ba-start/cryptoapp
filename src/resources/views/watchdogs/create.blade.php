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
        <h2 style="
            font-size: 2rem; 
            font-weight: bold; 
            background: linear-gradient(90deg, #6a5acd, #4b0082, #00bfff); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            text-shadow: 0 0 6px #6a5acd, 0 0 8px #4b0082;
            text-align: center;
        ">
            Create WatchDog
        </h2>

        <form method="POST" action="{{ route('watchdogs.store') }}" style="display: flex; flex-direction: column; gap: 15px;">
            @csrf
            <div class="mb-3" style="display: flex; flex-direction: column; gap: 5px;">
                <label for="currency_id" style="font-weight: bold; color: #b0b0ff;">Currency</label>
                <select name="currency_id" id="currency_id" class="form-control bg-dark text-light border-secondary" required
                    style="
                        padding: 10px; 
                        border-radius: 10px; 
                        border: 1px solid #4b0082; 
                        background: #0d0d1a; 
                        color: #e0e0ff;
                        box-shadow: inset 0 0 6px rgba(72,61,139,0.5);
                    ">
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}"
                            {{ isset($selectedCurrency) && $selectedCurrency == $currency->id ? 'selected' : '' }}>
                            {{ $currency->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3" style="display: flex; flex-direction: column; gap: 5px;">
                <label for="target_price" style="font-weight: bold; color: #b0b0ff;">Target Price</label>
                <input type="number" step="0.01" name="target_price" id="target_price" required
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
                Create WatchDog
            </button>
        </form>

        <p style="text-align: center; margin-top: 10px;">
            <a href="{{ url('/dashboard') }}" style="color: #b0b0ff; text-decoration: underline;">← Back to WatchDogs</a>
        </p>
    </div>
</div>
@endsection
