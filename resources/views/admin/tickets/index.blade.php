@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">🎟️ Gestion des billets</h2>

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

{{-- Formulaire de recherche avancée --}}
<form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-3 mb-4">
    <div class="col-md-2">
        <label class="form-label">ID</label>
        <input type="text" name="id" value="{{ request('id') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">QR Code</label>
        <input type="text" name="qr_code" value="{{ request('qr_code') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Événement</label>
        <input type="text" name="event" value="{{ request('event') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label class="form-label">Date</label>
        <input type="date" name="date" value="{{ request('date') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label class="form-label">Lieu</label>
        <input type="text" name="venue" value="{{ request('venue') }}" class="form-control">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-info">🔍 Rechercher</button>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">♻️ Réinitialiser</a>
    </div>
</form>

{{-- Tableau des billets --}}
<table class="table table-dark table-striped align-middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Commande</th>
            <th>Utilisateur</th>
            <th>Événement</th>
            <th>QR Code</th>
            <th>Prix</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tickets as $ticket)
        <tr>
            <td>{{ $ticket->id }}</td>
            <td>#{{ $ticket->order_id }}</td>
            <td>{{ $ticket->order->user->name ?? '-' }}</td>
            <td>{{ $ticket->event->title ?? '-' }}</td>
            <td>{{ $ticket->qr_code }}</td>
            <td>{{ number_format($ticket->price, 0, ',', ' ') }} Ar</td>
            <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-info btn-sm">👁️ Voir</a>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center">Aucun billet trouvé.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $tickets->links() }}
</div>
@endsection
