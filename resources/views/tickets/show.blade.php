@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">Billet #{{ $ticket->id }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
            <p><strong>Date :</strong> {{ $ticket->event->start_date->format('d/m/Y H:i') }}</p>
            <p><strong>Lieu :</strong> {{ $ticket->event->venue->name ?? '-' }}</p>
            <p><strong>Prix :</strong> {{ number_format($ticket->price, 0, ',', ' ') }} Ar</p>

            @if($ticket->seat)
                <p><strong>Siège :</strong> {{ $ticket->seat->label() }}</p>
            @else
                <p><strong>Type :</strong> Billet simple (événement libre)</p>
            @endif

            <p><strong>Paiement :</strong> 
                {{ $ticket->order->payment->method ?? 'Non renseigné' }} 
                @if(isset($ticket->order->payment->provider_ref))
                    (Ref: {{ $ticket->order->payment->provider_ref }})
                @endif
            </p>

            <div class="mt-3 text-center">
                <strong>QR Code :</strong><br>
                {!! QrCode::size(200)->generate($ticket->qr_code) !!}
            </div>
        </div>
    </div>

    <a href="{{ route('tickets.index') }}" class="btn btn-secondary mt-3">⬅️ Retour à mes billets</a>
</div>
@endsection