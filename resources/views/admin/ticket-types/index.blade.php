@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">Gestion des types de billets</h2>

<a href="{{ route('admin.ticket-types.create') }}" class="btn btn-success mb-3">➕ Ajouter un type de billet</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Événement associé</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($ticketTypes as $ticketType)
            <tr>
                <td>{{ $ticketType->id }}</td>
                <td>{{ $ticketType->name }}</td>
                <td>{{ number_format($ticketType->price, 0, ',', ' ') }} Ar</td>
                <td>{{ $ticketType->quantity }}</td>
                <td>{{ $ticketType->event->title ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.ticket-types.show', $ticketType->id) }}" class="btn btn-info btn-sm">Voir</a>
                    <a href="{{ route('admin.ticket-types.edit', $ticketType->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('admin.ticket-types.destroy', $ticketType->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Voulez-vous vraiment supprimer ce type de billet ?')">
                            Supprimer
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Aucun type de billet trouvé</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
