<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crypto Tracker</title>
    <style>
        body {
            background-color: #0d0d1a;
            color: #ffffff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
            text-align: center;
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #0ff;
            text-shadow: 0 0 10px #0ff, 0 0 20px #06f;
        }
        a {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(90deg, #0ff, #06f);
            color: #fff;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        a:hover {
            filter: brightness(1.2);
        }
    </style>
</head>
<body>

    <h1>🪙 Crypto Tracker</h1>
    <p>View real-time cryptocurrency prices and details</p>

    <a href="{{ route('currencies.index') }}">Go to Currencies</a>

</body>
</html>
