@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Modifier l’événement</h2>

    <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $event->title }}" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ $event->slug }}" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Catégorie</label>
            <select name="category" id="category" class="form-select" required>
                <option value="cinema" @selected($event->category === 'cinema')>Cinéma</option>
                <option value="expo" @selected($event->category === 'expo')>Exposition</option>
                <option value="concert" @selected($event->category === 'concert')>Concert</option>
                <option value="conference" @selected($event->category === 'conference')>Conférence</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Date début</label>
            <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                   value="{{ $event->start_date->format('Y-m-d\TH:i') }}" required>
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Date fin</label>
            <input type="datetime-local" name="end_date" id="end_date" class="form-control"
                   value="{{ $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '' }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $event->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
