<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Billetterie Madagascar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">



    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS global -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- AOS Animations -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <style>
        body {
            background-color: #0f172a;
            color: #e2e8f0;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar {
            background: rgba(30, 58, 138, 0.85);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .footer {
            background: linear-gradient(90deg, #1e3a8a, #0f172a);
            color: #e2e8f0;
        }
        .btn-primary {
            background: linear-gradient(90deg, #2563eb, #06b6d4);
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 15px #06b6d4;
        }
        .futuristic-card {
            background: #1e293b;
            border: 1px solid rgba(37,99,235,0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .futuristic-card:hover {
            transform: scale(1.03);
            box-shadow: 0 0 20px rgba(37,99,235,0.6);
        }
        .hero {
            background: linear-gradient(135deg, #0f172a, #1e3a8a, #2563eb);
            background-size: 400% 400%;
            animation: gradientMove 12s ease infinite;
            color: #e2e8f0;
            padding: 120px 20px;
            text-align: center;
        }
        @keyframes gradientMove {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }
        /* Barre de navigation futuriste */
.navbar {
    background: linear-gradient(90deg, #0f172a, #1e3a8a, #2563eb);
    background-size: 200% 200%;
    animation: gradientMove 12s ease infinite;
    border-bottom: 1px solid rgba(0,240,255,0.3);
}

.navbar-brand span {
    font-weight: bold;
    text-shadow: 0 0 8px #00f0ff;
}

.nav-link {
    color: #e2e8f0 !important;
    transition: color 0.3s ease, text-shadow 0.3s ease;
}
.nav-link:hover {
    color: #00f0ff !important;
    text-shadow: 0 0 6px #00f0ff;
}

/* Dropdown futuriste */
.dropdown-menu {
    background-color: #1e293b;
    border: 1px solid #00f0ff;
}
.dropdown-item {
    color: #e2e8f0;
}
.dropdown-item:hover {
    background-color: #00f0ff;
    color: #000;
    font-weight: bold;
}

/* Boutons futuristes */
.btn-outline-light {
    border: 1px solid #00f0ff;
    color: #00f0ff;
}
.btn-outline-light:hover {
    background-color: #00f0ff;
    color: #000;
    box-shadow: 0 0 12px #00f0ff;
}

.btn-info {
    background: linear-gradient(90deg, #06b6d4, #2563eb);
    border: none;
    color: #000;
    font-weight: bold;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 15px #06b6d4;
}

/* Animation gradient */
@keyframes gradientMove {
    0% {background-position: 0% 50%;}
    50% {background-position: 100% 50%;}
    100% {background-position: 0% 50%;}
}

    </style>
</head>
<body>
    @include('layouts.navigation')

    <main class="container-fluid py-4">
        @yield('content')
    </main>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
