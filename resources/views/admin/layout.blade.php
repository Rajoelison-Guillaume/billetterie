@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav class="mb-4">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.events.index') }}">Événements</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.organizers.index') }}">Organisateurs</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.ticket-types.index') }}">Types de billets</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.index') }}">Commandes</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.payments.index') }}">Paiements</a></li>
        </ul>
    </nav>

    @yield('admin-content')
</div>
@endsection
