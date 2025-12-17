@extends('layouts.app')

@section('title','Réinitialiser le mot de passe')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header text-center">
                    <h3 class="fw-bold text-info">Réinitialiser le mot de passe</h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input id="email" type="email" class="form-control" name="email" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Envoyer le lien</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
