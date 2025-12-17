<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - @yield('title','Tableau de bord')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color:#0f172a; color:#e2e8f0; }
        .navbar, .footer { background-color:#1e3a8a; }
        .card { background-color:#1e293b; color:#e2e8f0; }
        .btn-primary { background-color:#2563eb; border-color:#2563eb; }
        .btn-primary:hover { background-color:#1d4ed8; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('admin.events.index') }}">
            <span class="text-info">Admin</span> <span class="text-light">Billetterie MG</span>
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li><a class="nav-link" href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                <li><a class="nav-link" href="{{ route('admin.events.index') }}">Événements</a></li>
                <li><a class="nav-link" href="{{ route('admin.organizers.index') }}">Organisateurs</a></li>
                <li><a class="nav-link" href="{{ route('admin.venues.index') }}">Salles</a></li>
                <li><a class="nav-link" href="{{ route('admin.ticket-types.index') }}">Billets</a></li>
                <li><a class="nav-link" href="{{ route('admin.orders.index') }}">Commandes</a></li>
                <li><a class="nav-link" href="{{ route('admin.reservations.index') }}">Réservations</a></li>
                <li><a class="nav-link" href="{{ route('admin.payments.index') }}">Paiements</a></li>
 
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
<main class="container py-4">@yield('content')</main>
<footer class="text-center text-white py-4 mt-5 bg-primary">
    <p>&copy; {{ date('Y') }} Billetterie Madagascar — Interface Admin</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
