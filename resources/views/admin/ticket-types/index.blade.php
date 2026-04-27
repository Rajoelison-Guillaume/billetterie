@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">🎟️ Gestion des types de billets</h2>

    <div class="mb-3">
        <a href="{{ route('admin.ticket-types.create') }}" class="btn btn-primary">➕ Ajouter un type de billet</a>
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
                                <a href="{{ route('admin.ticket-types.show', $ticketType->id) }}" class="btn btn-info btn-sm">👁️ Voir</a>
                                <a href="{{ route('admin.ticket-types.edit', $ticketType->id) }}" class="btn btn-warning btn-sm">✏️ Modifier</a>
                                <form action="{{ route('admin.ticket-types.destroy', $ticketType->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer ce type de billet ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑️ Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun type de billet trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $ticketTypes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
