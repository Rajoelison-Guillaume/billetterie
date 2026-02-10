@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">
        🎟️ Détails de la commande #{{ $order->id }}
    </h2>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-light">
            Commande passée le {{ $order->created_at->format('d/m/Y H:i') }}
        </div>
        <div class="card-body">
            <p><strong>Total :</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} Ar</p>
            <p><strong>Statut :</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Méthode de paiement :</strong> {{ ucfirst($order->payment_method) }}</p>
        </div>
    </div>

    <h4 class="fw-bold mb-3">Billets associés</h4>
    <div class="row">
        @foreach($order->tickets as $ticket)
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        🎫 {{ $ticket->event->title }}
                    </div>
                    <div class="card-body">
                        <p><strong>Prix :</strong> {{ number_format($ticket->price, 0, ',', ' ') }} Ar</p>
                        @if($ticket->seat)
                            <p><strong>Place :</strong> 🪑 {{ $ticket->seat->row_label }}{{ $ticket->seat->seat_number }}</p>
                        @endif
                        @if($ticket->showtime)
                            <p><strong>Séance :</strong> 📅 {{ $ticket->showtime->start_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Salle :</strong> 🎬 {{ $ticket->showtime->room->name ?? '-' }}</p>
                        @endif
                        <p><strong>QR Code :</strong></p>
                        <div class="text-center">
                            {!! QrCode::size(150)->generate($ticket->qr_code) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <a href="{{ route('orders.cart') }}" class="btn btn-secondary mt-3">⬅️ Retour au panier</a>
</div>
@endsection
