@extends('layouts.app')
@section('js', asset('js/pages/forgotpassword.js'))
@section('title', 'Mot de passe oublié')
@section('content')

<section class="login-page">
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
	<header class="login-header">
		<h2>Mot de passe oublié</h2>
	</header>
	<form method="POST" action="{{ route('user.email') }}" class="login-form form">
		@csrf
		<div class="form-group">
			<label for="email" class="form-label">Email</label>
			<input type="email" id="email" name="email" placeholder="Entrez votre email" value="{{ old('email') }}" required>
		</div>
		<button type="submit" class="btn">Envoyer</button>
	</form>
</section>
@endsection