@extends('layouts.app')

@section('content')
<div class="text-center mb-5">
    <h1 class="display-5 text-primary fw-bold">Organisateurs</h1>
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

    <p class="lead">Découvrez les organisateurs d’événements à Madagascar</p>
</div>

<div class="row">
    @forelse($organizers as $organizer)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow">
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
    @empty
        <p class="text-center">Aucun organisateur enregistré.</p>
    @endforelse
</div>
@endsection
