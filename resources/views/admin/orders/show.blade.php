@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">Détail de la commande #{{ $order->id }}</h2>
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
        <p><strong>Utilisateur :</strong> {{ $order->user->name ?? '-' }}</p>
        <p><strong>Email :</strong> {{ $order->user->email ?? '-' }}</p>
        <p><strong>Total :</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} Ar</p>
        <p><strong>Statut :</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Paiement :</strong> {{ $order->payment->status ?? '-' }}</p>
    </div>
</div>

<h4 class="text-info">🎟️ Billets associés</h4>
<ul class="list-group mb-4">
    @foreach($order->tickets as $ticket)
        <li class="list-group-item bg-dark text-light">
            QR: {{ $ticket->qr_code }} — {{ $ticket->event->title }} — {{ number_format($ticket->price, 0, ',', ' ') }} Ar
        </li>
    @endforeach
</ul>

<a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">⬅️ Retour</a>
@endsection
