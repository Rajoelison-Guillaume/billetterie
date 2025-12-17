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
<form action="{{ route('client.reservation.store') }}" method="POST">
    @csrf
    <input type="hidden" name="event_id" value="{{ $event->id }}">
    <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
    <input type="hidden" name="price" value="{{ $event->ticket_price }}">

    <h5>Choisissez votre siège :</h5>
    <div class="seat-map">
        @foreach($sessionSeats as $sessionSeat)
            @php $isReserved = $sessionSeat->status === 'reserved'; @endphp
            <label class="btn {{ $isReserved ? 'btn-danger disabled' : 'btn-success' }}">
                <input type="radio" name="seat_id" value="{{ $sessionSeat->seat_id }}" {{ $isReserved ? 'disabled' : '' }}>
                {{ $sessionSeat->seat->row_label }}{{ $sessionSeat->seat->seat_number }}
            </label>
        @endforeach
    </div>

    @include('components.payment_fields')

    <button type="submit" class="btn btn-primary mt-3">Réserver</button>
</form>

    <h4 class="text-light mt-4">Séances disponibles</h4>
    @foreach($event->showtimes as $showtime)
        <div class="mb-3">
            <strong>{{ $showtime->start_at->format('d/m/Y H:i') }}</strong> — {{ $showtime->room->name }}
            <a href="{{ route('showtimes.show', $showtime->id) }}" class="btn btn-primary btn-sm">Voir les sièges</a>
        </div>
    @endforeach
</div>
@endsection
