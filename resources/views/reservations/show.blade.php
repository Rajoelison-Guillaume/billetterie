@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">
        🎬 Choisissez votre place pour {{ $showtime->event->title }} — {{ $showtime->start_at->format('d/m/Y H:i') }}
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

    <div class="row">
        @foreach($showtime->room->seats as $seat)
            @php
                $isReserved = in_array($seat->id, $reservedSeats);
            @endphp
            <div class="col-2 mb-3">
                <form action="{{ route('reservations.reserve', $showtime->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="seat_id" value="{{ $seat->id }}">
                    <button type="submit" class="btn {{ $isReserved ? 'btn-secondary' : 'btn-success' }}" {{ $isReserved ? 'disabled' : '' }}>
                        Rangée {{ $seat->row_label }} — Place {{ $seat->seat_number }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
