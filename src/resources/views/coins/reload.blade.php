@extends('layouts.app')

@section('content')

<style>

    h1 {
        font-size: 3rem;
        margin-bottom: 10px;
        color: #00ff88;
        text-shadow: 0 0 10px #00ff88, 0 0 20px #00ff44;
        text-align: center;
        animation: glow 1.5s infinite alternate;
    }

    @keyframes glow {
        0% { text-shadow: 0 0 10px #00ff88, 0 0 20px #00ff44; }
        100% { text-shadow: 0 0 20px #00ff88, 0 0 40px #00ff44; }
    }

    .btn-cyber {
        display: inline-block;
        padding: 12px 24px;
        background: linear-gradient(90deg, #00ff88, #00ffaa, #00ff88);
        color: #0d0d0d;
        font-weight: bold;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 0 8px #00ff88, 0 0 15px #00ffaa;
        cursor: pointer;
        margin: 20px auto;
    }

    .btn-cyber:hover {
        transform: scale(1.05);
        box-shadow: 0 0 12px #00ff88, 0 0 25px #00ffaa;
    }

    .terminal-card {
        background-color: #0b0b0b;
        border-radius: 15px;
        padding: 20px;
        max-width: 800px;
        width: 100%;
        height: 400px;
        overflow-y: auto;
        box-shadow: 0 0 20px rgba(0, 255, 136, 0.6);
        font-size: 0.9rem;
        border: 2px solid #00ff88;
        animation: flicker 2s infinite;
    }

    .terminal-line {
        margin: 0;
        padding: 0;
    }

    @keyframes flicker {
        0% { opacity: 1; }
        50% { opacity: 0.85; }
        100% { opacity: 1; }
    }
</style>
</head>
<body>

<h1>🪙 Crypto Tracker - Reload Coins</h1>
<p style="text-align:center;">Matrix/Cyber style coin import</p>

<form id="reloadForm" method="POST" action="{{ route('currencies.refreshSync') }}">
    @csrf
    <button type="submit" class="btn-cyber" id="reloadCoins">Initialize / Reload Coins</button>
</form>

<div class="terminal-card" id="terminal">
    <p class="terminal-line">Waiting for action...</p>
</div>

<script>
const button = document.getElementById('reloadCoins');
const terminal = document.getElementById('terminal');

document.getElementById('reloadForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    button.disabled = true;
    terminal.innerHTML = '<p class="terminal-line">🚀 Starting coin import...</p>';

    try {
        // Trigger synchronous import (calls your coins:import command)
        await fetch('{{ route("currencies.refreshSync") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        // Poll progress every 500ms
        const interval = setInterval(async () => {
            const res = await fetch('{{ route("currencies.progress") }}');
            const data = await res.json();
            terminal.innerHTML = `<p class="terminal-line">Progress: ${data.progress}%</p>`;
            terminal.scrollTop = terminal.scrollHeight;

            if (data.progress >= 100) {
                clearInterval(interval);
                terminal.innerHTML += '<p class="terminal-line">✅ Coin import complete!</p>';
                button.disabled = false;
            }
        }, 500);

    } catch (err) {
        terminal.innerHTML += `<p class="terminal-line" style="color:#ff5252;">Error: ${err.message}</p>`;
        button.disabled = false;
    }
});
</script>


@endsection
