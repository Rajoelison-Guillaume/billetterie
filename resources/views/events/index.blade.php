@extends('layouts.app')

@section('content')
<div class="container py-4">
    <form method="GET" action="{{ route('events.index') }}">
        <input type="text" name="q" placeholder="Rechercher un événement..." class="form-control">
            <button type="submit" class="btn btn-primary mt-2">Rechercher</button>
    </form>


    <h2 class="text-primary fw-bold mb-4">Agenda des événements</h2>
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

    <div class="row">
        @forelse($events as $event)
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text">{{ $event->start_date->format('d/m/Y') }} — {{ $event->venue->name ?? 'Lieu inconnu' }}</p>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-info text-dark">Voir détails</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Aucun événement disponible.</p>
        @endforelse
    </div>
</div>
@endsection
