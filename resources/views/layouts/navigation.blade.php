<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <span class="text-info">Billetterie</span> <span class="text-light">MG</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto">
                <!-- Menu Événements -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="eventDropdown" role="button" data-bs-toggle="dropdown">
                        Agenda
                    </a>
                    <ul class="dropdown-menu bg-dark border border-primary">
                        <li><a class="dropdown-item text-light" href="{{ route('events.cinema') }}">🎬 Cinéma</a></li>
                        <li><a class="dropdown-item text-light" href="{{ route('events.libre') }}">🎟️ Événements libres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-light" href="{{ route('events.index') }}">📅 Tous les événements</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="{{ route('organizers.index') }}">📣 Organisateurs</a></li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('orders.cart') }}">🛒 Panier</a>
                </li>

                @auth
                    {{-- Lien Admin visible uniquement pour les admins --}}
                    @if(Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link text-info fw-bold" href="{{ route('admin.events.index') }}">🎛️ Espace Admin</a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark border border-primary">
                            <li><a class="dropdown-item text-light" href="{{ route('profile.edit') }}">👤 Profil</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-light">🚪 Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="btn btn-outline-light me-2" href="{{ route('login') }}">Connexion</a></li>
                    <li class="nav-item"><a class="btn btn-info text-dark fw-bold" href="{{ route('register') }}">S'inscrire</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
