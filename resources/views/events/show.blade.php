@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">{{ $event->title }}</h2>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Infos de l'événement --}}
    <p class="lead text-light">{{ $event->description }}</p>
    <p><strong>Organisateur :</strong> {{ $event->organizer->name }}</p>
    <p><strong>Lieu :</strong> {{ $event->venue->name ?? 'Non défini' }}</p>
    <p><strong>Date :</strong> {{ $event->start_date->format('d/m/Y') }} - {{ $event->end_date->format('d/m/Y') }}</p>
    <p><strong>Prix :</strong> {{ number_format($event->ticket_price, 0, ',', ' ') }} Ar</p>

    {{-- Trailer vidéo --}}
    @if($event->trailer_url)
        @php
            $embedUrl = \Illuminate\Support\Str::contains($event->trailer_url, 'youtube.com/watch')
                ? str_replace('watch?v=', 'embed/', $event->trailer_url)
                : $event->trailer_url;
        @endphp
        <div class="ratio ratio-16x9 mb-4">
            <iframe src="{{ $embedUrl }}" frameborder="0" allowfullscreen></iframe>
        </div>
    @endif

    {{-- Séances cinéma --}}
    @if($event->isCinema())
        <h4 class="text-light mt-4">🎬 Séances disponibles</h4>
        @forelse($event->showtimes as $showtime)
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $showtime->start_at->format('d/m/Y H:i') }}</strong> — {{ $showtime->room->name }}
                    </div>
                    <a href="{{ route('showtimes.show', $showtime->id) }}" class="btn btn-sm btn-info text-dark">Réserver</a>
                </div>
            </div>
        @empty
            <p class="text-warning">Aucune séance disponible pour cet événement.</p>
        @endforelse
    @endif

    {{-- Formulaire de réservation --}}
    <form action="{{ route('events.reserve', $event->id) }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-3" id="providerField">
            <label class="form-label text-light">Mode de paiement</label>
            <select name="method" class="form-select" required>
                <option value="mobile_money">mobile_money</option>
                <option value="cash">Cash</option>
            </select>
        </div>

        @if($event->isCinema())
            <div class="mb-3">
                <label class="form-label text-light">Numéro de place</label>
                <input type="text" name="seats" class="form-control" value="{{ request('seats') }}" readonly required>
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label text-light">Numéro de téléphone</label>
            <input type="text" name="phone" class="form-control" placeholder="03xxxxxxxx" required>
        </div>

        <button type="submit" class="btn btn-primary">Acheter un billet</button>
    </form>
</div>

{{-- Disposition des sièges uniquement pour cinéma --}}
@if($event->isCinema())
    <h1 class="mt-5">Disposition des sièges 🎬</h1>
    <div class="screen">ÉCRAN GÉANT</div>
    <div class="cinema" id="cinema"></div>
    <div class="legend mt-4">
    <h5 class="text-light">Légende des sièges</h5>
    <ul class="list-unstyled d-flex gap-4">
        <li>
            <span class="legend-box free"></span> Libre
        </li>
        <li>
            <span class="legend-box selected"></span> Sélectionné
        </li>
        <li>
            <span class="legend-box reserved"></span> Occupé
        </li>
    </ul>
</div>

    <div id="info" class="mt-4">
        <strong>Sièges sélectionnés :</strong>
        <ul id="selectedSeats"></ul>
    </div>
    <button id="validateSeats" class="btn btn-success mt-3">Valider les sièges</button>

    @if($reservedSeats->count())
    <div class="mt-4">
        <h4 class="text-light">🪑 Places déjà réservées</h4>

        <ul>
            @foreach($reservedSeats as $seat)
                <li>{{ $seat }}</li>
            @endforeach
        </ul>
    </div>
@else
    <p class="text-success mt-4">Aucune place réservée.</p>
@endif
<style>
.legend-box {
  display: inline-block;
  width: 20px;
  height: 20px;
  border-radius: 4px;
  margin-right: 8px;
  border: 2px solid #333;
}

.legend-box.free { background: limegreen; }
.legend-box.selected { background: crimson; }
.legend-box.reserved { background: gray; }


.cinema { 
  position: relative; 
  width: 100%; 
  min-height: 600px; 
  margin-top: 30px; 
}

.seat { 
  width: 22px; 
  height: 22px; 
  border-radius: 50%; 
  position: absolute; 
  border: 2px solid #333; 
  appearance: none; /* supprime le style natif */ 
  -webkit-appearance: none; 
  cursor: pointer;
}

.seat.free { background: limegreen; }     /* disponible */
.seat.selected { background: crimson; }   /* sélectionné */
.seat.reserved { background: gray; }      /* occupé */
.seat:hover:not(:disabled) { background: gold; cursor: pointer; }

.screen { 
  width: 60%; 
  height: 30px; 
  background: silver; 
  margin: 0 auto; 
  border-radius: 5px; 
  text-align: center; 
  line-height: 30px; 
  font-weight: bold; 
}

</style>

    <script>
  const cinema = document.getElementById("cinema");
  const selectedSeatsList = document.getElementById("selectedSeats");
  const validateBtn = document.getElementById("validateSeats");
  const seatsInput = document.querySelector("input[name='seats']");
  let selectedSeats = [];

  // Liste des sièges réservés envoyée par Laravel
  const reservedSeats = @json($reservedSeats);

  function updateList(seat) {
    const seatId = seat.id;
    if (seat.checked) {
      if (!selectedSeats.includes(seatId)) {
        selectedSeats.push(seatId);
        const li = document.createElement("li");
        li.textContent = seatId;
        li.id = "li-" + seatId;
        selectedSeatsList.appendChild(li);
      }
    } else {
      selectedSeats = selectedSeats.filter(id => id !== seatId);
      const liToRemove = document.getElementById("li-" + seatId);
      if (liToRemove) selectedSeatsList.removeChild(liToRemove);
    }
  }

  validateBtn.addEventListener("click", () => {
    if (selectedSeats.length === 0) {
      alert("Veuillez sélectionner au moins un siège !");
      return;
    }
    seatsInput.value = selectedSeats.join(",");
  });

  const niveaux = 10, baseSeats = 30, increment = 2;
  const centerX = cinema.offsetWidth / 2, centerY = 600;
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

      const angle = startAngle + (s * angleStep);
      const x = centerX + radius * Math.cos(angle);
      const y = centerY - radius * Math.sin(angle);

      seat.style.left = (x - 11) + "px";
      seat.style.top = (y - 11) + "px";

      // ✅ Si le siège est réservé, on le coche et on le bloque
      if (reservedSeats.includes(seat.id)) {
        seat.checked = true;
        seat.disabled = true;
        seat.classList.add("reserved");
      } else {
        seat.classList.add("free");
        seat.addEventListener("change", () => {
          seat.classList.toggle("selected", seat.checked);
          updateList(seat);
        });
      }

      cinema.appendChild(seat);
    }
  }
</script>



@endif
@endsection
