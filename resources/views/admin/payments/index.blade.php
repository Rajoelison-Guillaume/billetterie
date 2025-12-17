@extends('layouts.admin')

@section('content')
<h1>Paiements</h1>
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

<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Montant</th>
            <th>Méthode</th>
            <th>Statut</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
        <tr>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->order->user->name }}</td>
            <td>{{ number_format($payment->amount, 0, ',', ' ') }} Ar</td>
            <td>{{ strtoupper($payment->method) }}</td>
            <td>{{ ucfirst($payment->status) }}</td>
            <td>{{ $payment->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $payments->links() }}
@endsection
