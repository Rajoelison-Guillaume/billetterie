@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">👁️ Détail de l’organisateur</h2>

<div class="card">
    <div class="card-body">
        <p><strong>Nom :</strong> {{ $organizer->name }}</p>
        <p><strong>Email :</strong> {{ $organizer->contact_email ?? '-' }}</p>
        <p><strong>Téléphone :</strong> {{ $organizer->contact_phone ?? '-' }}</p>
        <p><strong>Description :</strong> {{ $organizer->description ?? '-' }}</p>
        @if($organizer->logo)
            <p><strong>Logo :</strong></p>
            <img src="{{ asset('storage/' . $organizer->logo) }}" alt="Logo" style="max-height: 120px;">
        @endif
        <p><strong>Créé le :</strong> {{ $organizer->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Mis à jour le :</strong> {{ $organizer->updated_at->format('d/m/Y H:i') }}</p>
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
