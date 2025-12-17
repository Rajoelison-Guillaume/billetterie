@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">{{ $organizer->name }}</h2>
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


    @if($organizer->logo)
        <img src="{{ asset('storage/' . $organizer->logo) }}" class="img-fluid rounded mb-3" style="max-height: 200px;">
    @endif

    <p class="lead text-light">{{ $organizer->description }}</p>

    <hr class="my-5">

    <h4 class="text-light mb-3">Événements organisés</h4>
    <div class="row">
        @forelse($organizer->events as $event)
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text">{{ $event->start_date->format('d/m/Y') }} — {{ $event->venue->name ?? 'Lieu inconnu' }}</p>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-info text-dark">Voir l'événement</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Aucun événement associé.</p>
        @endforelse
    </div>
</div>
@endsection
