@extends('layouts.admin')

@section('content')
<h2>{{ $event->title }}</h2>
<p><strong>Catégorie :</strong> {{ ucfirst($event->category) }}</p>
<p><strong>Organisateur :</strong> {{ $event->organizer->name ?? '-' }}</p>
<p><strong>Lieu :</strong> {{ $event->venue->name ?? '-' }}</p>
<p><strong>Salle :</strong> {{ $event->room->name ?? '-' }}</p>
<p><strong>Type :</strong> {{ $event->eventType->name ?? '-' }}</p>
<p><strong>Dates :</strong> {{ $event->start_date->format('d/m/Y') }} - {{ $event->end_date->format('d/m/Y') }}</p>
<p><strong>Prix :</strong> {{ number_format($event->ticket_price, 0, ',', ' ') }} Ar</p>
<p><strong>Max par utilisateur :</strong> {{ $event->max_per_user }}</p>
<p><strong>Actif :</strong> {{ $event->is_active ? 'Oui' : 'Non' }}</p>
<p><strong>Description :</strong> {{ $event->description }}</p>
<a href="{{ route('admin.events.index') }}" class="btn btn-secondary mt-3">← Retour à la liste des événements</a>
@endsection