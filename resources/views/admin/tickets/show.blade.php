@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">Détail du billet #{{ $ticket->id }}</h2>
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

<div class="card bg-dark text-light mb-4">
    <div class="card-body">
        <p><strong>Commande :</strong> #{{ $ticket->order_id }}</p>
        <p><strong>Utilisateur :</strong> {{ $ticket->order->user->name ?? '-' }}</p>
        <p><strong>Événement :</strong> {{ $ticket->event->title ?? '-' }}</p>
        <p><strong>QR Code :</strong> {{ $ticket->qr_code }}</p>
        <p><strong>Prix :</strong> {{ number_format($ticket->price, 0, ',', ' ') }} Ar</p>
        <p><strong>Date :</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
    </div>
</div>

<a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">⬅️ Retour</a>
@endsection
