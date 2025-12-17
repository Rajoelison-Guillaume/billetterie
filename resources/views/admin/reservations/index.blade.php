@extends('layouts.admin')

@section('content')
<h1>Réservations</h1>
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
            <th>Utilisateur</th>
            <th>Événement</th>
            <th>Siège</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reservations as $reservation)
            <tr>
                <td>{{ $reservation->id }}</td>
                <td>{{ $reservation->user->name }}</td>
                <td>{{ $reservation->showtime->event->title ?? '—' }}</td>
                <td>{{ $reservation->seat->label ?? 'Libre' }}</td>
                <td>{{ $reservation->created_at->format('d/m/Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $reservations->links() }}
@endsection
