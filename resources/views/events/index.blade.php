@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Formulaire de recherche avancée --}}
    <form method="GET" action="{{ route('events.index') }}" class="row g-3 mb-4 bg-dark text-light p-3 rounded shadow">
        <div class="col-md-3">
            <input type="text" name="q" class="form-control" placeholder="🔍 Rechercher un événement..." value="{{ request('q') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="date_start" class="form-control" value="{{ request('date_start') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="date_end" class="form-control" value="{{ request('date_end') }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">Rechercher</button>
            <a href="{{ route('events.index') }}" class="btn btn-secondary flex-fill">Réinitialiser</a>
        </div>
    </form>

    <h2 class="text-primary fw-bold mb-4">🎟️ Agenda des événements</h2>

    {{-- Messages flash --}}
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

    {{-- Liste des événements --}}
    <div class="row">
        @forelse($events as $event)
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-lg h-100 bg-dark text-light">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title fw-bold text-light">{{ $event->title }}</h5>
                        <p class="card-text">
                            <span class="badge bg-primary">📅 {{ $event->start_date->format('d/m/Y') }}</span><br>
                            <span class="badge bg-info text-dark">📍 {{ $event->venue->name ?? 'Lieu inconnu' }}</span>
                        </p>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-light mt-auto">Voir détails</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Aucun événement disponible.</p>
        @endforelse
    </div>
</div>
@endsection
