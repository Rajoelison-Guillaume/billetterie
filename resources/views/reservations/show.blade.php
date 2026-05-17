@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">📄 Réservation #{{ $reservation->id }}</h2>

    {{-- Messages flash --}}
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
    <div class="card bg-dark text-light mb-4">
        <div class="card-body">
            <p class="lead">{{ $reservation->event->title }}</p>
            <p><strong>Organisateur :</strong> {{ $reservation->event->organizer->name ?? '-' }}</p>
            <p><strong>Lieu :</strong> {{ $reservation->event->venue->name ?? 'Non défini' }}</p>
            <p><strong>Date :</strong> {{ $reservation->event->start_date->format('d/m/Y H:i') }}</p>
            <p><strong>Statut :</strong> 
                <span class="badge bg-{{ $reservation->status === 'confirmée' ? 'success' : 'danger' }}">
                    {{ ucfirst($reservation->status) }}
                </span>
            </p>
        </div>
    </div>

    {{-- Billets liés --}}
    <h4 class="text-light mt-4">🎫 Billets associés</h4>
    <div class="row">
        @foreach($reservation->tickets as $ticket)
            <div class="col-md-6 mb-4">
                <div class="card bg-secondary text-light h-100">
                    <div class="card-body">
                        <p><strong>Billet #{{ $ticket->id }}</strong></p>
                        <p><strong>Prix :</strong> {{ number_format($ticket->price, 0, ',', ' ') }} Ar</p>
                        @if($ticket->seat)
                            <p><strong>Siège :</strong> 🪑 {{ $ticket->seat->label() }}</p>
                        @else
                            <p><strong>Type :</strong> Billet libre (sans siège)</p>
                        @endif
                        <p><strong>Statut :</strong> 
                            <span class="badge bg-{{ $ticket->status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </p>
                        <p><small>QR Code : {{ $ticket->qr_code }}</small></p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Disposition des sièges si cinéma --}}
    @if($reservation->event->isCinema())
        <div class="mt-4">
            <h4 class="text-info">🪑 Plan des sièges réservés</h4>
            <div class="cinema" id="cinemaPreview" style="position:relative; height:400px; background:#222; border-radius:12px; padding:20px;"></div>
        </div>
    @endif

    <a href="{{ route('reservations.index') }}" class="btn btn-secondary mt-3">⬅️ Retour à mes réservations</a>
</div>
@endsection

@push('scripts')
@if($reservation->event->isCinema())
<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById("cinemaPreview");
    const reservedSeats = @json($reservedSeats); // ex: ["seat-A-12", "seat-B-5"]

    // Génère des petits points pour visualiser les sièges réservés (simulation simple)
    reservedSeats.forEach(seatLabel => {
        const dot = document.createElement("div");
        dot.textContent = seatLabel.replace('seat-', '');
        dot.style.display = "inline-block";
        dot.style.margin = "5px";
        dot.style.padding = "4px 8px";
        dot.style.background = "#28a745";
        dot.style.color = "white";
        dot.style.borderRadius = "12px";
        dot.style.fontSize = "12px";
        container.appendChild(dot);
    });
    if(reservedSeats.length === 0) {
        container.innerHTML = "<p class='text-muted'>Aucun siège réservé affiché.</p>";
    }
});
</script>
@endif
@endpush