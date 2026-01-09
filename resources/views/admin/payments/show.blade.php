@extends('layouts.admin')

@section('content')
<h2 class="fw-bold text-primary mb-4">Détail du paiement #{{ $payment->id }}</h2>

<ul class="list-group mb-3">
    <li class="list-group-item"><strong>Client :</strong> {{ $payment->order->user->name }}</li>
    <li class="list-group-item"><strong>Commande :</strong> #{{ $payment->order_id }}</li>
    <li class="list-group-item"><strong>Montant :</strong> {{ number_format($payment->amount, 0, ',', ' ') }} Ar</li>
    <li class="list-group-item"><strong>Méthode :</strong> {{ ucfirst($payment->method) }}</li>
    <li class="list-group-item"><strong>Provider :</strong> {{ $payment->provider ?? '-' }}</li>
    <li class="list-group-item"><strong>Référence :</strong> {{ $payment->provider_ref ?? '-' }}</li>
    <li class="list-group-item"><strong>Statut :</strong> {{ ucfirst($payment->status) }}</li>
    <li class="list-group-item"><strong>Date :</strong> {{ $payment->created_at->format('d/m/Y H:i') }}</li>
</ul>

<h4 class="fw-bold">Billets liés</h4>
<ul class="list-group">
    @foreach($payment->order->tickets as $ticket)
        <li class="list-group-item">
            🎫 {{ $ticket->event->title }} 
            @if($ticket->seat)
                — Siège {{ $ticket->seat->row_label }}{{ $ticket->seat->seat_number }}
            @endif
            — Statut : {{ ucfirst($ticket->status) }}
        </li>
    @endforeach
</ul>
@endsection
