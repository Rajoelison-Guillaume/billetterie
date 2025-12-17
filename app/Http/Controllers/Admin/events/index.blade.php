@extends('layouts.admin')

@section('content')
<h2>Gestion des événements</h2>
<a href="{{ route('admin.events.create') }}" class="btn btn-primary">Créer un événement</a>

<table class="table">
    <thead>
        <tr>
            <th>Titre</th>
            <th>Catégorie</th>
            <th>Date début</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($events as $event)
        <tr>
            <td>{{ $event->title }}</td>
            <td>{{ $event->category }}</td>
            <td>{{ $event->start_date }}</td>
            <td>
                <a href="{{ route('admin.events.edit',$event) }}" class="btn btn-warning">Modifier</a>
                <form action="{{ route('admin.events.destroy',$event) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
