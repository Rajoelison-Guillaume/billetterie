@extends('layouts.admin')

@section('content')
<h2>Liste des lieux</h2>
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

<a href="{{ route('admin.venues.create') }}" class="btn btn-primary">Ajouter un lieu</a>

<table class="table">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Adresse</th>
            <th>Capacité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venues as $venue)
        <tr>
            <td>{{ $venue->name }}</td>
            <td>{{ $venue->address }}</td>
            <td>{{ $venue->capacity }}</td>
            <td>
                <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-warning">Modifier</a>
                <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $venues->links() }}
@endsection
