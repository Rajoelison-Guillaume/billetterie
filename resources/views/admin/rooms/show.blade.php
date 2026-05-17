@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">🏢 Détail de la salle : {{ $room->name }}</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Nom :</strong> {{ $room->name }}</p>
            <p><strong>Lieu :</strong> {{ $room->venue?->name ?? '-' }}</p>
            <p><strong>Capacité :</strong> {{ $room->capacity }}</p>
            <p><strong>Description :</strong> {{ $room->description ?? 'Aucune' }}</p>
            <p><strong>Créé le :</strong> {{ $room->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <h3 class="text-primary mt-4">Disposition des sièges 🎬</h3>
    <div class="screen">ÉCRAN GÉANT</div>
    <div class="cinema" id="cinema"></div>

    <h5 class="mt-4">Sièges sélectionnés :</h5>
    <ul id="selectedSeats"></ul>

    <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary mt-3">⬅️ Retour</a>
</div>
@endsection

@section('styles')
<style>
.cinema { position: relative; width: 100%; height: 600px; margin-top: 30px; }
.seat { appearance: none; -webkit-appearance: none; width: 22px; height: 22px; background: crimson; border-radius: 50%; position: absolute; transition: 0.3s; border: 2px solid #333; }
.seat:hover { background: gold; cursor: pointer; }
.seat:checked { background: limegreen; }
.screen { width: 60%; height: 30px; background: silver; margin: 0 auto; border-radius: 5px; line-height: 30px; font-weight: bold; }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const cinema = document.getElementById("cinema");
    const selectedSeatsList = document.getElementById("selectedSeats");
    let selectedSeats = [];
    const niveaux = 10, baseSeats = 30, increment = 2;
    const centerX = window.innerWidth / 2, centerY = 650;
    const arcAngle = 0.35 * 2 * Math.PI, seatSpacing = 1.2;
    const capacity = {{ $room->capacity }};
    let generated = 0;

    for (let n = 0; n < niveaux; n++) {
      const seatsCount = baseSeats + (n * increment);
      const radius = 180 + (n * 45);
      const angleStep = (arcAngle * seatSpacing) / (seatsCount - 1);
      const startAngle = Math.PI/2 - (arcAngle * seatSpacing)/2;
      for (let s = 0; s < seatsCount; s++) {
        generated++; if (generated > capacity) return;
        const seat = document.createElement("input");
        seat.type = "checkbox"; seat.classList.add("seat");
        seat.id = `seat-${String.fromCharCode(65+n)}-${s+1}`;
        const angle = startAngle + (s * angleStep);
        const x = centerX + radius * Math.cos(angle);
        const y = centerY - radius * Math.sin(angle);
        seat.style.left = (x - 11) + "px"; seat.style.top = (y - 11) + "px";
        seat.addEventListener("change", () => {
          const seatId = seat.id;
          if (seat.checked) {
            selectedSeats.push(seatId);
            const li = document.createElement("li"); li.textContent = seatId; li.id = "li-" + seatId;
            selectedSeatsList.appendChild(li);
          } else {
            selectedSeats = selectedSeats.filter(id => id !== seatId);
            document.getElementById("li-" + seatId)?.remove();
          }
        });
        cinema.appendChild(seat);
      }
    }
});
</script>
@endsection
