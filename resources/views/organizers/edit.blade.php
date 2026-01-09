@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">📝 Modifier un organisateur</h2>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.organizers.update', $organizer->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Nom</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $organizer->name) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $organizer->contact_email) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Téléphone</label>
        <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $organizer->contact_phone) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $organizer->description) }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Logo</label>
        <input type="file" name="logo" class="form-control">
        @if($organizer->logo)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $organizer->logo) }}" alt="Logo actuel" style="max-height: 100px;">
            </div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary">💾 Mettre à jour</button>
    <a href="{{ route('admin.organizers.index') }}" class="btn btn-secondary">⬅️ Annuler</a>
</form>
@endsection
