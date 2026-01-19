@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">✏ Modifier l'événement</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Titre + Slug --}}
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" id="title" name="title" class="form-control" 
                   value="{{ old('title', $event->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" id="slug" name="slug" class="form-control" 
                   value="{{ old('slug', $event->slug) }}" readonly>
        </div>

        {{-- Catégorie --}}
        <div class="mb-3">
            <label for="category" class="form-label">Catégorie</label>
            <select id="category" name="category" class="form-select" required>
                <option value="cinema" {{ old('category', $event->category) == 'cinema' ? 'selected' : '' }}>Cinéma</option>
                <option value="concert" {{ old('category', $event->category) == 'concert' ? 'selected' : '' }}>Concert</option>
                <option value="festival" {{ old('category', $event->category) == 'festival' ? 'selected' : '' }}>Festival</option>
                <option value="libre" {{ old('category', $event->category) == 'libre' ? 'selected' : '' }}>Libre</option>
            </select>
        </div>

        {{-- Type d’événement (lié à catégorie) --}}
        <div class="mb-3">
            <label for="event_type_id" class="form-label">Type d'événement</label>
            <select id="event_type_id" name="event_type_id" class="form-select" required>
                @foreach($eventTypes as $type)
                    <option value="{{ $type->id }}" 
                        {{ old('event_type_id', $event->event_type_id) == $type->id ? 'selected' : '' }}>
                        {{ $type->label }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Organisateur --}}
        <div class="mb-3">
            <label for="organizer_id" class="form-label">Organisateur</label>
            <select name="organizer_id" class="form-select" required>
                <option value="">-- Choisir un organisateur --</option>
                @foreach($organizers as $organizer)
                    <option value="{{ $organizer->id }}" 
                        {{ old('organizer_id', $event->organizer_id) == $organizer->id ? 'selected' : '' }}>
                        {{ $organizer->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Lieu --}}
        <div class="mb-3">
            <label for="venue_id" class="form-label">Lieu</label>
            <select name="venue_id" class="form-select" required>
                <option value="">-- Choisir un lieu --</option>
                @foreach($venues as $venue)
                    <option value="{{ $venue->id }}" 
                        {{ old('venue_id', $event->venue_id) == $venue->id ? 'selected' : '' }}>
                        {{ $venue->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Salle --}}
        <div class="mb-3">
            <label for="room_id" class="form-label">Salle</label>
            <select name="room_id" class="form-select" required>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" 
                        {{ old('room_id', $event->room_id) == $room->id ? 'selected' : '' }}>
                        {{ $room->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Dates --}}
        <div class="mb-3">
            <label for="start_date" class="form-label">Date début</label>
            <input type="datetime-local" name="start_date" class="form-control" 
                   value="{{ old('start_date', $event->start_date) }}" required>
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Date fin</label>
            <input type="datetime-local" name="end_date" class="form-control" 
                   value="{{ old('end_date', $event->end_date) }}" required>
        </div>

        {{-- Prix --}}
        <div class="mb-3">
            <label for="ticket_price" class="form-label">Prix du billet</label>
            <input type="number" name="ticket_price" class="form-control" 
                   value="{{ old('ticket_price', $event->ticket_price) }}" required>
        </div>

        {{-- Actif --}}
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" 
                   {{ old('is_active', $event->is_active) ? 'checked' : '' }}>
            <label class="form-check-label">Activer l'événement</label>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $event->description) }}</textarea>
        </div>

        {{-- Trailer --}}
        <div class="mb-3">
            <label for="trailer_url" class="form-label">Lien du trailer (YouTube, Vimeo...)</label>
            <input type="url" name="trailer_url" class="form-control" 
                   value="{{ old('trailer_url', $event->trailer_url) }}">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Retour</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Slug auto-généré
    document.getElementById('title').addEventListener('input', function () {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-') 
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
    });

    // Synchronisation catégorie → type d'événement
    document.getElementById('category').addEventListener('change', function () {
        const selected = this.value.toLowerCase();
        const typeSelect = document.getElementById('event_type_id');
        for (let option of typeSelect.options) {
            if (option.text.toLowerCase().includes(selected)) {
                typeSelect.value = option.value;
                break;
            }
        }
    });
</script>
@endsection
