@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">Détail du type de billet</h2>

<div class="card">
    <div class="card-body">
        <p><strong>Nom :</strong> {{ $ticketType->name }}</p>
        <p><strong>Prix :</strong> {{ number_format($ticketType->price, 0, ',', ' ') }} Ar</p>
        <p><strong>Quantité :</strong> {{ $ticketType->quantity }}</p>
        <p><strong>Événement associé :</strong> {{ $ticketType->event->title ?? '-' }}</p>
        <p><strong>Créé le :</strong> {{ $ticketType->created_at->format('d/m/Y H:i') }}</p>
    </div>
</div>

<a href="{{ route('admin.ticket-types.index') }}" class="btn btn-secondary mt-3">⬅️ Retour</a>
@endsection
