@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">➕ Ajouter un siège</h2>

    <form action="{{ route('admin.seats.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="room_id" class="form-label">Salle</label>
            <select name="room_id" id="room_id" class="form-select" required>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="row_label" class="form-label">Rangée</label>
            <input type="text" name="row_label" id="row_label" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="seat_number" class="form-label">Numéro du siège</label>
            <input type="number" name="seat_number" id="seat_number" class="form-control" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_accessible" id="is_accessible" class="form-check-input">
            <label for="is_accessible" class="form-check-label">Accessible</label>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('admin.seats.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
