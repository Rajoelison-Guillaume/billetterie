@extends('layouts.admin')

@section('title', 'Détail de l’organisateur')

@section('content')
<h2 class="text-primary fw-bold mb-4">👁️ Détail de l’organisateur</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4 text-center">
                @if($organizer->logo)
                    <img src="{{ asset('storage/' . $organizer->logo) }}" 
                         alt="Logo de {{ $organizer->name }}" 
                         class="img-fluid rounded shadow" 
                         style="max-height: 150px;">
                @else
                    <span class="text-muted">Aucun logo disponible</span>
                @endif
            </div>
            <div class="col-md-8">
                <p><strong>Nom :</strong> {{ $organizer->name }}</p>
                <p><strong>Email :</strong> {{ $organizer->contact_email ?? '-' }}</p>
                <p><strong>Téléphone :</strong> {{ $organizer->contact_phone ?? '-' }}</p>
                <p><strong>Description :</strong> {{ $organizer->description ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.organizers.edit', $organizer->id) }}" class="btn btn-warning">✏️ Modifier</a>
    <form action="{{ route('admin.organizers.destroy', $organizer->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
                onclick="return confirm('Voulez-vous vraiment supprimer cet organisateur ?')">
            🗑️ Supprimer
        </button>
    </form>
    <a href="{{ route('admin.organizers.index') }}" class="btn btn-secondary">⬅️ Retour</a>
</div>
@endsection
