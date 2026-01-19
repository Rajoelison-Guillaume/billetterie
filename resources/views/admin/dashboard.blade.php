@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">📊 Tableau de bord Admin</h2>

    {{-- Compteurs globaux --}}
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Événements</h5>
                    <p>{{ $eventsCount }}</p>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-info">Gérer</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Billets</h5>
                    <p>{{ $ticketsCount }}</p>
                    <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-sm btn-info">Voir</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Commandes</h5>
                    <p>{{ $ordersCount }}</p>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-info">Voir</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Réservations</h5>
                    <p>{{ $reservationsCount }}</p>
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-info">Voir</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Graphiques dynamiques --}}
    <div class="row mt-5">
        <div class="col-md-6 mb-4">
            <canvas id="ticketsChart"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="venueChart"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="typeChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 🎟️ Billets par événement
    const ticketsCtx = document.getElementById('ticketsChart');
    new Chart(ticketsCtx, {
        type: 'bar',
        data: {
            labels: @json($ticketsByEvent->pluck('title')),
            datasets: [{
                label: 'Billets',
                data: @json($ticketsByEvent->map(fn($e) => $e->tickets->count())),
                backgroundColor: '#3b82f6'
            }]
        },
        options: {
            responsive: true,
            plugins: { title: { display: true, text: 'Billets par événement' } }
        }
    });

    // 🏟️ Événements par lieu
    const venueCtx = document.getElementById('venueChart');
    new Chart(venueCtx, {
        type: 'bar',
        data: {
            labels: @json($eventsByVenue->pluck('name')),
            datasets: [{
                label: 'Nombre d\'événements',
                data: @json($eventsByVenue->pluck('events_count')),
                backgroundColor: '#22c55e'
            }]
        },
        options: {
            responsive: true,
            plugins: { title: { display: true, text: 'Événements par lieu' } }
        }
    });

    // 🧮 Répartition par type d’événement
    const typeCtx = document.getElementById('typeChart');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: @json($eventsByType->pluck('label')),
            datasets: [{
                data: @json($eventsByType->pluck('events_count')),
                backgroundColor: ['#f59e0b','#10b981','#ef4444','#8b5cf6']
            }]
        },
        options: {
            responsive: true,
            plugins: { title: { display: true, text: 'Répartition des types d\'événements' } }
        }
    });
</script>
@endsection
