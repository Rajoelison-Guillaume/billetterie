<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - @yield('title','Tableau de bord')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    


    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS global -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body { background-color:#0f172a; color:#e2e8f0; font-family:'Segoe UI', sans-serif; }
        .navbar {
            background: rgba(30,58,138,0.85);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .card {
            background-color:#1e293b;
            border:1px solid rgba(37,99,235,0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 0 15px rgba(37,99,235,0.6);
        }
        .btn-primary {
            background: linear-gradient(90deg,#2563eb,#06b6d4);
            border:none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 15px #06b6d4;
        }
        footer {
            background: linear-gradient(90deg,#1e3a8a,#0f172a);
            color:#e2e8f0;
        }
        .stat-number {
            background: rgba(37,99,235,0.2);
            color: #fff;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: bold;
        }
        /* Chiffres clés en bleu néon futuriste */
.dashboard-number, .dashboard-number-neon {
    font-size: 2rem;
    font-weight: bold;
    color: #00f0ff;
    text-shadow: 0 0 10px #00f0ff;
}

/* Cartes avec contraste fort */
.card {
    background-color: #1e293b; /* fond sombre */
    border: 1px solid rgba(0,240,255,0.3); /* halo bleu */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: scale(1.02);
    box-shadow: 0 0 15px rgba(0,240,255,0.6);
}

/* Titres et labels */
h2, h5 {
    color: #00f0ff;
    text-shadow: 0 0 6px #00f0ff;
}

/* Tableaux */
.table thead {
    background-color: #00f0ff;
    color: #000;
}
.table-dark td, .table-dark th {
    border-color: #333;
}

/* Graphiques Chart.js */
canvas {
    background-color: #1a1a1a;
    border-radius: 10px;
    padding: 10px;
}


    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
            <span class="text-info">Admin</span> <span class="text-light">Billetterie MG</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto">
                <li><a class="nav-link" href="{{ route('admin.dashboard') }}">📊 Tableau de bord</a></li>
                <li><a class="nav-link" href="{{ route('admin.events.index') }}">🎫 Événements</a></li>
                <li><a class="nav-link" href="{{ route('admin.organizers.index') }}">📣 Organisateurs</a></li>
                <li><a class="nav-link" href="{{ route('admin.venues.index') }}">🏟️ Salles</a></li>
                <li><a class="nav-link" href="{{ route('admin.ticket-types.index') }}">🎟️ Billets</a></li>
                <li><a class="nav-link" href="{{ route('admin.orders.index') }}">🛒 Commandes</a></li>
                <li><a class="nav-link" href="{{ route('admin.reservations.index') }}">🪑 Réservations</a></li>
                <li><a class="nav-link" href="{{ route('admin.payments.index') }}">💳 Paiements</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li><a class="nav-link" href="{{ route('profile.edit') }}">{{ Auth::user()->name }}</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Se déconnecter ?')">
                        @csrf
                        <button class="btn btn-outline-light">Déconnexion</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container-fluid py-4">@yield('content')</main>

<footer class="text-center py-4 mt-5">
    <p>&copy; {{ date('Y') }} Billetterie Madagascar — Interface Admin</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@yield('scripts')
</body>
</html>
