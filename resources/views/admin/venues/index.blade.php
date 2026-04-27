@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">📍 Liste des lieux</h2>

    <div class="mb-3">
        <a href="{{ route('admin.venues.create') }}" class="btn btn-primary">➕ Ajouter un lieu</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-dark table-striped align-middle">
                <thead>
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
                                <form action="{{ route('admin.venues.destroy', $venue->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer ce lieu ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑️ Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun lieu trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $venues->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
