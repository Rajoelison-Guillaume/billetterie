@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">✏️ Modifier un organisateur</h2>
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

<form action="{{ route('admin.organizers.update', $organizer->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Nom</label>
        <input type="text" name="name" class="form-control" value="{{ $organizer->name }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="contact_email" class="form-control" value="{{ $organizer->contact_email }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Téléphone</label>
        <input type="text" name="contact_phone" class="form-control" value="{{ $organizer->contact_phone }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control">{{ $organizer->description }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Logo</label>
        @if($organizer->logo)
            <img src="{{ asset('storage/'.$organizer->logo) }}" alt="Logo" width="80" class="mb-2">
        @endif
        <input type="file" name="logo" class="form-control">
    </div>

    <button type="submit" class="btn btn-warning">Mettre à jour</button>
</form>
@endsection
