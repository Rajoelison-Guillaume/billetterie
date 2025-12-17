@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">Gestion des événements</h2>
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


<a href="{{ route('admin.events.create') }}" class="btn btn-success mb-3">➕ Créer un événement</a>

<table class="table table-dark table-striped align-middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Catégorie</th>
            <th>Date début</th>
            <th>Date fin</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($events as $event)
        <tr>
            <td>{{ $event->id }}</td>
            <td>{{ $event->title }}</td>
            <td>{{ ucfirst($event->category) }}</td>
            <td>{{ $event->start_date->format('d/m/Y') }}</td>
            <td>{{ $event->end_date ? $event->end_date->format('d/m/Y') : '-' }}</td>
            <td>{{ $event->is_active ? 'Actif' : 'Inactif' }}</td>
            <td>
                <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-info btn-sm">👁️ Voir</a>
                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-warning btn-sm">✏️ Modifier</a>
                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cet événement ?')">🗑️ Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-3">
    {{ $events->links() }}
</div>
@endsection
