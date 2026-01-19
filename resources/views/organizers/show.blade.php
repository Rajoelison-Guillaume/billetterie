@extends('layouts.app')

@section('title', 'Détails de l’organisateur')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>{{ $organizer->name }}</h4>
        </div>
        <div class="card-body">
            @if($organizer->logo)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $organizer->logo) }}" 
                         alt="Logo de {{ $organizer->name }}" 
                         class="img-fluid rounded" 
                         style="max-height:150px;">
                </div>
            @endif

            <p class="text-muted">{{ $organizer->description }}</p>

            <h5 class="mt-4">Événements organisés :</h5>
            <ul class="list-group">
                @forelse($organizer->events as $event)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $event->title }}</span>
                        <small class="text-muted">{{ $event->date }}</small>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Aucun événement pour l’instant.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
