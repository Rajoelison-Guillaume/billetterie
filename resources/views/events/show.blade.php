@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">{{ $event->title }}</h2>

    {{-- Messages de succès --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Messages d'erreurs --}}
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
            <iframe src="{{ $embedUrl }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
            </iframe>
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

    {{-- Formulaire de réservation direct --}}
    <form action="{{ route('events.reserve', $event->id) }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-3">
            <label class="form-label text-light">Mode de paiement</label>
            <select name="payment_method" class="form-select" required>
                <option value="mobile_money">Mobile Money (Mvola, Orange, Airtel)</option>
                <option value="cash">Cash</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label text-light">Numéro de téléphone</label>
            <input type="text" name="phone" class="form-control" placeholder="032xxxxxxx" required>
        </div>

        <button type="submit" class="btn btn-primary">Acheter un billet</button>
    </form>
</div>
@endsection
