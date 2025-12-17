@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-center text-primary fw-bold mb-4">Ajouter un organisateur</h1>
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

    <form method="POST" action="{{ route('organizers.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label text-light">Nom</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label text-light">Description</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label for="logo" class="form-label text-light">Logo (optionnel)</label>
            <input type="file" name="logo" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
    </form>
</div>
@endsection
