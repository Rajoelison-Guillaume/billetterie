@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Panier actif --}}
    <h2 class="fw-bold text-primary mb-4">🛒 Mon panier</h2>
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


    @if($activeOrder && $activeOrder->tickets->count())
        @foreach($activeOrder->tickets->groupBy('event_id') as $eventId => $tickets)
            @php
                $event = $tickets->first()->event;
                $total = $tickets->sum('price');
            @endphp
            <div class="card mb-3 bg-dark text-light">
                <div class="card-header">
                    {{ $event->title }} <span class="badge bg-info">{{ $event->category }}</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($tickets as $ticket)
                            <li class="list-group-item bg-dark text-light">
                                🎫 {{ $ticket->event->title }} — {{ number_format($ticket->price, 0, ',', ' ') }} Ar
                                @if($ticket->seat)
                                    <br><small>🪑 Place : {{ $ticket->seat->row_label }}{{ $ticket->seat->seat_number }}</small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-3">
                        <strong>Total pour {{ $event->title }} :</strong> {{ number_format($total, 0, ',', ' ') }} Ar
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Total global du panier --}}
        <div class="alert alert-info fw-bold">
            Total panier : {{ number_format($activeOrder->total_amount, 0, ',', ' ') }} Ar
        </div>
    @else
        <p class="text-muted">Votre panier est vide.</p>
    @endif

    {{-- Historique des commandes --}}
    <h3 class="mt-5">Historique de vos commandes</h3>
    @forelse($pastOrders as $order)
        <div class="card mb-3">
            <div class="card-header">
                Commande #{{ $order->id }} — {{ $order->created_at->format('d/m/Y H:i') }}
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($order->tickets as $ticket)
                        <li class="list-group-item">
                            🎟️ {{ $ticket->event->title }} — {{ number_format($ticket->price, 0, ',', ' ') }} Ar
                            @if($ticket->seat)
                                <br><small>🪑 Place : {{ $ticket->seat->row_label }}{{ $ticket->seat->seat_number }}</small>
                            @endif
                        </li>
                    @endforeach
                </ul>
                <div class="mt-3">
                    <strong>Total :</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} Ar
                </div>
                <div><strong>Statut :</strong> {{ ucfirst($order->status) }}</div>
            </div>
        </div>
    @empty
        <p class="text-muted">Aucune commande passée.</p>
    @endforelse
</div>
@endsection
