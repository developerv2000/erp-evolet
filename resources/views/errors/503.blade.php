{{-- Custom Maintance page --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-misc.noindex-tags />

    <link rel="icon" type="image/x-icon" href="{{ asset('img/main/favicon.png') }}">

    <title>503 | We will be back soon!</title>

    <style>
        :root {
            --primary: #1e88e5;
            --dark: #0d47a1;
            --light: #e3f2fd;
            --text: #333;
            --bg: #f9f9f9;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            padding: 1rem;
        }

        .container {
            max-width: 600px;
            background: #fff;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-top: 6px solid var(--primary);
            animation: fadeIn 1s ease-in-out;
        }

        h1 {
            font-size: 4rem;
            color: var(--dark);
        }

        h2 {
            margin-top: 0.5rem;
            font-size: 1.5rem;
            color: var(--primary);
        }

        p {
            margin: 1rem 0;
            font-size: 1rem;
            color: #666;
        }

        .logo {
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 2rem;
            color: var(--primary);
            letter-spacing: 1px;
        }

        .wave {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            animation: wave 1.5s infinite;
        }

        @keyframes wave {

            0%,
            60%,
            100% {
                transform: rotate(0deg);
            }

            30% {
                transform: rotate(15deg);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 3rem;
            }

            h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="wave">👷</div>
        <h1>503</h1>
        <h2>We will be back soon!</h2>
        <p>Sorry for the inconvenience but we are performing some maintenance right now.</p>
        <p>We will be back online shortly</p>
        <div class="logo">{{ config('app.name', 'Laravel') }}</div>
    </div>
</body>

</html>
