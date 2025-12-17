@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">💳 Paiement de la commande #{{ $order->id }}</h2>
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

    <p><strong>Total à payer :</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} Ar</p>

    <form action="{{ route('checkout.pay') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Méthode de paiement</label>
            <select name="method" class="form-select" required>
                <option value="mvola">MVola</option>
                <option value="orange_money">Orange Money</option>
                <option value="airtel_money">Airtel Money</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">✅ Payer</button>
    </form>
</div>
@endsection
