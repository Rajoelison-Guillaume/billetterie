@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">🏟️ Liste des salles</h2>

    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary mb-3">➕ Ajouter une salle</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Lieu</th>
                <th>Capacité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rooms as $room)
                <tr>
                    <td>{{ $room->id }}</td>
                    <td>{{ $room->name }}</td>
                    <td>{{ $room->venue?->name ?? '-' }}</td>
                    <td>{{ $room->capacity }}</td>
                    <td>
                        <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-info btn-sm">👁️ Voir</a>
                        <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-warning btn-sm">✏️ Modifier</a>
                        <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette salle ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">🗑️ Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Aucune salle trouvée</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $rooms->links() }}
</div>
@endsection
