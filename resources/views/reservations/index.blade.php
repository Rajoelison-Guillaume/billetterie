@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-neon mb-4">📋 Mes réservations</h2>

    @foreach($reservations as $reservation)
        <div class="card mb-4 border-0 shadow-lg bg-dark text-light">
            <div class="card-header bg-gradient text-light fw-bold">
                Réservation #{{ $reservation->id }} — {{ $reservation->event->title }}
            </div>
            <div class="card-body">
                <p><strong>Date :</strong> {{ $reservation->event->start_date->format('d/m/Y H:i') }}</p>
                <p><strong>Lieu :</strong> {{ $reservation->event->venue->name ?? 'Non défini' }}</p>
                <p><strong>Statut :</strong> 
                    <span class="badge bg-{{ $reservation->status === 'confirmée' ? 'success' : 'danger' }}">
                        {{ ucfirst($reservation->status) }}
                    </span>
                </p>

                {{-- Si événement cinéma : afficher les sièges réservés --}}
                @if($reservation->event->isCinema())
                    <h5 class="mt-4 text-info">🪑 Mes sièges réservés</h5>
                    @php
                        $seatsList = $reservation->reservationSeats->map(fn($rs) => $rs->seat->label())->toArray();
                    @endphp
                    @if(count($seatsList))
                        <ul class="list-group">
                            @foreach($seatsList as $seatLabel)
                                <li class="list-group-item bg-dark text-light fw-bold text-neon">{{ $seatLabel }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-warning">Aucun siège réservé.</p>
                    @endif
                @else
                    <p><strong>Détails :</strong> Réservation simple sans choix de place ({{ $reservation->quantity }} billet(s)).</p>
                @endif

                <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-sm btn-outline-light mt-3">Voir le détail</a>
            </div>
        </div>
    @endforeach
</div>

<style>
    .text-neon {
        color: #2665a0;
        text-shadow: 0 0 8px #00f0ff;
    }
    .card-header {
        background: linear-gradient(90deg, #0d47a1, #1976d2);
        border-bottom: 2px solid #00f0ff;
    }
</style>
@endsection