@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">👥 Gestion des organisateurs</h2>

<a href="{{ route('admin.organizers.create') }}" class="btn btn-success mb-3">➕ Ajouter un organisateur</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Description</th>
            <th>Logo</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($organizers as $organizer)
            <tr>
                <td>{{ $organizer->id }}</td>
                <td>{{ $organizer->name }}</td>
                <td>{{ $organizer->contact_email ?? '-' }}</td>
                <td>{{ $organizer->contact_phone ?? '-' }}</td>
                <td>{{ Str::limit($organizer->description, 50) }}</td>
                <td>
                    @if($organizer->logo)
                        <img src="{{ asset('storage/' . $organizer->logo) }}" alt="Logo" style="max-height: 60px;">
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.organizers.show', $organizer->id) }}" class="btn btn-info btn-sm">👁️ Voir</a>
                    <a href="{{ route('admin.organizers.edit', $organizer->id) }}" class="btn btn-warning btn-sm">✏️ Modifier</a>
                    <form action="{{ route('admin.organizers.destroy', $organizer->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Voulez-vous vraiment supprimer cet organisateur ?')">
                            🗑️ Supprimer
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">Aucun organisateur trouvé</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $organizers->links() }}
</div>
@endsection
