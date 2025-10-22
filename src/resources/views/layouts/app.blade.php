<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Crypto Watchdog' }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Optional: your custom CSS -->
    <link href="{{ asset('css/app.css') }}?v={{ time() }}" rel="stylesheet">
</head>

<body class="bg-dark text-light">
@if(session('success'))
    <div class="success-msg">
        {{ session('success') }}
    </div>
@endif
    
@include('layouts.topbar')
    



<div class="container">
    @yield('content')
</div>

</body>
</html>
