@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">Mon profil</h2>
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

    <div class="card bg-dark text-light shadow mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ Auth::user()->name }}</h5>
            <p class="card-text"><strong>Email :</strong> {{ Auth::user()->email }}</p>
            <p class="card-text"><strong>Inscrit depuis :</strong> {{ Auth::user()->created_at->format('d/m/Y') }}</p>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-3">Modifier mes informations</a>
        </div>
    </div>

    <h4 class="text-light mb-3">Mes billets</h4>
    @forelse(Auth::user()->orders as $order)
        <div class="card mb-3 bg-secondary text-white">
            <div class="card-body">
                <h5 class="card-title">Commande #{{ $order->id }} — {{ $order->created_at->format('d/m/Y') }}</h5>
                <p class="card-text">Montant total : {{ $order->total_amount }} Ar</p>
                <p class="card-text">Statut : {{ $order->status }}</p>
                <ul>
                    @foreach($order->tickets as $ticket)
                        <li>{{ $ticket->event->title }} — {{ $ticket->seat_number ?? 'Place libre' }}</li>
                    @endforeach
                </ul>
                    <a href="{{ route('orders.history') }}" class="btn btn-primary">Historique de mes commandes</a>

            </div>
        </div>
    @empty
        <p class="text-muted">Aucun billet acheté pour le moment.</p>
    @endforelse
</div>
@endsection
