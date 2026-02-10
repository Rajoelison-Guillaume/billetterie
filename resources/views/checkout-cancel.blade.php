    @extends('layouts.app')

    @section('content')
    <div class="container py-5 text-center">
        <h1 class="text-danger fw-bold mb-4">❌ Paiement annulé</h1>
        <p class="lead">Le paiement n’a pas été finalisé. Vous pouvez réessayer ou choisir un autre mode de paiement.</p>

        <a href="{{ route('events.index') }}" class="btn btn-warning mt-3">
            Retour aux événements
        </a>
    </div>
    @endsection
