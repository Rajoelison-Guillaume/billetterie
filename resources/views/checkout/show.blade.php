@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">💳 Paiement de la commande</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Commande :</strong> #{{ $order->id }}</p>
            <p><strong>Total :</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} Ar</p>
            <p><strong>Numéro utilisé :</strong> {{ Auth::user()->phone }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('checkout.pay') }}">
        @csrf
        <div class="mb-3">
            <label for="method" class="form-label fw-bold">Mode de paiement</label>
            <select name="method" id="method" class="form-select" required>
                <option value="mvola">MVola</option>
                <option value="orange_money">Orange Money</option>
                <option value="airtel_money">Airtel Money</option>
                <option value="cash">Espèces</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">🚀 Initier le paiement</button>
    </form>

    <div class="alert alert-info mt-4">
        Après avoir choisi votre mode de paiement, vous serez redirigé vers la page de paiement Efaina.
    </div>
</div>
@endsection