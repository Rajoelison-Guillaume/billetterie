@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">🎬 Réservation de sièges</h2>

    <form method="POST" action="{{ route('showtimes.reserve', $showtime->id) }}">
        @csrf
        <div class="seat-grid">
            @foreach($showtime->room->seats->sortBy(['row','number']) as $seat)
                @php
                    $isReserved = in_array($seat->id, $reservedSeats);
                @endphp
                <label class="seat-label">
                    <input type="checkbox" name="seat_id[]" value="{{ $seat->id }}" {{ $isReserved ? 'disabled' : '' }}>
                    <span class="seat-btn {{ $isReserved ? 'occupied' : 'free' }}">
                        {{ $seat->row }}-{{ $seat->number }}
                    </span>
                </label>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary mt-3">Réserver les places sélectionnées</button>
    </form>
</div>
@endsection

@section('styles')
<style>
.seat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
    gap: 10px;
    margin-top: 20px;
}
.seat-label {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.seat-btn {
    display: inline-block;
    padding: 10px;
    border-radius: 6px;
    font-weight: bold;
    color: white;
    text-align: center;
    cursor: pointer;
    width: 100%;
}
.free { background-color: #10b981; }   /* vert = libre */
.occupied { background-color: #ef4444; cursor: not-allowed; } /* rouge = occupé */
</style>
@endsection
