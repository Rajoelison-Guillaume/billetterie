@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">📋 Liste des réservations</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-dark table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Utilisateur</th>
                        <th>Événement</th>
                        <th>Sièges</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->id }}</td>
                            <td>{{ $reservation->user?->name ?? '—' }}</td>
                            <td>{{ $reservation->event?->title ?? '—' }}</td>
                            <td>
                                @if(!empty($reservation->seats))
                                    @foreach(explode(',', $reservation->seats) as $seat)
                                        <span class="badge bg-secondary">{{ $seat }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Aucun siège</span>
                                @endif
                            </td>
                            <td>{{ $reservation->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.reservations.show', $reservation->id) }}" class="btn btn-sm btn-info">
                                    👁 Voir détail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucune réservation trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
