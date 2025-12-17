@extends('layouts.app')

@section('title','Vérification email')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header text-center">
                    <h3 class="fw-bold text-info">Vérification de l’adresse email</h3>
                </div>
                <div class="card-body text-center">
                    @if (session('resent'))
                        <div class="alert alert-success">
                            Un nouveau lien de vérification a été envoyé à votre adresse email.
                        </div>
                    @endif

                    <p>Avant de continuer, veuillez vérifier votre email.</p>
                    <p>Si vous n’avez pas reçu l’email, cliquez ci-dessous :</p>

                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Renvoyer le lien</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
