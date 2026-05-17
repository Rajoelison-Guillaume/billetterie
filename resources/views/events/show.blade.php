@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">{{ $event->title }}</h2>

    <p class="lead text-light">{{ $event->description }}</p>
    <p><strong>Organisateur :</strong> {{ $event->organizer->name }}</p>
    <p><strong>Lieu :</strong> {{ $event->venue->name ?? 'Non défini' }}</p>
    <p><strong>Date :</strong> {{ $event->start_date->format('d/m/Y H:i') }} - {{ $event->end_date->format('d/m/Y H:i') }}</p>
    <p><strong>Prix :</strong> {{ number_format($event->ticket_price, 0, ',', ' ') }} Ar</p>

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

    <form action="{{ route('events.reserve', $event->id) }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-3">
            <label class="form-label text-light">Mode de paiement</label>
            <select name="method" class="form-select" required>
                <option value="mvola">Mvola</option>
                <option value="orange_money">Orange Money</option>
                <option value="airtel_money">Airtel Money</option>
                <option value="cash">Cash</option>
            </select>
        </div>

        @if($event->isCinema())
            <div class="mb-3">
                <label class="form-label text-light">Places sélectionnées</label>
                <input type="text" name="seats" id="seatsInput" class="form-control" readonly required>
            </div>
        @else
            <div class="mb-3">
                <label class="form-label text-light">Quantité de billets</label>
                <input type="number" name="quantity" class="form-control" min="1" value="1" required>
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label text-light">Numéro de téléphone</label>
            <input type="text" name="phone" class="form-control" placeholder="03xxxxxxxx" required>
        </div>

        <button type="submit" class="btn btn-primary">Acheter un billet</button>
    </form>
</div>

@if($event->isCinema())
    <h1 class="mt-5">Disposition des sièges 🎬</h1>
    <div class="screen">ÉCRAN GÉANT</div>
    <div class="cinema" id="cinema"></div>

    <h3 class="mt-4 text-light">Sièges sélectionnés :</h3>
    <ul id="selectedSeats"></ul>
@endif
@endsection

@section('styles')
<style>
    body { background:#111; color:#fff; }
    .cinema { position:relative; width:100%; height:600px; margin-top:30px; }
    .seat {
        appearance:none; -webkit-appearance:none;
        width:22px; height:22px;
        background:crimson; border-radius:50%;
        position:absolute; transition:0.3s; border:2px solid #333;
    }
    .seat:hover { background:gold; cursor:pointer; }
    .seat:checked { background:limegreen; }
    .seat.reserved { background:gray; cursor:not-allowed; }
    .screen {
        width:60%; height:30px; background:silver;
        margin:0 auto; border-radius:5px;
        line-height:30px; font-weight:bold;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const cinema = document.getElementById("cinema");
    const selectedSeatsList = document.getElementById("selectedSeats");
    const seatsInput = document.getElementById("seatsInput");
    let selectedSeats = [];

    // Données envoyées par Laravel
    const seatsData = @json($event->room->seats->map(fn($s) => [
        'id' => $s->id,
        'row_label' => $s->row_label,
        'seat_number' => $s->seat_number
    ]));

    const reservedSeatIds = @json($reservedSeatIds); // liste des seat_id déjà pris

    const niveaux = 10;
    const baseSeats = 30;
    const increment = 2;
    const centerX = window.innerWidth / 2;
    const centerY = 650;
    const arcAngle = 0.35 * 2 * Math.PI;
    const seatSpacing = 1.2;

    let seatIndex = 0;

    for (let n = 0; n < niveaux; n++) {
        const seatsCount = baseSeats + (n * increment);
        const radius = 180 + (n * 45);
        const angleStep = (arcAngle * seatSpacing) / (seatsCount - 1);
        const startAngle = Math.PI/2 - (arcAngle * seatSpacing)/2;

        for (let s = 0; s < seatsCount; s++) {
            if (seatIndex >= seatsData.length) break;

            const seatInfo = seatsData[seatIndex];
            const seat = document.createElement("input");
            seat.type = "checkbox";
            seat.classList.add("seat");
            seat.dataset.seatId = seatInfo.id;
            seat.title = `Row ${seatInfo.row_label} - Seat ${seatInfo.seat_number}`;

            const angle = startAngle + (s * angleStep);
            const x = centerX + radius * Math.cos(angle);
            const y = centerY - radius * Math.sin(angle);

            seat.style.left = (x - 11) + "px";
            seat.style.top = (y - 11) + "px";

            if (reservedSeatIds.includes(seatInfo.id)) {
                seat.disabled = true;
                seat.checked = true;
                seat.classList.add("reserved");
            } else {
                seat.addEventListener("change", () => {
                    const seatId = seat.dataset.seatId;
                    if (seat.checked) {
                        selectedSeats.push(seatId);
                        const li = document.createElement("li");
                        li.textContent = `Seat ${seatInfo.row_label}-${seatInfo.seat_number}`;
                        li.id = "li-" + seatId;
                        selectedSeatsList.appendChild(li);
                    } else {
                        selectedSeats = selectedSeats.filter(id => id !== seatId);
                        document.getElementById("li-" + seatId)?.remove();
                    }
                    seatsInput.value = selectedSeats.join(",");
                });
            }
            cinema.appendChild(seat);
            seatIndex++;
        }
    }
});
</script>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
    </div>
@endif
@endsection
