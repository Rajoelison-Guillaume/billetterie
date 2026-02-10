@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <h1 class="text-success fw-bold mb-4">✅ Paiement réussi</h1>
    <p class="lead">Votre commande a été confirmée et vos billets sont disponibles dans votre espace.</p>

    <a href="{{ route('reservations.index') }}" class="btn btn-primary mt-3">
        Voir mes réservations
    </a>
</div>
@endsection
