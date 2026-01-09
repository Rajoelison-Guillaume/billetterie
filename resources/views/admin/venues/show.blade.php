@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">📍 Détail du lieu</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <p><strong>Nom du lieu :</strong> {{ $venue->name }}</p>
        <p><strong>Adresse :</strong> {{ $venue->address ?? '-' }}</p>
        <p><strong>Ville :</strong> {{ $venue->city ?? '-' }}</p>
        <p><strong>Type :</strong> 
            @switch($venue->type)
                @case('hall') Salle polyvalente @break
                @case('cinema') Cinéma @break
                @case('plein_air') Plein air @break
                @case('stade') Stade @break
                @case('theatre') Théâtre @break
                @default {{ $venue->type }}
            @endswitch
        </p>
        <p><strong>Capacité :</strong> {{ $venue->capacity }}</p>
        <p><strong>Description :</strong> {{ $venue->description ?? 'Aucune description' }}</p>
        <p><strong>Créé le :</strong> {{ $venue->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Mis à jour le :</strong> {{ $venue->updated_at->format('d/m/Y H:i') }}</p>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.venues.edit', $venue->id) }}" class="btn btn-warning">✏️ Modifier</a>
    <form action="{{ route('admin.venues.destroy', $venue->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
                onclick="return confirm('Voulez-vous vraiment supprimer ce lieu ?')">
            🗑️ Supprimer
        </button>
    </form>
    <a href="{{ route('admin.venues.index') }}" class="btn btn-secondary">⬅️ Retour à la liste</a>
</div>
@endsection
