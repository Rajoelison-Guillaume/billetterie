@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">🧾 Historique des commandes</h2>

    {{-- Messages de succès / erreurs --}}
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

    {{-- Formulaire de recherche avancée --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label fw-bold">Code</label>
                    <input type="text" name="id" class="form-control" placeholder="ID commande" value="{{ request('id') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">QR Code</label>
                    <input type="text" name="qr_code" class="form-control" placeholder="QR code" value="{{ request('qr_code') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Nom de l'événement</label>
                    <input type="text" name="event" class="form-control" placeholder="Événement" value="{{ request('event') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Lieu</label>
                    <input type="text" name="venue" class="form-control" placeholder="Lieu" value="{{ request('venue') }}">
                </div>

                {{-- Recherche entre deux dates --}}
                <div class="col-md-2">
                    <label class="form-label fw-bold">Date début</label>
                    <input type="date" name="date_start" class="form-control" value="{{ request('date_start') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Date fin</label>
                    <input type="date" name="date_end" class="form-control" value="{{ request('date_end') }}">
                </div>

                <div class="col-md-12 d-flex justify-content-between mt-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">♻️ Réinitialiser</a>
                    </div>
                    <a href="{{ route('admin.orders.export.pdf', request()->all()) }}" class="btn btn-danger">📄 Export PDF</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tableau des commandes --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Utilisateur</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Événement</th>
                        <th>Date</th>
                        <th>Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td>{{ number_format($order->total_amount, 0, ',', ' ') }} Ar</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : 'success' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $order->tickets->count() ? $order->tickets->first()->event->title : '-' }}
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $order->payment->status ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">👁️ Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Aucune commande trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
