@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">📋 Réservations de sièges</h2>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
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

    @forelse($reservations as $reservation)
        <div class="card mb-3">
            <div class="card-header">
                Réservation #{{ $reservation->id }} — {{ $reservation->showtime->event->title }}
            </div>
            <div class="card-body">
                <p><strong>Séance :</strong> {{ $reservation->showtime->start_at->format('d/m/Y H:i') }}</p>
                <p><strong>Salle :</strong> {{ $reservation->showtime->room->name ?? '-' }}</p>
                <p><strong>Place :</strong> {{ $reservation->seat->row_label }}{{ $reservation->seat->seat_number }}</p>
                <p><strong>Billet :</strong> #{{ $reservation->ticket->id }} — {{ $reservation->ticket->qr_code }}</p>
                <p><strong>Réservé le :</strong> {{ $reservation->reserved_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    @empty
        <p class="text-muted">Aucune réservation enregistrée.</p>
    @endforelse
</div>
@endsection
