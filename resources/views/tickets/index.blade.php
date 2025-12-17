@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">🎟️ Mes billets</h2>
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

    @forelse($tickets as $ticket)
        <div class="card mb-3 bg-dark text-light">
            <div class="card-header">
                Billet #{{ $ticket->id }} — {{ $ticket->event->title }}
                <span class="badge bg-{{ $ticket->status === 'paid' ? 'success' : 'warning' }}">
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>
            <div class="card-body">
                <p><strong>Événement :</strong> {{ $ticket->event->title }}</p>
                <p><strong>Date :</strong> {{ $ticket->event->start_date->format('d/m/Y') }}</p>
                <p><strong>Lieu :</strong> {{ $ticket->event->venue->name ?? '-' }}</p>

                @if($ticket->showtime)
                    <p><strong>Séance :</strong> {{ $ticket->showtime->start_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Salle :</strong> {{ $ticket->showtime->room->name ?? '-' }}</p>
                @endif

                @if($ticket->seat)
                    <p><strong>Place :</strong> {{ $ticket->seat->row_label }}{{ $ticket->seat->seat_number }}</p>
                @endif

                <p><strong>Prix :</strong> {{ number_format($ticket->price, 0, ',', ' ') }} Ar</p>
                <p><strong>QR Code :</strong> 🔐 {{ $ticket->qr_code }}</p>
                <p><strong>Acheté le :</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    @empty
        <p class="text-muted">Vous n’avez encore aucun billet.</p>
    @endforelse
</div>
@endsection
