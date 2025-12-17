@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">
        🎬 Séance : {{ $showtime->event->title }} — {{ $showtime->start_at->format('d/m/Y H:i') }}
    </h2>
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

    <p><strong>Salle :</strong> {{ $showtime->room->name }}</p>
    <p><strong>Prix par billet :</strong> {{ number_format($showtime->price, 0, ',', ' ') }} Ar</p>

    <h4 class="text-light mt-4">🪑 Choisissez vos places</h4>
    <form action="{{ route('reservations.reserve', $showtime->id) }}" method="POST">
    @csrf
        <input type="hidden" name="payment_method" value="mobile_money">
            <div class="row">
                @foreach($seats as $seat)
                @php
                $isReserved = in_array($seat->id, $occupiedSeatIds);
                @endphp
            <div class="col-2 mb-3">
                <label class="btn {{ $isReserved ? 'btn-secondary' : 'btn-outline-success' }}">
                    <input type="checkbox" name="seat_id[]" value="{{ $seat->id }}" {{ $isReserved ? 'disabled' : '' }}>
                    {{ $seat->row_label }}{{ $seat->seat_number }}
                </label>
            </div>
        @endforeach
    </div>
        <button type="submit" class="btn btn-primary mt-3">Réserver les places sélectionnées</button>
    </form>

</div>
@endsection
