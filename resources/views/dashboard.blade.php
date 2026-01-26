@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">🎟️ Tableau de bord Client</h2>

    {{-- Compteurs personnels --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-info text-white text-center">
                <div class="card-body">
                    <h5>Billets achetés</h5>
                    <p class="fs-4">{{ $totalTickets }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white text-center">
                <div class="card-body">
                    <h5>Réservations effectuées</h5>
                    <p class="fs-4">{{ $totalReservations }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Graphiques --}}
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <canvas id="reservationsChart"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="typesChart"></canvas>
        </div>
        <div class="col-md-12 mb-4">
            <canvas id="ticketsByMonthChart"></canvas>
        </div>
    </div>

    {{-- Événements disponibles --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">📅 Événements disponibles</div>
        <div class="card-body">
            <ul class="list-group">
                @forelse($availableEvents as $event)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $event->title }} — {{ $event->start_date->format('d/m/Y H:i') }}</span>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-primary">Voir</a>
                    </li>
                @empty
                    <li class="list-group-item">Aucun événement disponible.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Réservations du client --}}
    <div class="card">
        <div class="card-header fw-bold">🧾 Vos réservations</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Événement</th>
                        <th>Date</th>
                        <th>Quantité</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->event->title }}</td>
                            <td>{{ $reservation->event->start_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $reservation->quantity }}</td>
                            <td>
                                <span class="badge bg-{{ $reservation->status === 'confirmée' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Aucune réservation trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 📊 Réservations par mois
    const reservationsCtx = document.getElementById('reservationsChart');
    new Chart(reservationsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($reservationsByMonth->toArray())) !!},
            datasets: [{
                label: 'Réservations par mois',
                data: {!! json_encode(array_values($reservationsByMonth->toArray())) !!},
                backgroundColor: '#3b82f6'
            }]
        },
        options: {
            responsive: true,
            plugins: { title: { display: true, text: 'Réservations par mois' } }
        }
    });

    // 🍩 Répartition des types d’événements
    const typesCtx = document.getElementById('typesChart');
    new Chart(typesCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($eventTypes->toArray())) !!},
            datasets: [{
                data: {!! json_encode(array_values($eventTypes->toArray())) !!},
                backgroundColor: ['#f59e0b','#10b981','#ef4444','#8b5cf6']
            }]
        },
        options: {
            responsive: true,
            plugins: { title: { display: true, text: 'Répartition des types d’événements' } }
        }
    });

    // 📈 Évolution des billets achetés par mois
    const ticketsCtx = document.getElementById('ticketsByMonthChart');
    new Chart(ticketsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($ticketsByMonth->toArray())) !!},
            datasets: [{
                label: 'Billets achetés par mois',
                data: {!! json_encode(array_values($ticketsByMonth->toArray())) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.2)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#10b981'
            },{
                label: 'Réservations par mois',
                data: {!! json_encode(array_values($reservationsByMonth->toArray())) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.2)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#3b82f6'
            }]
        },
        options: {
            responsive: true,
            plugins: { title: { display: true, text: 'Comparaison billets achetés vs réservations' } },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return value + ' billets';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
