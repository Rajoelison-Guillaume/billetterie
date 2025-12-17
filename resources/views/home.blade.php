@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Hero Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">Billetterie Madagascar</h1>
        <p class="lead text-light">Réservez vos billets pour les meilleurs événements culturels, professionnels et cinématographiques à Madagascar</p>
        <a href="{{ route('events.index') }}" class="btn btn-info text-dark fw-bold mt-3">Voir les événements</a>
    </div>

    <!-- Statistiques -->
    <div class="row text-center text-light mb-5">
        <div class="col-md-4">
            <h2 class="fw-bold">{{ \App\Models\Event::count() }}</h2>
            <p>Événements disponibles</p>
        </div>
        <div class="col-md-4">
            <h2 class="fw-bold">{{ \App\Models\Ticket::count() }}</h2>
            <p>Billets vendus</p>
        </div>
        <div class="col-md-4">
            <h2 class="fw-bold">{{ \App\Models\Organizer::count() }}</h2>
            <p>Organisateurs partenaires</p>
        </div>
    </div>

    <!-- Slider des événements à venir -->
    <h3 class="text-light mb-3">🎉 Événements à venir</h3>
    <div class="row">
        @foreach(\App\Models\Event::where('start_date', '>=', now())->take(6)->get() as $event)
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text">{{ $event->start_date->format('d/m/Y') }} — {{ $event->venue->name ?? 'Lieu inconnu' }}</p>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-info text-dark">Réserver</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Organisateurs en vedette -->
    <h3 class="text-light mt-5 mb-3">🏆 Organisateurs en vedette</h3>
    <div class="row">
        @foreach(\App\Models\Organizer::has('events')->take(3)->get() as $organizer)
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white shadow">
                    @if($organizer->logo)
                        <img src="{{ asset('storage/' . $organizer->logo) }}" class="card-img-top" alt="{{ $organizer->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $organizer->name }}</h5>
                        <p class="card-text">{{ Str::limit($organizer->description, 100) }}</p>
                        <a href="{{ route('organizers.show', $organizer->id) }}" class="btn btn-primary">Voir profil</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Section cinéma -->
    <h3 class="text-light mt-5 mb-3">🎬 Cinéma & Spectacles</h3>
    <div class="row">
        @foreach(\App\Models\Event::where('category', 'cinema')->take(3)->get() as $cinema)
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title">{{ $cinema->title }}</h5>
                        <p class="card-text">{{ $cinema->start_date->format('d/m/Y') }} — {{ $cinema->venue->name ?? 'Lieu inconnu' }}</p>
                        <a href="{{ route('events.show', $cinema->id) }}" class="btn btn-info text-dark">Voir séance</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
