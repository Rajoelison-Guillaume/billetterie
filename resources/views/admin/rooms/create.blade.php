@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">➕ Ajouter une salle</h2>

<form action="{{ route('admin.rooms.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nom</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Lieu associé</label>
        <select name="venue_id" class="form-select" required>
            <option value="">-- Choisir un lieu --</option>
            @foreach($venues as $venue)
                <option value="{{ $venue->id }}">{{ $venue->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Capacité</label>
        <input type="number" id="capacity" name="capacity" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" name="generate_seats" value="1" class="form-check-input">
        <label class="form-check-label">Générer automatiquement les sièges</label>
    </div>

    <button type="submit" class="btn btn-success">Ajouter</button>
</form>

{{-- Prévisualisation graphique --}}
<h3 class="text-primary mt-4">Disposition des sièges 🎬</h3>
<div class="screen">ÉCRAN GÉANT</div>
<div class="cinema" id="cinema"></div>

<h5 class="mt-4">Sièges sélectionnés :</h5>
<ul id="selectedSeats"></ul>
@endsection

@section('styles')
<style>
.cinema {
  position: relative;
  width: 100%;
  height: 600px;
  margin-top: 30px;
}
.seat {
  appearance: none;
  -webkit-appearance: none;
  width: 22px;
  height: 22px;
  background: crimson;
  border-radius: 50%;
  position: absolute;
  transition: 0.3s;
  border: 2px solid #333;
}
.seat:hover { background: gold; cursor: pointer; }
.seat:checked { background: limegreen; }
.screen {
  width: 60%;
  height: 30px;
  background: silver;
  margin: 0 auto;
  border-radius: 5px;
  line-height: 30px;
  font-weight: bold;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const cinema = document.getElementById("cinema");
    const selectedSeatsList = document.getElementById("selectedSeats");
    let selectedSeats = [];

    function generateArcSeats(capacity) {
        cinema.innerHTML = "";
        const niveaux = 10;
        const baseSeats = 30;
        const increment = 2;
        const centerX = window.innerWidth / 2;
        const centerY = 650;
        const arcAngle = 0.35 * 2 * Math.PI;
        const seatSpacing = 1.2;
        let generated = 0;

        for (let n = 0; n < niveaux; n++) {
          const seatsCount = baseSeats + (n * increment);
          const radius = 180 + (n * 45);
          const angleStep = (arcAngle * seatSpacing) / (seatsCount - 1);
          const startAngle = Math.PI/2 - (arcAngle * seatSpacing)/2;

          for (let s = 0; s < seatsCount; s++) {
            generated++;
            if (generated > capacity) return;
            const seat = document.createElement("input");
            seat.type = "checkbox";
            seat.classList.add("seat");
            seat.id = `seat-${String.fromCharCode(65+n)}-${s+1}`;
            const angle = startAngle + (s * angleStep);
            const x = centerX + radius * Math.cos(angle);
            const y = centerY - radius * Math.sin(angle);
            seat.style.left = (x - 11) + "px";
            seat.style.top = (y - 11) + "px";

            seat.addEventListener("change", () => {
              const seatId = seat.id;
              if (seat.checked) {
                selectedSeats.push(seatId);
                const li = document.createElement("li");
                li.textContent = seatId;
                li.id = "li-" + seatId;
                selectedSeatsList.appendChild(li);
              } else {
                selectedSeats = selectedSeats.filter(id => id !== seatId);
                document.getElementById("li-" + seatId)?.remove();
              }
            });

            cinema.appendChild(seat);
          }
        }
    }

    document.getElementById("capacity").addEventListener("input", function() {
        const cap = parseInt(this.value);
        if (cap > 0) generateArcSeats(cap);
    });
});
</script>
@endsection
