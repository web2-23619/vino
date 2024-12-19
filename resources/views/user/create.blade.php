@extends('layouts.app')
@section('title', 'Enregistrement')
@section('content')

<section class="register-page">
    <h2 class="register-header">Enregistrement</h2>
    @if(session('success'))
        <div class="alerte alerte_succes">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="alerte alerte_erreur">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    <form class="register-form" action="{{ route('user.store') }}" method="POST">
        @csrf 
        <div class="form-group">
            <label for="username">Nom</label>
            <input type="text" id="username" name="username" placeholder="Votre nom" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Votre email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
        </div>
        <div>
            <button type="submit" class="btn">Enregistrer</button>
        </div>
    </form>
</section>
@endsection
