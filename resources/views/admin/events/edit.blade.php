@extends('layouts.admin')

@section('content')
<form action="{{ route('admin.events.update', $event->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Tous les champs identiques à create.blade.php -->
    <!-- Préremplis avec $event->champ -->

    <input type="text" name="title" value="{{ $event->title }}" required>
    <input type="text" name="slug" value="{{ $event->slug }}" required>

    <select name="category" required>
        <option value="cinema" {{ $event->category === 'cinema' ? 'selected' : '' }}>Cinéma</option>
        <option value="concert" {{ $event->category === 'concert' ? 'selected' : '' }}>Concert</option>
        <option value="festival" {{ $event->category === 'festival' ? 'selected' : '' }}>Festival</option>
        <option value="libre" {{ $event->category === 'libre' ? 'selected' : '' }}>Libre</option>
    </select>

    <!-- Organisateur, lieu, salle, type, dates, prix, etc. -->
    <!-- identiques à create.blade.php mais avec `selected` -->
     <div class="mb-3">
        <label for="trailer_url" class="form-label">Lien du trailer (YouTube, Vimeo...)</label>
        <input type="text" name="trailer_url" id="trailer_url" class="form-control" value="{{ old('trailer_url', $event->trailer_url ?? '') }}">
    </div>


    <button type="submit" class="btn btn-warning">Mettre à jour</button>
</form>
@endsection