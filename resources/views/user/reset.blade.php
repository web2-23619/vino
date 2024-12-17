@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')

<!-- Affichage des erreurs -->
@if(!$errors->isEmpty())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
</div>
@endif

<!-- Formulaire Réinitialiser Mot de Passe -->
<section>
    <header>
        <h2>Réinitialiser le mot de passe</h2>
    </header>
    <form action="" method="POST">
        @csrf
        @method('put')

        <!-- Champ Nouveau Mot de Passe -->
        <div>
            <label for="password">Nouveau mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Entrez votre nouveau mot de passe" required>
        </div>

        <!-- Champ Confirmation Mot de Passe -->
        <div>
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirmez votre mot de passe" required>
        </div>

        <!-- Bouton Soumettre -->
        <div>
            <button type="submit">Réinitialiser le mot de passe</button>
        </div>
    </form>
</section>
@endsection
