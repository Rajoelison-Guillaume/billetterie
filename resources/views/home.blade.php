@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<div class="hero mb-5" data-aos="fade-up">
    <h1 class="fw-bold display-4">Billetterie Madagascar</h1>
    <p class="lead">Réservez vos billets pour les meilleurs événements culturels, professionnels et cinématographiques</p>
    <a href="{{ route('events.index') }}" class="btn btn-primary btn-lg mt-3">Découvrir les événements</a>
</div>

<!-- Statistiques -->
<div class="row text-center text-light mb-5">
    <div class="col-md-4" data-aos="zoom-in">
        <h2 class="fw-bold">{{ \App\Models\Event::count() }}</h2>
        <p>Événements disponibles</p>
    </div>
    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
        <h2 class="fw-bold">{{ \App\Models\Ticket::count() }}</h2>
        <p>Billets vendus</p>
    </div>
    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="400">
        <h2 class="fw-bold">{{ \App\Models\Organizer::count() }}</h2>
        <p>Organisateurs partenaires</p>
    </div>
</div>

<!-- Slider des événements -->
<h3 class="text-light mb-3">🎉 Événements à venir</h3>
<div class="row">
    @foreach(\App\Models\Event::where('start_date', '>=', now())->take(6)->get() as $event)
        <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="card futuristic-card shadow" data-aos="fade-up">
                <div class="card-body">
                    <h5 class="card-title">{{ $event->title }}</h5>
                    <p class="card-text">{{ $event->start_date->format('d/m/Y') }} — {{ $event->venue->name ?? 'Lieu inconnu' }}</p>
                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-info text-dark">Réserver</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Organisateurs -->
<h3 class="text-light mt-5 mb-3">🏆 Organisateurs en vedette</h3>
<div class="row">
    @foreach(\App\Models\Organizer::has('events')->take(3)->get() as $organizer)
        <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="card futuristic-card shadow" data-aos="zoom-in">
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
        <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="card futuristic-card shadow" data-aos="fade-up">
                <div class="card-body">
                    <h5 class="card-title">{{ $cinema->title }}</h5>
                    <p class="card-text">{{ $cinema->start_date->format('d/m/Y') }} — {{ $cinema->venue->name ?? 'Lieu inconnu' }}</p>
                    <a href="{{ route('events.show', $cinema->id) }}" class="btn btn-info text-dark">Voir séance</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
<section class="hero d-flex flex-column justify-content-center align-items-center text-center">
    <div data-aos="fade-up">
        <h1 class="fw-bold display-4 text-light mb-3">
            Bienvenue sur <span class="text-info">Billetterie MG</span>
        </h1>
        <p class="lead text-light mb-4">
            La plateforme d’événements moderne à Madagascar.<br>
            Réservez vos billets en toute simplicité et vivez des expériences uniques.
        </p>
        <a href="{{ route('events.index') }}" class="btn btn-primary btn-lg">
            🎟️ Découvrir les événements
        </a>
    </div>
</section>


@endsection
