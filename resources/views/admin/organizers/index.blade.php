@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">Gestion des organisateurs</h2>
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

<a href="{{ route('admin.organizers.create') }}" class="btn btn-success mb-3">➕ Ajouter un organisateur</a>

<table class="table table-dark table-striped align-middle">
    <thead>
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
        @foreach($organizers as $organizer)
        <tr>
            <td>{{ $organizer->id }}</td>
            <td>{{ $organizer->name }}</td>
            <td>{{ $organizer->contact_email ?? '-' }}</td>
            <td>{{ $organizer->contact_phone ?? '-' }}</td>
            <td>{{ Str::limit($organizer->description, 50) }}</td>
            <td>
                @if($organizer->logo)
                    <img src="{{ asset('storage/'.$organizer->logo) }}" alt="Logo" width="50">
                @else
                    -
                @endif
            </td>
            <td>
                <a href="{{ route('admin.organizers.show', $organizer->id) }}" class="btn btn-info btn-sm">👁️ Voir</a>
                <a href="{{ route('admin.organizers.edit', $organizer->id) }}" class="btn btn-warning btn-sm">✏️ Modifier</a>
                <form action="{{ route('admin.organizers.destroy', $organizer->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cet organisateur ?')">🗑️ Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-3">
    {{ $organizers->links() }}
</div>
@endsection
