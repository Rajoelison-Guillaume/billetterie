@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">
        📄 Détails de la réservation #{{ $reservation->id }}
    </h2>

    <div class="card mb-4">
        <div class="card-header">
            Événement : {{ $reservation->event->title }}
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
                    <span class="legend-item selected"></span> Sélectionné (rouge)
                </div>

                <h5 class="mt-4">Disposition des sièges 🎬</h5>
                <div class="screen">ÉCRAN GÉANT</div>
                <div class="cinema" id="cinema-{{ $reservation->id }}"></div>

                <div id="info-{{ $reservation->id }}" class="mt-4">
                    <strong>Sièges réservés :</strong>
                    <ul id="selectedSeats-{{ $reservation->id }}">
                        @if($reservation->seats)
                            @foreach(explode(',', $reservation->seats) as $seat)
                                <li>{{ $seat }}</li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            @else
                <p><strong>Détails :</strong> Réservation simple sans choix de place.</p>
            @endif
        </div>
    </div>
</div>

<style>
    .cinema { position: relative; width: 100%; height: 600px; margin-top: 30px; }
    .seat { width: 32px; height: 32px; border-radius: 50%; position: absolute; border: 2px solid #333; }
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
