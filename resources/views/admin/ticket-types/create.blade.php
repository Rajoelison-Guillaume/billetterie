@extends('layouts.admin')

@section('content')
<h2 class="text-primary fw-bold mb-4">Ajouter un type de billet</h2>
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

<form action="{{ route('admin.ticket-types.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nom</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Prix</label>
        <input type="number" name="price" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Quantité</label>
        <input type="number" name="quantity" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Ajouter</button>
</form>
@endsection
