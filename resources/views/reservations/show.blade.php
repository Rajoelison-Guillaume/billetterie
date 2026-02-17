@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">📄 Réservation #{{ $reservation->id }}</h2>

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
    <p class="lead text-light">{{ $reservation->event->title }}</p>
    <p><strong>Organisateur :</strong> {{ $reservation->event->organizer->name ?? '-' }}</p>
    <p><strong>Lieu :</strong> {{ $reservation->event->venue->name ?? 'Non défini' }}</p>
    <p><strong>Date :</strong> {{ $reservation->event->start_date->format('d/m/Y') }}</p>
    <p><strong>Statut :</strong> 
        <span class="badge bg-{{ $reservation->status === 'confirmée' ? 'success' : 'danger' }}">
            {{ ucfirst($reservation->status) }}
        </span>
    </p>

    {{-- Billets liés --}}
    <h4 class="text-light mt-4">🎫 Billets réservés</h4>
    <table class="table table-striped table-dark">
        <thead>
            <tr>
                <th>Événement</th>
                <th>Siège</th>
                <th>Prix</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservation->tickets as $ticket)
                <tr>
                    <td>{{ $ticket->event->title }}</td>
                    <td>
                        @if($ticket->seat)
                            🪑 {{ $ticket->seat->row_label }}{{ $ticket->seat->seat_number }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ number_format($ticket->price, 0, ',', ' ') }} Ar</td>
                    <td>
                        <span class="badge bg-{{ $ticket->status === 'confirmée' ? 'success' : 'secondary' }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Disposition des sièges si cinéma --}}
    @if($reservation->event->isCinema())
        <x-cinema-seats 
            :event="$reservation->event" 
            :mySeats="$reservation->tickets->map(fn($t) => $t->seat ? 'seat-'.$t->seat->row_label.'-'.$t->seat->seat_number : null)->filter()->values()" 
            :reservedSeats="$reservedSeats" 
        />
    @endif
</div>
@endsection
