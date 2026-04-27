<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Billetterie Madagascar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS global -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- AOS Animations -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
</head>
<body>
    <!-- Navigation -->        
    @include('layouts.navigation')

    <!-- Hero Section -->
    <section class="hero" data-aos="fade-up">
        <div class="container text-center">
            <h1>Billetterie Madagascar</h1>
            <p class="lead">Réservez vos billets pour les meilleurs évènements à Madagascar</p>
            <a href="{{ route('events.index') }}" class="btn btn-primary me-2">🎫 Voir les événements</a>
            <a href="{{ route('orders.cart') }}" class="btn btn-secondary">🛒 Mon panier</a>
        </div>
    </section>

    <!-- Contenu principal -->
    <main class="container py-5">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer bg-light py-3 text-center">
        <p class="mb-2">&copy; {{ date('Y') }} Billetterie Madagascar - Tous droits réservés</p>
            <div class="footer-social">
                <a href="#" class="me-3"><i class="bi bi-facebook"></i></a>
                <a href="#" class="me-3"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
            </div>
    </footer>


    <!-- Scripts -->

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
