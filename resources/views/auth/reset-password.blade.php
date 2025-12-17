@extends('layouts.app')

@section('title','Nouveau mot de passe')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header text-center">
                    <h3 class="fw-bold text-info">Définir un nouveau mot de passe</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Réinitialiser</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
