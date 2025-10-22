<nav class="navbar navbar-expand-lg py-2 px-3 shadow-sm" style="
    position: sticky; 
    top: 0; 
    z-index: 1000;
    background: linear-gradient(135deg, #0d0d1a, #1a1a33);
    box-shadow: 0 2px 10px rgba(0,0,0,0.7);
">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        {{-- Brand --}}
        <a href="{{ url('/') }}" class="navbar-brand fw-bold" style="
            font-size: 1.5rem;
            background: linear-gradient(90deg, #8a2be2, #4b0082, #00ffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 4px #8a2be2, 0 0 6px #4b0082;
            transition: transform 0.2s, text-shadow 0.2s;
        " 
        onmouseover="this.style.transform='scale(1.05)'; this.style.textShadow='0 0 8px #8a2be2, 0 0 12px #4b0082, 0 0 10px #00ffff'" 
        onmouseout="this.style.transform='scale(1)'; this.style.textShadow='0 0 4px #8a2be2, 0 0 6px #4b0082'">
            Crypto Watchdog
        </a>

        {{-- Links & user controls --}}
        <div class="d-flex align-items-center">
            @if($user)
                <a href="{{ route('currencies.index') }}" class="fw-bold me-3" style="color:#e0e0ff; text-shadow: 0 0 2px #4b0082; transition: text-shadow 0.2s, color 0.2s;"
                   onmouseover="this.style.color='#00ffff'; this.style.textShadow='0 0 6px #8a2be2, 0 0 10px #00ffff'"
                   onmouseout="this.style.color='#e0e0ff'; this.style.textShadow='0 0 2px #4b0082'">Currencies</a>

                <a href="{{ route('dashboard') }}" class="fw-bold me-3" style="color:#e0e0ff; text-shadow: 0 0 2px #4b0082; transition: text-shadow 0.2s, color 0.2s;"
                   onmouseover="this.style.color='#00ffff'; this.style.textShadow='0 0 6px #8a2be2, 0 0 10px #00ffff'"
                   onmouseout="this.style.color='#e0e0ff'; this.style.textShadow='0 0 2px #4b0082'">Watch Dogs</a>

                <a href="{{ route('settings.edit') }}" class="fw-bold me-3" style="color:#e0e0ff; text-shadow: 0 0 2px #4b0082; transition: text-shadow 0.2s, color 0.2s;"
                   onmouseover="this.style.color='#00ffff'; this.style.textShadow='0 0 6px #8a2be2, 0 0 10px #00ffff'"
                   onmouseout="this.style.color='#e0e0ff'; this.style.textShadow='0 0 2px #4b0082'">Settings</a>

                <span class="text-light me-3" style="color:#b0b0ff; text-shadow:0 0 2px #4b0082;">Hi, {{ $user->name }}</span>

                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm" style="
                        border: 1px solid #4b0082;
                        color:#e0e0ff;
                        background: transparent;
                        transition: transform 0.2s, box-shadow 0.2s;
                    " onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px #8a2be2'"
                       onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm me-2" style="
                    border: 1px solid #4b0082;
                    color:#e0e0ff;
                    background: transparent;
                    transition: transform 0.2s, box-shadow 0.2s;
                " onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px #8a2be2'"
                   onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">Login</a>

                <a href="{{ route('register') }}" class="btn btn-sm" style="
                    border: 1px solid #4b0082;
                    color:#e0e0ff;
                    background: transparent;
                    transition: transform 0.2s, box-shadow 0.2s;
                " onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px #8a2be2'"
                   onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">Register</a>
            @endif
        </div>

    </div>

    {{-- Subtle star overlay for cosmic effect --}}
    <div style="
        position:absolute;
        top:0; left:0;
        width:100%; height:100%;
        pointer-events:none;
        background: radial-gradient(white 1px, transparent 1px);
        background-size: 20px 20px;
        opacity:0.03;
        z-index:1;
    "></div>
</nav>
