@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">
        🎟 Réservation pour {{ $showtime->event->title }}
    </h2>

    <p><strong>Date :</strong> {{ $showtime->start_at->format('d/m/Y H:i') }}</p>
    <p><strong>Salle :</strong> {{ $showtime->room->name }}</p>
    <p><strong>Prix du billet :</strong> {{ number_format($showtime->event->ticket_price, 0, ',', ' ') }} Ar</p>

    {{-- Affichage des sièges --}}
    <h4 class="text-light mt-4">🪑 Choisissez vos places</h4>
    <form action="{{ route('showtimes.reserve', $showtime->id) }}" method="POST">
        @csrf
        <div class="d-flex flex-wrap gap-2 mb-4">
            @foreach($seats as $seat)
                @php
                    $isOccupied = in_array($seat->id, $occupiedSeatIds);
                @endphp
                <label class="btn {{ $isOccupied ? 'btn-danger disabled' : 'btn-success' }}">
                    <input type="checkbox" name="seat_id[]" value="{{ $seat->id }}" 
                           {{ $isOccupied ? 'disabled' : '' }} hidden>
                    {{ $seat->row_label }}{{ $seat->seat_number }}
                </label>
            @endforeach
        </div>

        {{-- Paiement --}}
        <div class="mb-3">
            <label class="form-label text-light">Mode de paiement</label>
            <select name="payment_method" class="form-select" required>
                <option value="mobile_money">Mobile Money (Mvola, Orange, Airtel)</option>
                <option value="cash">Cash</option>
            </select>
        </div>

        {{-- Téléphone --}}
        <div class="mb-3">
            <label class="form-label text-light">Numéro de téléphone</label>
            <input type="text" name="phone" class="form-control" placeholder="032xxxxxxx" required>
        </div>

        <button type="submit" class="btn btn-primary">Réserver</button>
    </form>
</div>
@endsection
