@extends('layouts.admin')

@section('content')

<form action="{{ route('payments.pay') }}" method="POST">
    @csrf

    <label>Méthode de paiement :</label>
    <select name="method" required>
        <option value="mvola">MVola</option>
        <option value="orange_money">OrangeMoney</option>
        <option value="airtel_money">AirtelMoney</option>
        <option value="cash">Cash</option>
    </select>

    <label>Téléphone (Mobile Money) :</label>
    <input type="text" name="phone" placeholder="032xxxxxxx">

    @if($order->tickets->first()->event->isCinema())
        <label>Choisir un siège :</label>
        <select name="seat_id">
            @foreach($availableSeats as $seat)
                <option value="{{ $seat->id }}">{{ $seat->row_label }}{{ $seat->seat_number }}</option>
            @endforeach
        </select>
    @endif

    <button type="submit">Payer et réserver</button>
</form>
@endsection