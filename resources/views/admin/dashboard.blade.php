@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">📊 Tableau de bord Admin</h2>
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

    <div class="row">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Événements</h5>
                    <p>{{ $eventsCount }}</p>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-info">Gérer</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Billets</h5>
                    <p>{{ $ticketsCount }}</p>
                    <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-sm btn-info">Voir</a>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Commandes</h5>
                    <p>{{ $ordersCount }}</p>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-info">Voir</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Réservations</h5>
                    <p>{{ $reservationsCount }}</p>
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-info">Voir</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
