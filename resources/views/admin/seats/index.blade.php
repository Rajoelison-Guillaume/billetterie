@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">🪑 Gestion des sièges</h2>

    <a href="{{ route('admin.seats.create') }}" class="btn btn-success mb-3">➕ Ajouter un siège</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Salle</th>
                <th>Rangée</th>
                <th>Numéro</th>
                <th>Accessible</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($seats as $seat)
                <tr>
                    <td>{{ $seat->id }}</td>
                    <td>{{ $seat->room->name ?? '-' }}</td>
                    <td>{{ $seat->row }}</td>
                    <td>{{ $seat->number }}</td>
                    <td>{{ $seat->accessible ? 'Oui' : 'Non' }}</td>
                    <td>
                        <a href="{{ route('admin.seats.edit', $seat->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.seats.destroy', $seat->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce siège ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
