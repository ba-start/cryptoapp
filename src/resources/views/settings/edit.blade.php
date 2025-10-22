@extends('layouts.app')

@section('content')
<div class="container my-5" style="max-width: 500px;">

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
            text-align: center;
            background: linear-gradient(90deg, #32cd32, #00fa9a, #008000);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 6px #32cd32, 0 0 8px #00fa9a;
        ">
            User Settings
        </h2>

        {{-- Success Message --}}
        @if(session('success'))
            <div style="
                padding: 10px 15px;
                background: linear-gradient(135deg, #00fa9a, #32cd32);
                color: #0d0d1a;
                border-radius: 10px;
                text-align: center;
                font-weight: bold;
                box-shadow: 0 4px 10px rgba(0,255,128,0.5);
            ">
                {{ session('success') }}
            </div>
        @endif

        {{-- Settings Form --}}
        <form action="{{ route('settings.update') }}" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            @csrf

            {{-- Name --}}
            <div style="display: flex; flex-direction: column; gap: 5px;">
                <label for="name" style="font-weight: bold; color: #b0b0ff;">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                    class="@error('name') is-invalid @enderror"
                    style="
                        padding: 10px;
                        border-radius: 10px;
                        border: 1px solid #4b0082;
                        background: #0d0d1a;
                        color: #e0e0ff;
                        box-shadow: inset 0 0 6px rgba(72,61,139,0.5);
                    ">
                @error('name')
                    <div style="color:#ff5252; font-size: 0.85rem;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div style="display: flex; flex-direction: column; gap: 5px;">
                <label for="email" style="font-weight: bold; color: #b0b0ff;">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                    class="@error('email') is-invalid @enderror"
                    style="
                        padding: 10px;
                        border-radius: 10px;
                        border: 1px solid #4b0082;
                        background: #0d0d1a;
                        color: #e0e0ff;
                        box-shadow: inset 0 0 6px rgba(72,61,139,0.5);
                    ">
                @error('email')
                    <div style="color:#ff5252; font-size: 0.85rem;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div style="display: flex; flex-direction: column; gap: 5px;">
                <label for="password" style="font-weight: bold; color: #b0b0ff;">New Password <small style="color:#aaa;">(leave blank to keep current)</small></label>
                <input type="password" id="password" name="password" 
                    class="@error('password') is-invalid @enderror"
                    style="
                        padding: 10px;
                        border-radius: 10px;
                        border: 1px solid #4b0082;
                        background: #0d0d1a;
                        color: #e0e0ff;
                        box-shadow: inset 0 0 6px rgba(72,61,139,0.5);
                    ">
                @error('password')
                    <div style="color:#ff5252; font-size: 0.85rem;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password Confirmation --}}
            <div style="display: flex; flex-direction: column; gap: 5px;">
                <label for="password_confirmation" style="font-weight: bold; color: #b0b0ff;">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                    style="
                        padding: 10px;
                        border-radius: 10px;
                        border: 1px solid #4b0082;
                        background: #0d0d1a;
                        color: #e0e0ff;
                        box-shadow: inset 0 0 6px rgba(72,61,139,0.5);
                    ">
            </div>

            {{-- Submit --}}
            <button type="submit" style="
                padding: 12px 20px;
                font-weight: bold;
                border-radius: 12px;
                background: linear-gradient(135deg, #32cd32, #00fa9a, #008000);
                color: #fff;
                text-shadow: 0 0 4px #32cd32;
                transition: transform 0.2s, box-shadow 0.2s;
            "
            onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 18px rgba(0,255,128,0.8)'"
            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 12px rgba(0,255,128,0.6)'">
                Save Changes
            </button>
        </form>
    </div>
</div>
@endsection
