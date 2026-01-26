@extends('layouts.admin')

@section('content')
<h2>Gestion des sièges</h2>
<a href="{{ route('admin.seats.create') }}" class="btn btn-primary">Ajouter un siège</a>

<table class="table mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Salle</th>
            <th>Rangée</th>
            <th>Numéro</th>
            <th>Accessible</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($seats as $seat)
        <tr>
            <td>{{ $seat->id }}</td>
            <td>{{ $seat->room->name }}</td>
            <td>{{ $seat->row_label }}</td>
            <td>{{ $seat->seat_number }}</td>
            <td>{{ $seat->is_accessible ? 'Oui' : 'Non' }}</td>
            <td>
                <a href="{{ route('admin.seats.edit',$seat) }}" class="btn btn-warning">Modifier</a>
                <form action="{{ route('admin.seats.destroy',$seat) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $seats->links() }}
@endsection
