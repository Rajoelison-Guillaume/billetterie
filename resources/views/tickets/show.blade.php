@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">Billet #{{ $ticket->id }}</h2>
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

    <div class="card bg-dark text-light">
        <div class="card-body">
            <p><strong>Événement :</strong> {{ $ticket->event->title }}</p>
            <p><strong>Date :</strong> {{ $ticket->event->start_date->format('d/m/Y') }}</p>
            <p><strong>Lieu :</strong> {{ $ticket->event->venue->name ?? '-' }}</p>
            <p><strong>Prix :</strong> {{ number_format($ticket->price, 0, ',', ' ') }} Ar</p>
            <p><strong>QR Code :</strong> {{ $ticket->qr_code }}</p>
        </div>
    </div>

    <a href="{{ route('tickets.index') }}" class="btn btn-secondary mt-3">⬅️ Retour</a>
</div>
@endsection
