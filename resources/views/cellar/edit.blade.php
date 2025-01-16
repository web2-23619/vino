@extends('layouts.app')
@section('title', 'Modifier cellier')
@section('content')
<section class="page">
	<header>
		<h2>Modifier le Cellier</h2>
	</header>
	<form action="{{ route('cellar.update', $cellar->id) }}" method="POST" class="form">
		@csrf
		@method('PUT')
		<div class="form-group">
			<label for="name">Nom:</label>
			<input type="text" name="name" id="name" value="{{ old('name', $cellar->name) }}" required>
			@error('name')
			<div>{{ $message }}</div>
			@enderror
		</div>
		<!-- <div class="form-group">
			<label for="quantity">Quantité:</label>
			<input type="number" name="quantity" id="quantity" value="{{ old('quantity', $cellar->quantity) }}" required>
			@error('quantity')
			<div>{{ $message }}</div>
			@enderror
		</div> -->
		<button type="submit" class="btn">Modifier</button>
	</form>
</section>
@endsection