@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">📋 Mes réservations</h2>

    @foreach($reservations as $reservation)
        <div class="card mb-4">
            <div class="card-header">
                Réservation #{{ $reservation->id }} — {{ $reservation->event->title }}
            </div>
            <div class="card-body">
                <p><strong>Date :</strong> {{ $reservation->event->start_date->format('d/m/Y') }}</p>
                <p><strong>Lieu :</strong> {{ $reservation->event->venue->name ?? 'Non défini' }}</p>
                <p><strong>Statut :</strong> 
                    <span class="badge bg-{{ $reservation->status === 'confirmée' ? 'success' : 'danger' }}">
                        {{ ucfirst($reservation->status) }}
                    </span>
                </p>

                @if($reservation->event->isCinema())
                    <div class="legend mb-3">
                        <span class="legend-item free"></span> Libre (vert)
                        <span class="legend-item reserved"></span> Occupé (gris)
                        <span class="legend-item selected"></span> Mes sièges (rouge)
                    </div>

                    <h5 class="mt-4">Disposition des sièges 🎬</h5>
                    <div class="screen">ÉCRAN GÉANT</div>
                    <div class="cinema" id="cinema-{{ $reservation->id }}"></div>

                    <div id="info-{{ $reservation->id }}" class="mt-4">
                        <strong>Mes sièges réservés :</strong>
                        <ul>
                            @if($reservation->seats)
                                @foreach(explode(',', $reservation->seats) as $seat)
                                    <li>{{ $seat }}</li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    {{-- Script pour générer les sièges --}}
                    <script>
                        (function() {
                            const cinema = document.getElementById("cinema-{{ $reservation->id }}");

                            // 🔴 Mes sièges réservés (format texte)
                            const mySeats = @json(explode(',', $reservation->seats ?? ''));

                            // ⚪️ Autres sièges réservés (IDs numériques venant du contrôleur)
                            const allReservedSeats = @json($reservedSeatsByEvent[$reservation->event_id] ?? []);

                            const niveaux = 10, baseSeats = 30, increment = 2;
                            const centerY = 600;
                            const centerX = cinema.offsetWidth / 2;
                            const arcAngle = 0.35 * 2 * Math.PI, seatSpacing = 1.2;

                            for (let n = 0; n < niveaux; n++) {
                                const seatsCount = baseSeats + (n * increment);
                                const radius = 180 + (n * 45);
                                const angleStep = (arcAngle * seatSpacing) / (seatsCount - 1);
                                const startAngle = Math.PI/2 - (arcAngle * seatSpacing)/2;

                                for (let s = 0; s < seatsCount; s++) {
                                    const seat = document.createElement("input");
                                    seat.type = "checkbox";
                                    seat.classList.add("seat");
                                    seat.id = `seat-${n + 1}-${s + 1}`;
                                    seat.disabled = true; // 🔒 lecture seule

                                    // ⚡ stocker l'ID numérique correspondant
                                    seat.dataset.dbid = s + 1;

                                    const angle = startAngle + (s * angleStep);
                                    const x = centerX + radius * Math.cos(angle);
                                    const y = centerY - radius * Math.sin(angle);

                                    seat.style.left = (x - 11) + "px";
                                    seat.style.top = (y - 11) + "px";

                                    if (mySeats.includes(seat.id)) {
                                        seat.checked = true;
                                        seat.classList.add("selected"); // 🔴 mes sièges
                                    } else if (allReservedSeats.includes(parseInt(seat.dataset.dbid))) {
                                        seat.checked = true;
                                        seat.classList.add("reserved"); // ⚪️ autres réservations
                                    } else {
                                        seat.classList.add("free"); // 🟢 libre
                                    }

                                    cinema.appendChild(seat);
                                }
                            }
                        })();
                    </script>
                @else
                    <p><strong>Détails :</strong> Réservation simple sans choix de place.</p>
                @endif
            </div>
        </div>
    @endforeach
</div>

<style>
    .cinema { position: relative; width: 100%; height: 600px; margin-top: 30px; }
    .seat { width: 22px; height: 22px; border-radius: 50%; position: absolute; border: 2px solid #333; appearance: none; -webkit-appearance: none; }
    .seat.free { background: limegreen; }
    .seat.selected { background: crimson; }
    .seat.reserved { background: gray; }
    .screen { width: 60%; height: 30px; background: silver; margin: 0 auto; border-radius: 5px; text-align: center; line-height: 30px; font-weight: bold; }
    .legend { display: flex; gap: 20px; align-items: center; }
    .legend-item { display: inline-block; width: 22px; height: 22px; border-radius: 50%; border: 2px solid #333; margin-right: 5px; }
    .legend-item.free { background: limegreen; }
    .legend-item.reserved { background: gray; }
    .legend-item.selected { background: crimson; }
</style>
@endsection
