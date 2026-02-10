@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">
    <h2 class="fw-bold text-danger mb-4">❌ Paiement annulé</h2>
    <p>Votre paiement n’a pas été confirmé. Vous pouvez réessayer ou choisir un autre mode de paiement.</p>

    <a href="{{ route('checkout.show') }}" class="btn btn-warning mt-3">Réessayer le paiement</a>
    <a href="{{ route('events.index') }}" class="btn btn-secondary mt-3">Retour aux événements</a>
</div>
@endsection
