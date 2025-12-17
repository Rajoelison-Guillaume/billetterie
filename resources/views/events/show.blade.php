@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">{{ $event->title }}</h2>
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

    <p class="lead text-light">{{ $event->description }}</p>
    <p><strong>Organisateur :</strong> {{ $event->organizer->name }}</p>
    <p><strong>Lieu :</strong> {{ $event->venue->name ?? 'Non défini' }}</p>
    <p><strong>Date :</strong> {{ $event->start_date->format('d/m/Y') }} - {{ $event->end_date->format('d/m/Y') }}</p>
    <p><strong>Prix :</strong> {{ number_format($event->ticket_price, 0, ',', ' ') }} Ar</p>

    @if($event->isCinema())
        <h4 class="text-light mt-4">🎬 Séances disponibles</h4>
        <ul class="list-group mb-4">
            @foreach($event->showtimes as $showtime)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $showtime->start_at->format('d/m/Y H:i') }} — {{ $showtime->room->name }}
                    <a href="{{ route('showtimes.show', $showtime->id) }}" class="btn btn-sm btn-info text-dark">Réserver</a>
                </li>
            @endforeach
        </ul>
    @else
        <form action="{{ route('events.reserve', $event->id) }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-3">
                <label class="form-label text-light">Mode de paiement</label>
                <select name="payment_method" class="form-select" required>
                    <option value="mobile_money">Mobile Money (Mvola, Orange, Airtel)</option>
                    <option value="cash">Cash</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Acheter un billet</button>
        </form>
    @endif
</div>
@endsection
