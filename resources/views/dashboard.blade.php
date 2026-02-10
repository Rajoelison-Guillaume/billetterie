@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Hero + Navigation -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">Billetterie Madagascar</h1>
        <p class="lead text-light">Réservez vos billets pour les meilleurs événements à Madagascar</p>
        <div class="mt-3">
            <a href="{{ route('events.index') }}" class="btn btn-info text-dark fw-bold">🎫 Voir les événements</a>
            <a href="{{ route('orders.cart') }}" class="btn btn-secondary">🛒 Mon panier</a>
            @auth
                @if($totalReservations > 0)
                    <a href="{{ route('reservations.index') }}" class="btn btn-success">
                        🪑 Mes places réservées ({{ $totalReservations }})
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="row text-center text-light mb-5">
        <div class="col-md-4"><h2>{{ $eventsCount }}</h2><p>Événements disponibles</p></div>
        <div class="col-md-4"><h2>{{ $ticketsCount }}</h2><p>Billets vendus</p></div>
        <div class="col-md-4"><h2>{{ $organizersCount }}</h2><p>Organisateurs partenaires</p></div>
    </div>

    <!-- Statistiques personnelles -->
    @auth
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-info text-white text-center"><div class="card-body">
                <h5>Billets achetés</h5><p class="fs-4">{{ $totalTickets }}</p>
            </div></div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white text-center"><div class="card-body">
                <h5>Réservations effectuées</h5><p class="fs-4">{{ $totalReservations }}</p>
            </div></div>
        </div>
    </div>
    @endauth

    <!-- Graphiques -->
    @auth
    <div class="row mb-5">
        <div class="col-md-6 mb-4"><canvas id="reservationsChart"></canvas></div>
        <div class="col-md-6 mb-4"><canvas id="typesChart"></canvas></div>
        <div class="col-md-12 mb-4"><canvas id="ticketsByMonthChart"></canvas></div>
    </div>
    @endauth

    <!-- Événements disponibles -->
    <div class="card mb-4">
        <div class="card-header fw-bold">📅 Événements disponibles</div>
        <div class="card-body">
            <ul class="list-group">
                @forelse($availableEvents as $event)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $event->title }} — {{ $event->start_date?->format('d/m/Y H:i') ?? 'Date non définie' }}</span>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-primary">Voir</a>
                    </li>
                @empty
                    <li class="list-group-item">Aucun événement disponible.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Organisateurs en vedette -->
    <h3 class="text-light mt-5 mb-3">🏆 Organisateurs en vedette</h3>
    <div class="row">
        @forelse($featuredOrganizers as $organizer)
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white shadow">
                    @if($organizer->logo)
                        <img src="{{ asset('storage/' . $organizer->logo) }}" class="card-img-top" alt="{{ $organizer->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $organizer->name }}</h5>
                        <p class="card-text">{{ Str::limit($organizer->description, 100) }}</p>
                        <a href="{{ route('organizers.show', $organizer->id) }}" class="btn btn-primary">Voir profil</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Aucun organisateur en vedette.</p>
        @endforelse
    </div>

    <!-- Section cinéma -->
    <h3 class="text-light mt-5 mb-3">🎬 Cinéma & Spectacles</h3>
    <div class="row">
        @forelse($cinemaEvents as $cinema)
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title">{{ $cinema->title }}</h5>
                        <p class="card-text">{{ $cinema->start_date?->format('d/m/Y') ?? 'Date non définie' }} — {{ $cinema->venue->name ?? 'Lieu inconnu' }}</p>
                        <a href="{{ route('events.show', $cinema->id) }}" class="btn btn-info text-dark">Voir séance</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Aucun spectacle ou séance cinéma disponible.</p>
        @endforelse
    </div>

    <!-- Réservations du client -->
    @auth
    <div class="card mb-4">
        <div class="card-header fw-bold">🧾 Vos réservations</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead><tr><th>Événement</th><th>Date</th><th>Place</th><th>Statut</th><th>QR Code</th></tr></thead>
                <tbody>
                    @forelse($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->showtime?->event?->title ?? 'Événement non défini' }}</td>
                            <td>{{ $reservation->showtime?->start_at?->format('d/m/Y H:i') ?? 'Date non définie' }}</td>
                            <td>
                                @if($reservation->seat)
                                    {{ $reservation->seat->row_label }}{{ $reservation->seat->seat_number }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($reservation->ticket)
                                    <span class="badge bg-{{ $reservation->ticket->status === 'unused' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($reservation->ticket->status) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Inconnu</span>
                                @endif
                            </td>
                            <td>
                                @if($reservation->ticket)
                                    {!! QrCode::size(80)->generate($reservation->ticket->qr_code) !!}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">Aucune réservation trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Historique des commandes -->
    <div class="card">
        <div class="card-header fw-bold">📜 Historique des commandes</div>
        <div class="card-body">
            @forelse($orders as $order)
                <div class="mb-3">
                    <strong>Commande #{{ $order->id }}</strong> — {{ $order->created_at->format('d/m/Y H:i') }}<br>
                    Total : {{ number_format($order->total_amount, 0, ',', ' ') }} Ar — Statut : {{ ucfirst($order->status) }}
                    <ul>
                        @foreach($order->tickets as $ticket)
                            <li>
                                🎟️ {{ $ticket->event->title }} — {{ number_format($ticket->price, 0, ',', ' ') }} Ar
                                @if($ticket->seat) 🪑 {{ $ticket->seat->row_label }}{{ $ticket->seat->seat_number }} @endif
                                {!! QrCode::size(60)->generate($ticket->qr_code) !!}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <p class="text-muted">Aucune commande passée.</p>
            @endforelse
        </div>
    </div>
    @endauth
</div>
@endsection
