@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">{{ $event->title }}</h2>
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

    <p class="lead text-light">{{ $event->description }}</p>

    <h4 class="text-light mt-4">Acheter un billet</h4>
    <form action="{{ route('events.reserve', $event->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label text-light">Mode de paiement</label>
            <select name="payment_method" class="form-select" required>
                <option value="mobile_money">Mobile Money (Mvola, Orange, Airtel)</option>
                <option value="cash">Cash</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Acheter un billet</button>
    </form>
</div>
@endsection
