@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">📋 Liste des événements</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">➕ Créer un nouvel événement</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Type</th>
                        <th>Organisateur</th>
                        <th>Lieu</th>
                        <th>Salle</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Prix</th>
                        <th>Actif</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td>{{ $event->title }}</td>
                            <td>{{ ucfirst($event->category) }}</td>
                            <td>{{ $event->eventType?->label ?? '—' }}</td>
                            <td>{{ $event->organizer?->name ?? '—' }}</td>
                            <td>{{ $event->venue?->name ?? '—' }}</td>
                            <td>{{ $event->room?->name ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($event->ticket_price, 0, ',', ' ') }} Ar</td>
                            <td>
                                @if($event->is_active)
                                    <span class="badge bg-success">Oui</span>
                                @else
                                    <span class="badge bg-danger">Non</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-sm btn-info">👁 Voir</a>
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-warning">✏ Modifier</a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet événement ?')">🗑 Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">Aucun événement trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
