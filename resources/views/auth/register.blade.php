@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">📝 Inscription</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Numéro de téléphone</label>
            <div class="input-group">
                <span class="input-group-text">+261</span>
                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required placeholder="341795207">
            </div>
            <small class="text-muted">Entrez les 9 chiffres après +261 (ex: 33xxxxxxx). Préfixes valides : 32, 33, 34, 37, 38.</small>
        </div>

        <div class="mb-3 position-relative">
            <label for="password" class="form-label">Mot de passe</label>
            <input id="password" type="password" class="form-control" name="password" required>
            <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
        </div>

        <div class="mb-3 position-relative">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
            <span class="toggle-password" onclick="togglePassword('password_confirmation')">👁️</span>
        </div>

        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection

@section('styles')
<style>
.toggle-password {
    position: absolute;
    top: 38px;
    right: 12px;
    cursor: pointer;
    font-size: 18px;
}
</style>
@endsection
