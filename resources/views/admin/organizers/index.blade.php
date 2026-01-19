@extends('layouts.admin')

@section('title', 'Gestion des organisateurs')

@section('content')
<h2 class="text-primary fw-bold mb-4">📋 Liste des organisateurs</h2>

<div class="mb-3 text-end">
    <a href="{{ route('admin.organizers.create') }}" class="btn btn-success">➕ Ajouter un organisateur</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Logo</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Description</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($organizers as $organizer)
                <tr>
                    <td>{{ $organizer->id }}</td>
                    <td class="text-center" style="width: 120px;">
                        @if($organizer->logo)
                            <img src="{{ asset('storage/' . $organizer->logo) }}" 
                                 alt="Logo" 
                                 class="img-fluid rounded shadow-sm" 
                                 style="max-height: 60px;">
                        @else
                            <span class="text-muted">Aucun</span>
                        @endif
                    </td>
                    <td>{{ $organizer->name }}</td>
                    <td>{{ $organizer->contact_email ?? '-' }}</td>
                    <td>{{ $organizer->contact_phone ?? '-' }}</td>
                    <td>{{ $organizer->description ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.organizers.show', $organizer->id) }}" class="btn btn-sm btn-info">👁️ Voir</a>
                        <a href="{{ route('admin.organizers.edit', $organizer->id) }}" class="btn btn-sm btn-warning">✏️ Modifier</a>
                        <form action="{{ route('admin.organizers.destroy', $organizer->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Supprimer cet organisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">🗑️ Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Aucun organisateur enregistré.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
