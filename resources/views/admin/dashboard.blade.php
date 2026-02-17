    @extends('layouts.admin')

    @section('content')
    <div class="container-fluid py-4">
        <h2 class="fw-bold text-primary mb-4">📊 Tableau de bord Admin</h2>

        {{-- Compteurs globaux --}}
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Événements</h5>
                        <p class="dashboard-number">{{ $eventsCount }}</p>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-info">Gérer</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Billets</h5>
                        <p class="dashboard-number">{{ $ticketsCount }}</p>
                        <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-sm btn-info">Voir</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Commandes</h5>
                        <p class="dashboard-number">{{ $ordersCount }}</p>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-info">Voir</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Réservations</h5>
                        <p class="dashboard-number">{{ $reservationsCount }}</p>
                        <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-info">Voir</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistiques cinéma --}}
        <div class="row mt-4 g-4">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <h5 class="text-success">Places réservées</h5>
                        <p class="dashboard-number-neon">{{ $totalReservedSeats }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card text-center border-warning">
                    <div class="card-body">
                        <h5 class="text-warning">Revenus générés</h5>
                        <p class="dashboard-number-neon">{{ number_format($totalRevenue, 0, ',', ' ') }} Ar</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card text-center border-primary">
                    <div class="card-body">
                        <h5 class="text-primary">Taux d’occupation moyen</h5>
                        <p class="dashboard-number-neon">{{ $averageOccupancy }} %</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graphiques dynamiques --}}
        <div class="row mt-5 g-4">
            <div class="col-12">
                <canvas id="revenueByMonthChart"></canvas>
            </div>
            <div class="col-lg-6 col-12">
                <canvas id="ticketsChart"></canvas>
            </div>
            <div class="col-lg-6 col-12">
                <canvas id="venueChart"></canvas>
            </div>
            <div class="col-lg-6 col-12">
                <canvas id="typeChart"></canvas>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 🎟️ Billets par événement
        new Chart(document.getElementById('ticketsChart'), {
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
                plugins: { 
                    title: { display: true, text: 'Billets par événement', color:'#e2e8f0' },
                    legend: { labels: { color:'#e2e8f0' } }
                },
                scales: {
                    x: { ticks: { color:'#e2e8f0' } },
                    y: { ticks: { color:'#e2e8f0' } }
                }
            }
        });

        // 🏟️ Événements par lieu
        new Chart(document.getElementById('venueChart'), {
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
                plugins: { title: { display: true, text: 'Événements par lieu', color:'#e2e8f0' } },
                scales: {
                    x: { ticks: { color:'#e2e8f0' } },
                    y: { ticks: { color:'#e2e8f0' } }
                }
            }
        });

        // 🧮 Répartition par type d’événement
        new Chart(document.getElementById('typeChart'), {
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
                plugins: { 
                    title: { display: true, text: 'Répartition des types d\'événements', color:'#e2e8f0' },
                    legend: { labels: { color:'#e2e8f0' } }
                }
            }
        });

        // 📈 Revenus mensuels
        new Chart(document.getElementById('revenueByMonthChart'), {
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
                plugins: { title: { display: true, text: 'Évolution des revenus par mois', color:'#e2e8f0' } },
                scales: {
                    x: { ticks: { color:'#e2e8f0' } },
                    y: { ticks: { color:'#e2e8f0', callback: value => value + ' Ar' } }
                }
            }
        });
    </script>
    @endsection
