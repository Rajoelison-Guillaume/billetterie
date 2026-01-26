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
                    <p id="eventsCount">{{ $eventsCount }}</p>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-info">Gérer</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Billets</h5>
                    <p id="ticketsCount">{{ $ticketsCount }}</p>
                    <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-sm btn-info">Voir</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Commandes</h5>
                    <p id="ordersCount">{{ $ordersCount }}</p>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-info">Voir</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Réservations</h5>
                    <p id="reservationsCount">{{ $reservationsCount }}</p>
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-info">Voir</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques cinéma --}}
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h5 class="text-success">Places réservées</h5>
                    <p class="fs-4" id="reservedSeats">{{ $totalReservedSeats }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h5 class="text-warning">Revenus générés</h5>
                    <p class="fs-4" id="totalRevenue">{{ number_format($totalRevenue, 0, ',', ' ') }} Ar</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h5 class="text-primary">Taux d’occupation moyen</h5>
                    <p class="fs-4" id="averageOccupancy">{{ $averageOccupancy }} %</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RECETTES par événement --}}
    <div class="row mt-5">
        <div class="col-md-12">
            <h4 class="fw-bold text-secondary">💰 RECETTES par événement</h4>
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>Désignation</th>
                        <th>Nombre</th>
                        <th>PU</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody id="revenuesTable">
                    @foreach($ticketsByEvent as $event)
                        <tr>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->tickets->count() }}</td>
                            <td>{{ number_format($event->ticket_price ?? 0, 0, ',', ' ') }} Ar</td>
                            <td>{{ number_format(($event->tickets->count()) * ($event->ticket_price ?? 0), 0, ',', ' ') }} Ar</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
        <div class="col-md-12 mb-4">
            <canvas id="revenueByMonthChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 🎟️ Billets par événement
    const ticketsCtx = document.getElementById('ticketsChart');
    const ticketsChart = new Chart(ticketsCtx, {
        type: 'bar',
        data: {
            labels: @json($ticketsByEvent->pluck('title')),
            datasets: [{
                label: 'Billets',
                data: @json($ticketsByEvent->map(fn($e) => $e->tickets->count())),
                backgroundColor: '#3b82f6'
            }]
        },
        options: { responsive: true, plugins: { title: { display: true, text: 'Billets par événement' } } }
    });

    // 🏟️ Événements par lieu
    const venueCtx = document.getElementById('venueChart');
    const venueChart = new Chart(venueCtx, {
        type: 'bar',
        data: {
            labels: @json($eventsByVenue->pluck('name')),
            datasets: [{
                label: 'Nombre d\'événements',
                data: @json($eventsByVenue->pluck('events_count')),
                backgroundColor: '#22c55e'
            }]
        },
        options: { responsive: true, plugins: { title: { display: true, text: 'Événements par lieu' } } }
    });

    // 🧮 Répartition par type d’événement
    const typeCtx = document.getElementById('typeChart');
    const typeChart = new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: @json($eventsByType->pluck('name')),
            datasets: [{
                data: @json($eventsByType->pluck('events_count')),
                backgroundColor: ['#f59e0b','#10b981','#ef4444','#8b5cf6']
            }]
        },
        options: { responsive: true, plugins: { title: { display: true, text: 'Répartition des types d\'événements' } } }
    });

    // 📈 Évolution des revenus par mois
    const revenueCtx = document.getElementById('revenueByMonthChart');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_map(fn($m) => date("F", mktime(0,0,0,$m,1)), array_keys($revenueByMonth->toArray()))) !!},
            datasets: [{
                label: 'Revenus mensuels (Ar)',
                data: {!! json_encode(array_values($revenueByMonth->toArray())) !!},
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,0.2)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#ef4444'
            }]
        },
        options: {
            responsive: true,
            plugins: { title: { display: true, text: 'Évolution des revenus par mois' } },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return value + ' Ar';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
