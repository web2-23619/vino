@extends('layouts.app')
@section('title', 'Ajouter cellier')
@section('content')
<section class="page">
	<header>
		<h2>Ajouter un Cellier</h2>
	</header>

	<form action="{{ route('cellar.store') }}" method="POST" class="form">
		@csrf
		<div class="form-group">
			<label for="name">Nom:</label>
			<input type="text" name="name" id="name" value="{{ old('name') }}" required>
			@error('name')
			<div>{{ $message }}</div>
			@enderror
		</div>
		<!-- <div class="form-group">
			<label for="quantity">Quantité:</label>
			<input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" required>
			@error('quantity')
			<div>{{ $message }}</div>
			@enderror
		</div> -->
		<button type="submit" class="btn">Sauvegarder</button>
	</form>
</section>
@endsection