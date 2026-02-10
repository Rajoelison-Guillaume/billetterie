@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">💳 Historique des paiements</h2>

    {{-- Message de succès --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Message d'erreurs --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($payments->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-dark align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Commande</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Provider</th>
                        <th>Référence</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>#{{ $payment->order->id }}</td>
                            <td>{{ number_format($payment->amount, 0, ',', ' ') }} Ar</td>
                            <td>{{ ucfirst($payment->method) }}</td>
                            <td>{{ $payment->provider ?? '-' }}</td>
                            <td>{{ $payment->provider_ref ?? '-' }}</td>
                            <td>
                                @if($payment->status === 'success' || $payment->status === 'paid')
                                    <span class="badge bg-success">Confirmé</span>
                                @elseif($payment->status === 'pending')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @else
                                    <span class="badge bg-danger">Échoué</span>
                                @endif
                            </td>
                            <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $payments->links() }}
        </div>
    @else
        <p class="text-muted">Aucun paiement enregistré pour le moment.</p>
    @endif
</div>
@endsection
