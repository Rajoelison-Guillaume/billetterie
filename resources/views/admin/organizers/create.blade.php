@extends('layouts.admin')

@section('title', 'Créer un organisateur')

@section('content')
<h2 class="text-success fw-bold mb-4">➕ Créer un organisateur</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.organizers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nom *</label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label for="contact_email" class="form-label">Email</label>
                <input type="email" name="contact_email" id="contact_email" class="form-control" value="{{ old('contact_email') }}">
            </div>

            <div class="mb-3">
                <label for="contact_phone" class="form-label">Téléphone</label>
                <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone') }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                <input type="file" name="logo" id="logo" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
            <a href="{{ route('admin.organizers.index') }}" class="btn btn-secondary">⬅️ Annuler</a>
        </form>
    </div>
</div>
@endsection
