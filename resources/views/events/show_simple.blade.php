@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">{{ $event->title }}</h2>
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

    <p class="lead text-light">{{ $event->description }}</p>
<form action="{{ route('client.reservation.store') }}" method="POST">
    @csrf
    <input type="hidden" name="event_id" value="{{ $event->id }}">
    <input type="hidden" name="price" value="{{ $event->ticket_price }}">

    <p>Billet simple pour l’événement libre.</p>

    @include('components.payment_fields')

    <button type="submit" class="btn btn-primary mt-3">Réserver</button>
</form>
</div>
@endsection
