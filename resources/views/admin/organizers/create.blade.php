@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">➕ Ajouter un organisateur</h2>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.organizers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nom</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Téléphone</label>
        <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Logo</label>
        <input type="file" name="logo" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Ajouter</button>
    <a href="{{ route('admin.organizers.index') }}" class="btn btn-secondary">⬅️ Annuler</a>
</form>
@endsection
