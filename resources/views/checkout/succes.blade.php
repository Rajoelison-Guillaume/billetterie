@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">
    <h2 class="fw-bold text-success mb-4">✅ Paiement réussi</h2>
    <p>Votre paiement a été confirmé par Efaina.</p>
    <p>Vous pouvez consulter vos tickets ou vos réservations dans votre espace utilisateur.</p>

    <a href="{{ route('tickets.index') }}" class="btn btn-primary mt-3">Voir mes tickets</a>
    <a href="{{ route('reservations.index') }}" class="btn btn-secondary mt-3">Voir mes réservations</a>
</div>
@endsection
