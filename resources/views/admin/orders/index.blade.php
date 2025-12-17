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
        <label class="form-label">ID</label>
        <input type="text" name="id" value="{{ request('id') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label class="form-label">QR Code</label>
        <input type="text" name="qr_code" value="{{ request('qr_code') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Événement</label>
        <input type="text" name="event" value="{{ request('event') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label class="form-label">Date</label>
        <input type="date" name="date" value="{{ request('date') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Lieu</label>
        <input type="text" name="venue" value="{{ request('venue') }}" class="form-control">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-info">🔍 Rechercher</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">♻️ Réinitialiser</a>
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
