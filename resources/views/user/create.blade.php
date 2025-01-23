@extends('layouts.app')
@section('title', 'Enregistrement')
@section('js', asset('js/pages/createuser.js'))
@section('content')

<section class="page">
    <header data-js="header">
        <h2 class="register-header">Enregistrement</h2>
    </header>

    <!-- Blade-rendered errors for fallback -->
    @if(!$errors->isEmpty())
    <template id="alerte">
        <div class="alerte alerte_erreur">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button data-js-action="fermer">x</button>
        </div>
    </template>
    @endif

    <form class="form" action="{{ route('user.store') }}" method="POST">
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
		<div class="form-group">
            <label for="password"> Confirmer votre mot de passe</label>
<input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirmez votre mot de passe" required>
        </div>
        <div class="page-prompt">
            <button type="submit" class="btn">Inscrire</button>
        </div>
    </form>
</section>

@endsection
