@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Créer un nouvel événement</h2>

    <form action="{{ route('admin.events.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Catégorie</label>
            <select name="category" id="category" class="form-select" required>
                <option value="cinema">Cinéma</option>
                <option value="expo">Exposition</option>
                <option value="concert">Concert</option>
                <option value="conference">Conférence</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Date début</label>
            <input type="datetime-local" name="start_date" id="start_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Date fin</label>
            <input type="datetime-local" name="end_date" id="end_date" class="form-control">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Créer</button>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
