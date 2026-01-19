@extends('layouts.admin')

@section('title', 'Modifier un organisateur')

@section('content')
<h2 class="text-warning fw-bold mb-4">✏️ Modifier un organisateur</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.organizers.update', $organizer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nom *</label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $organizer->name) }}">
            </div>

            <div class="mb-3">
                <label for="contact_email" class="form-label">Email</label>
                <input type="email" name="contact_email" id="contact_email" class="form-control" value="{{ old('contact_email', $organizer->contact_email) }}">
            </div>

            <div class="mb-3">
                <label for="contact_phone" class="form-label">Téléphone</label>
                <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone', $organizer->contact_phone) }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $organizer->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                @if($organizer->logo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $organizer->logo) }}" alt="Logo actuel" class="img-fluid rounded shadow" style="max-height: 120px;">
                    </div>
                @endif
                <input type="file" name="logo" id="logo" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">💾 Mettre à jour</button>
            <a href="{{ route('admin.organizers.index') }}" class="btn btn-secondary">⬅️ Annuler</a>
        </form>
    </div>
</div>
@endsection
