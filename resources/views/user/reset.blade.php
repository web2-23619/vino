@extends('layouts.app')
@section('title', 'Reset Password')
@section('js', asset('js/pages/reset.js'))
@section('content')

<!-- Formulaire Réinitialiser Mot de Passe -->
<section class="page">
	@if(!$errors->isEmpty())
	<div class="alerte alerte_erreur">
		<ul>
			@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
		</ul><button data-js-action="fermer">x</button>
	</div>
	@endif
	<header>
		<h2>Réinitialiser le mot de passe</h2>
	</header>
	<form class="form" method="POST">
		@csrf
		@method('put')

		<!-- Champ Nouveau Mot de Passe -->
		<div class="form-group">
			<label for="password">Nouveau mot de passe</label>
			<input type="password" id="password" name="password" placeholder="Nouveau mot de passe" required>
		</div>

		<!-- Champ Confirmation Mot de Passe -->
		<div class="form-group">
			<label for="password_confirmation">Confirmer le mot de passe</label>
			<input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirmez votre mot de passe" required>
		</div>

		<!-- Bouton Soumettre -->
		<div class="page-prompt">
			<button type="submit" class="btn">Sauvegarder</button>
		</div>
	</form>
</section>
@endsection