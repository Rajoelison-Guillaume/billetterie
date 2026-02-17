@extends('layouts.admin')

@section('content')
<h1 class="fw-bold text-primary mb-4">📄 Détail de la réservation #{{ $reservation->id }}</h1>

<div class="card mb-4 border-0 shadow-lg bg-dark text-light">
    <div class="card-body">
        <p><strong>Utilisateur :</strong> {{ $reservation->user?->name ?? '—' }}</p>
        <p><strong>Événement :</strong> {{ $reservation->event?->title ?? '—' }}</p>
        <p><strong>Lieu :</strong> {{ $reservation->event?->venue?->name ?? 'Non défini' }}</p>
        <p><strong>Date de réservation :</strong> {{ $reservation->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Statut :</strong> 
            <span class="badge bg-{{ $reservation->status === 'confirmée' ? 'success' : 'secondary' }}">
                {{ ucfirst($reservation->status) }}
            </span>
        </p>

        {{-- Liste des sièges occupés pour l’événement --}}
        @if($reservation->event && $reservation->event->isCinema())
            <h4 class="text-info mt-4">🔒 Sièges occupés pour cet événement</h4>
            <table class="table table-hover table-dark table-striped align-middle shadow-sm">
                <thead class="table-primary text-dark">
                    <tr>
                        <th>Siège</th>
                        <th>Utilisateur</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservedSeats as $resSeat)
                        <tr>
                            <td>{{ "seat-{$resSeat->seat->row_label}-{$resSeat->seat->seat_number}" }}</td>
                            <td>{{ $resSeat->ticket->order->user->name ?? 'Inconnu' }}</td>
                            <td><span class="badge bg-secondary">Occupé</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p><strong>Détails :</strong> Événement sans choix de place.</p>
        @endif
    </div>
</div>

<a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">⬅ Retour à la liste</a>
@endsection
