@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">Historique de vos commandes</h2>
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


    @forelse($orders as $order)
        <div class="card mb-3 bg-dark text-light">
            <div class="card-header">
                Commande #{{ $order->id }} — {{ $order->created_at->format('d/m/Y H:i') }}
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($order->tickets as $ticket)
                        <li class="list-group-item bg-dark text-light">
                            🎟️ {{ $ticket->event->title }} — {{ $ticket->price }} Ar
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @empty
        <p class="text-muted">Vous n’avez encore effectué aucune commande.</p>
    @endforelse
</div>
@endsection
