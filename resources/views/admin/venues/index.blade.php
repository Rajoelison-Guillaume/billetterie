@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">📍 Liste des lieux</h2>

<a href="{{ route('admin.venues.create') }}" class="btn btn-success mb-3">➕ Ajouter un lieu</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Adresse</th>
            <th>Type</th>
            <th>Capacité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($venues as $venue)
            <tr>
                <td>{{ $venue->id }}</td>
                <td>{{ $venue->name }}</td>
                <td>{{ $venue->address ?? '-' }}</td>
                <td>
                    @switch($venue->type)
                        @case('hall') Salle polyvalente @break
                        @case('cinema') Cinéma @break
                        @case('plein_air') Plein air @break
                        @case('stade') Stade @break
                        @case('theatre') Théâtre @break
                        @default {{ $venue->type }}
                    @endswitch
                </td>
                <td>{{ $venue->capacity }}</td>
                <td>
                    <a href="{{ route('admin.venues.show', $venue->id) }}" class="btn btn-info btn-sm">👁️ Voir</a>
                    <a href="{{ route('admin.venues.edit', $venue->id) }}" class="btn btn-warning btn-sm">✏️ Modifier</a>
                    <form action="{{ route('admin.venues.destroy', $venue->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Voulez-vous vraiment supprimer ce lieu ?')">
                            🗑️ Supprimer
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Aucun lieu trouvé</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $venues->links() }}
</div>
@endsection
