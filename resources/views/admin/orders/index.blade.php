@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">🧾 Historique des commandes</h2>
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


{{-- Formulaire de recherche avancée --}}
<form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 mb-4">
    <div class="col-md-2">
        <input type="text" name="id" class="form-control" placeholder="ID commande" value="{{ request('id') }}">
    </div>
    <div class="col-md-2">
        <input type="text" name="qr" class="form-control" placeholder="QR code" value="{{ request('qr') }}">
    </div>
    <div class="col-md-2">
        <input type="text" name="event" class="form-control" placeholder="Événement" value="{{ request('event') }}">
    </div>
    <div class="col-md-2">
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
    </div>
    <div class="col-md-2">
        <input type="text" name="location" class="form-control" placeholder="Lieu" value="{{ request('location') }}">
    </div>
    <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Rechercher</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Réinitialiser</a>
    </div>
</form>


{{-- Tableau des commandes --}}
<table class="table table-dark table-striped align-middle">
    <thead>
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
            <td><span class="badge bg-{{ $order->status === 'pending' ? 'warning' : 'success' }}">
                {{ ucfirst($order->status) }}
            </span></td>
            <td>
                @if($order->tickets->count())
                    {{ $order->tickets->first()->event->title }}
                @else
                    -
                @endif
            </td>
            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $order->payment->status ?? '-' }}</td>
            <td>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">👁️ Voir</a>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center">Aucune commande trouvée.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $orders->links() }}
</div>
@endsection
