@extends('layouts.admin')

@section('content')
<h1>Détail de la réservation #{{ $reservation->id }}</h1>

<div class="card mb-4">
    <div class="card-body">
        <p><strong>Utilisateur :</strong> {{ $reservation->user?->name ?? '—' }}</p>
        <p><strong>Événement :</strong> {{ $reservation->event?->title ?? '—' }}</p>
        <p><strong>Date de réservation :</strong> {{ $reservation->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Statut :</strong> 
            <span class="badge bg-{{ $reservation->status === 'confirmée' ? 'success' : 'secondary' }}">
                {{ ucfirst($reservation->status) }}
            </span>
        </p>

        <h5 class="mt-4">Disposition des sièges 🎬</h5>
        <div class="legend mb-3">
            <span class="legend-item free"></span> Libre (vert)
            <span class="legend-item reserved"></span> Occupé (rouge)
        </div>

        <div class="screen">ÉCRAN GÉANT</div>
        <div class="cinema" id="cinema-{{ $reservation->id }}"></div>

        <p class="mt-4"><strong>Sièges réservés :</strong></p>
        @if(!empty($reservation->seats))
            <ul>
                @foreach(explode(',', $reservation->seats) as $seat)
                    <li>{{ $seat }}</li>
                @endforeach
            </ul>
        @else
            <p>Aucun siège réservé.</p>
        @endif
    </div>
</div>

<a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">⬅ Retour à la liste</a>

{{-- Script pour générer le plan de salle --}}
<script>
    (function() {
        const cinema = document.getElementById("cinema-{{ $reservation->id }}");
        const reservedSeats = @json(explode(',', $reservation->seats ?? ''));

        const niveaux = 10, baseSeats = 30, increment = 2;
        const centerX = cinema.offsetWidth / 2, centerY = 600;
        const arcAngle = 0.35 * 2 * Math.PI, seatSpacing = 1.2;

        for (let n = 0; n < niveaux; n++) {
            const seatsCount = baseSeats + (n * increment);
            const radius = 180 + (n * 45);
            const angleStep = (arcAngle * seatSpacing) / (seatsCount - 1);
            const startAngle = Math.PI/2 - (arcAngle * seatSpacing)/2;

            for (let s = 0; s < seatsCount; s++) {
                const seat = document.createElement("div");
                seat.classList.add("seat");
                seat.id = `seat-${n + 1}-${s + 1}`;

                const angle = startAngle + (s * angleStep);
                const x = centerX + radius * Math.cos(angle);
                const y = centerY - radius * Math.sin(angle);

                seat.style.left = (x - 11) + "px";
                seat.style.top = (y - 11) + "px";

                // ✅ Si le siège est réservé → rouge
                if (reservedSeats.includes(seat.id)) {
                    seat.classList.add("reserved");
                } else {
                    seat.classList.add("free");
                }

                cinema.appendChild(seat);
            }
        }
    })();
</script>

<style>
    .cinema { position: relative; width: 100%; height: 600px; margin-top: 30px; }
    .seat { width: 22px; height: 22px; border-radius: 50%; position: absolute; border: 2px solid #333; }
    .seat.free { background: limegreen; }
    .seat.reserved { background: crimson; }
    .screen { width: 60%; height: 30px; background: silver; margin: 0 auto; border-radius: 5px; text-align: center; line-height: 30px; font-weight: bold; }
    .legend { display: flex; gap: 20px; align-items: center; }
    .legend-item { display: inline-block; width: 22px; height: 22px; border-radius: 50%; border: 2px solid #333; margin-right: 5px; }
    .legend-item.free { background: limegreen; }
    .legend-item.reserved { background: crimson; }
</style>
@endsection
