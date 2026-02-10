@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">🎬 Plan de salle : {{ $room->name }}</h2>
    <p>Capacité totale : {{ $room->capacity }} sièges</p>

    <div class="seat-grid mt-3">
        @foreach($seats as $seat)
            <span class="seat-btn {{ $seat->is_accessible ? 'accessible' : 'normal' }}">
                {{ $seat->row_label }}{{ $seat->seat_number }}
            </span>
        @endforeach
    </div>
</div>
@endsection

@section('styles')
<style>
.seat-grid {
    display: grid;
    grid-template-columns: repeat(20, 1fr); /* 20 colonnes */
    gap: 5px;
}
.seat-btn {
    display: inline-block;
    padding: 6px;
    border-radius: 4px;
    background-color: #10b981; /* vert par défaut */
    color: white;
    text-align: center;
    font-size: 12px;
}
.seat-btn.accessible {
    background-color: #3b82f6; /* bleu pour sièges accessibles */
}
</style>
@endsection
