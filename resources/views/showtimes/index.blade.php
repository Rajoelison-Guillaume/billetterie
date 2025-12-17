@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">Toutes les séances</h2>
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
        @forelse($showtimes as $showtime)
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-light shadow">
                    <div class="card-body">
                        <h5 class="card-title">{{ $showtime->event->title }}</h5>
                        <p><strong>Salle :</strong> {{ $showtime->room->name }}</p>
                        <p><strong>Date :</strong> {{ $showtime->start_at->format('d/m/Y H:i') }}</p>
                        <a href="{{ route('showtimes.show', $showtime->id) }}" class="btn btn-info text-dark">Voir les places</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Aucune séance disponible.</p>
        @endforelse
    </div>
</div>
@endsection
