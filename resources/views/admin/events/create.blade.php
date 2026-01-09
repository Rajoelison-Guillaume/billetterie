@extends('layouts.admin')

@section('content')
<form action="{{ route('admin.events.store') }}" method="POST">
    @csrf

    <!-- Titre -->
    <div class="mb-3">
        <label for="title" class="form-label">Titre</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <!-- Slug -->
    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" required>
    </div>

    <!-- Catégorie -->
    <div class="mb-3">
        <label for="category" class="form-label">Catégorie</label>
        <select name="category" class="form-select" required>
            <option value="cinema">Cinéma</option>
            <option value="concert">Concert</option>
            <option value="festival">Festival</option>
            <option value="libre">Libre</option>
        </select>
    </div>

    <!-- Organisateur -->
    <div class="mb-3">
        <label for="organizer_id" class="form-label">Organisateur</label>
        <select name="organizer_id" class="form-select" required>
            @foreach($organizers as $organizer)
                <option value="{{ $organizer->id }}">{{ $organizer->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Lieu -->
    <div class="mb-3">
        <label for="venue_id" class="form-label">Lieu</label>
        <select name="venue_id" class="form-select" required>
            @foreach($venues as $venue)
                <option value="{{ $venue->id }}">{{ $venue->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Salle -->
    <div class="mb-3">
        <label for="room_id" class="form-label">Salle</label>
        <select name="room_id" class="form-select" required>
            @foreach($rooms as $room)
                <option value="{{ $room->id }}">{{ $room->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Type d'événement -->
    <div class="mb-3">
        <label for="event_type_id" class="form-label">Type d'événement</label>
        <select name="event_type_id" class="form-select" required>
            @foreach($eventTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Dates -->
    <div class="mb-3">
        <label for="start_date" class="form-label">Date début</label>
        <input type="datetime-local" name="start_date" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="end_date" class="form-label">Date fin</label>
        <input type="datetime-local" name="end_date" class="form-control" required>
    </div>

    <!-- Prix -->
    <div class="mb-3">
        <label for="ticket_price" class="form-label">Prix du billet</label>
        <input type="number" step="0.01" name="ticket_price" class="form-control" required>
    </div>

    <!-- Max par utilisateur -->
    <div class="mb-3">
        <label for="max_per_user" class="form-label">Max par utilisateur</label>
        <input type="number" name="max_per_user" class="form-control" value="10">
    </div>

    <!-- Actif -->
    <div class="mb-3 form-check">
        <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
        <label class="form-check-label" for="is_active">Activer l’événement</label>
    </div>

    <!-- Description -->
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label for="trailer_url" class="form-label">Lien du trailer (YouTube, Vimeo...)</label>
        <input type="text" name="trailer_url" id="trailer_url" class="form-control" value="{{ old('trailer_url', $event->trailer_url ?? '') }}">
    </div>


    <button type="submit" class="btn btn-success">Créer</button>
</form>
@endsection