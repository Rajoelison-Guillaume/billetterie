@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">💳 Supervision des paiements</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-dark table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Commande</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Provider</th>
                        <th>Référence</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>#{{ $payment->order_id }}</td>
                            <td>{{ $payment->order->user->name }}</td>
                            <td>{{ number_format($payment->amount, 0, ',', ' ') }} Ar</td>
                            <td>{{ ucfirst($payment->method) }}</td>
                            <td>{{ $payment->provider ?? '-' }}</td>
                            <td>{{ $payment->provider_ref ?? '-' }}</td>
                            <td>
                                @if($payment->status === 'success')
                                    <span class="badge bg-success">Payé</span>
                                @elseif($payment->status === 'pending')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @elseif($payment->status === 'failed')
                                    <span class="badge bg-danger">Échoué</span>
                                @endif
                            </td>
                            <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-info btn-sm">👁️ Voir</a>
                                <form action="{{ route('admin.payments.failed', $payment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-danger btn-sm">❌ Marquer échoué</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">Aucun paiement trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
