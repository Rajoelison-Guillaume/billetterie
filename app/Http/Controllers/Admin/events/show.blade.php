@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Détails de l’événement</h2>

    <div class="card">
        <div class="card-header">
            {{ $event->title }}
        </div>
        <div class="card-body">
            <p><strong>Slug :</strong> {{ $event->slug }}</p>
            <p><strong>Catégorie :</strong> {{ ucfirst($event->category) }}</p>
            <p><strong>Date début :</strong> {{ $event->start_date }}</p>
            <p><strong>Date fin :</strong> {{ $event->end_date ?? 'Non définie' }}</p>
            <p><strong>Description :</strong> {{ $event->description }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-warning">Modifier</a>
            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button class="btn btn-danger">Supprimer</button>
            </form>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>
@endsection
