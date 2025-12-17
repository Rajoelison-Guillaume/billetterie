@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">➕ Créer un nouvel événement</h2>
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


<form action="{{ route('admin.events.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Titre</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Catégorie</label>
        <select name="category" class="form-select" required>
            <option value="cinema">Cinéma</option>
            <option value="libre">Événement libre</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Organisateur</label>
        <select name="organizer_id" class="form-select" required>
            @foreach($organizers as $organizer)
                <option value="{{ $organizer->id }}">{{ $organizer->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Lieu</label>
        <select name="venue_id" class="form-select" required>
            @foreach($venues as $venue)
                <option value="{{ $venue->id }}">{{ $venue->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Date début</label>
        <input type="date" name="start_date" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Date fin</label>
        <input type="date" name="end_date" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Prix</label>
        <input type="number" name="price" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Créer</button>
</form>
@endsection
