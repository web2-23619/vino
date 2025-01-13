@extends('layouts.app')
@section('title', 'Enregistrement')
@section('js', asset('js/pages/createuser.js'))
@section('content')

<section class="register-page">
	<h2 class="register-header">Enregistrement</h2>

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

	<form class="register-form form" action="{{ route('user.store') }}" method="POST">
		@csrf
		<div class="form-group">
			<label for="username">Nom</label>
			<input type="text" id="username" name="username" placeholder="Votre nom" value="{{ old('username') }}" required>
		</div>
		<div class="form-group">
			<label for="email">Email</label>
			<input type="email" id="email" name="email" placeholder="Votre email" value="{{ old('email') }}" required>
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