@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">✏ Modifier le siège #{{ $seat->id }}</h2>

    <form action="{{ route('admin.seats.update', $seat) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="room_id" class="form-label">Salle</label>
            <select name="room_id" id="room_id" class="form-select" required>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ $seat->room_id == $room->id ? 'selected' : '' }}>
                        {{ $room->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="row_label" class="form-label">Rangée</label>
            <input type="text" name="row_label" id="row_label" class="form-control" value="{{ $seat->row_label }}" required>
        </div>

        <div class="mb-3">
            <label for="seat_number" class="form-label">Numéro du siège</label>
            <input type="number" name="seat_number" id="seat_number" class="form-control" value="{{ $seat->seat_number }}" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_accessible" id="is_accessible" class="form-check-input" {{ $seat->is_accessible ? 'checked' : '' }}>
            <label for="is_accessible" class="form-check-label">Accessible</label>
        </div>

        <button type="submit" class="btn btn-warning">Mettre à jour</button>
        <a href="{{ route('admin.seats.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
