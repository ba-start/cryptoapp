@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow" style="background: #1f1f2a; border: 1px solid #333; border-radius: 12px; width: 400px;">
        <h2 class="text-center mb-4 text-white">Login</h2>

        @if(session('error'))
            <div class="alert alert-danger text-white" style="background: #b91c1c;">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label text-white">Email</label>
                <input type="email" class="form-control bg-dark text-white border-0" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label text-white">Password</label>
                <input type="password" class="form-control bg-dark text-white border-0" id="password" name="password" required>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-gradient w-100 mt-2">Login</button>

            <p class="mt-3 text-center text-white">
                Don't have an account? <a href="{{ route('register') }}" class="text-info">Register</a>
            </p>
        </form>
    </div>
</div>
@endsection
