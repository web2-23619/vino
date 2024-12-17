@extends('layouts.app')
@section('title', 'Modifier cellier')
@section('content')
@section('content')
<section>
	<header>
		<h2>Modifier le Cellier</h2>
	</header>
	<form action="{{ route('cellar.update', $cellar->id) }}" method="POST">
		@csrf
		@method('PUT')

		<label for="name">Nom:</label>
		<input type="text" name="name" id="name" value="{{ old('name', $cellar->name) }}" required>
		@error('name')
		<div>{{ $message }}</div>
		@enderror

		<label for="quantity">Quantité:</label>
		<input type="number" name="quantity" id="quantity" value="{{ old('quantity', $cellar->quantity) }}" required>
		@error('quantity')
		<div>{{ $message }}</div>
		@enderror

		<button type="submit">Modifier</button>
	</form>
</section>


@endsection