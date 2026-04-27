    @extends('layouts.admin')

    @section('content')
    <div class="container py-4">
        <h2 class="fw-bold text-primary mb-4">{{ $event->title }}</h2>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">📌 Informations générales</h5>
                <p><strong>Slug :</strong> {{ $event->slug }}</p>
                <p><strong>Catégorie :</strong> {{ ucfirst($event->category) }}</p>
                <p><strong>Type d'événement :</strong> {{ $event->eventType?->label ?? 'Non défini' }}</p>
                <p><strong>Organisateur :</strong> {{ $event->organizer?->name ?? 'Non défini' }}</p>
                <p><strong>Lieu :</strong> {{ $event->venue?->name ?? 'Non défini' }}</p>
                <p><strong>Salle :</strong> {{ $event->room?->name ?? 'Non défini' }}</p>
                <p><strong>Dates :</strong> 
                    {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }} 
                    - 
                    {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y H:i') }}
                </p>
                <p><strong>Prix du billet :</strong> {{ number_format($event->ticket_price, 0, ',', ' ') }} Ar</p>
                <p><strong>Actif :</strong> {{ $event->is_active ? 'Oui' : 'Non' }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">📝 Description</h5>
                <p>{{ $event->description ?? 'Aucune description disponible.' }}</p>
            </div>
        </div>

        @if($event->trailer_url)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">🎥 Trailer</h5>
                    <div class="ratio ratio-16x9">
                        <iframe 
                            src="{{ str_replace('watch?v=', 'embed/', $event->trailer_url) }}" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        @endif


        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">← Retour à la liste des événements</a>
            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-warning">✏ Modifier</a>
        </div>
    </div>
    @endsection
