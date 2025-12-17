@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">Réserver votre billet</h2>

    {{-- Messages --}}
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

    <form action="{{ route('client.reservation.store') }}" method="POST">
        @csrf

        <input type="hidden" name="event_id" value="{{ $event->id }}">
        <input type="hidden" name="showtime_id" value="{{ $showtime->id ?? null }}">
        <input type="hidden" name="price" value="{{ $event->ticket_price }}">

        {{-- Cas cinéma : affichage des sièges --}}
        @if($event->event_type_id == 1) 
            <h5 class="mb-3">Choisissez votre siège :</h5>
            <div class="seat-map">
                @foreach($sessionSeats as $sessionSeat)
                    @php $isReserved = $sessionSeat->status === 'reserved'; @endphp
                    <label class="btn {{ $isReserved ? 'btn-danger disabled' : 'btn-success' }}">
                        <input type="radio" name="seat_id" value="{{ $sessionSeat->seat_id }}" {{ $isReserved ? 'disabled' : '' }}>
                        {{ $sessionSeat->seat->row_label }}{{ $sessionSeat->seat->seat_number }}
                    </label>
                @endforeach
            </div>
        @else
            {{-- Cas libre : pas de siège --}}
            <p>Billet simple pour l’événement libre.</p>
        @endif

        {{-- Paiement --}}
        <h5 class="mt-4">Mode de paiement :</h5>
        <div class="mb-3">
            <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="cash">Cash</option>
                <option value="mobile_money">Mobile Money</option>
            </select>
        </div>

        <div id="mobile-money-fields" style="display:none;">
            <div class="mb-3">
                <label for="provider" class="form-label">Opérateur</label>
                <select name="provider" class="form-select">
                    <option value="MVola">MVola</option>
                    <option value="OrangeMoney">Orange Money</option>
                    <option value="AirtelMoney">Airtel Money</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="provider_ref" class="form-label">Code transaction</label>
                <input type="text" name="provider_ref" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">✅ Réserver</button>
    </form>
</div>

<script>
    document.getElementById('payment_method').addEventListener('change', function() {
        document.getElementById('mobile-money-fields').style.display = 
            this.value === 'mobile_money' ? 'block' : 'none';
    });
</script>
@endsection
