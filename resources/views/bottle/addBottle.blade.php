@extends('layouts.app')
@section('js', asset('js/pages/listeAchatCellier.js'))
@section('title', 'Ajouter une bouteille')

@section('content')
<section class="page">
	<header>
		<h2 class="register-header">Ajouter la bouteille</h2>
	</header>
	<!-- Formulaire avec action conditionnelle -->
	<form class="form" id="addBottleForm" method="POST">
		@csrf
		<input type="hidden" name="bottle_id" value="{{ $bottle->id }}">
		<input type="hidden" name="cellar_id" id="cellar_id" value="{{ session('cellar_id') }}">
		<input type="hidden" name="source" value="{{ $source }}">

		<article class="card_bottle" data-js-key="{{ $bottle->id }}">
			<picture>
				<img src="{{ $bottle->image_url }}" alt="Image de la bouteille">
			</picture>
			<section>
				<header>
					<p class="card_bottle__metainfo">{{ ucfirst($bottle->type) }}</p>
					<h3>{{ $bottle->name }}</h3>
					<p class="card_bottle__metainfo">{{ $bottle->volume }} ml | {{ $bottle->country }}</p>
				</header>
				<div>
					<p>Prix: {{ number_format($bottle->price, 2) }} $</p>
				</div>
			</section>
		</article>
		<!-- Quantité à ajouter -->
    <div class="form-group">
        <label for="quantity">Quantité :</label>
        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $quantity) }}" min="1" required>
    </div>
		@if($source == 'listeAchat')
		<div class="form-group">
			<input type="hidden" name="cellar_id" value="wishlist">
		</div>
		<button type="submit" class="btn">Liste d'achat</button>
		@elseif($source == 'cellier')
		<div class="form-group">
			<label for="cellar_id">Sélectionner un cellier :</label>
			<div class="action-group">
				@foreach ($userCellars as $cellar)
				<button type="button" class="btn btn_cellar" data-cellar-id="{{ $cellar->id }}">
					{{ $cellar->name }}
				</button>
				@endforeach
			</div>
		</div>
		@endif
	</form>
</section>
@endsection