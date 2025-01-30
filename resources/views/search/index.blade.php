@extends('layouts.app')
@section('js', asset('js/pages/search.js'))
@section('title', 'Recherche')

@section('content')
<section class="result">
	<h2 class="search-header">Recherche</h2>

	{{-- recherche form --}}
	@include('search.form')
	<div class="display-options">
		@include('layouts.filtres',['initialCountries' => $initialCountries, 'remainingCountries' => $remainingCountries, 'remainingCount' => $remainingCount, 'types' => $types])
		@include('layouts.sort')
	</div>
	<!-- Template pour la position du scanner ne pas supprimer, merci! -->
	<template id="interactive-container">
		<div id="interactive" style="width: 100%; height: 300px;"></div>
	</template>

	<div data-js-list>
		<header>
			<h2 class="search-header">Bouteilles à découvrir</h2>
		</header>
		@if($randomBottles->isEmpty())
		<p>Aucune bouteille à afficher.</p>
		@else
		@foreach($randomBottles as $bottle)
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
					<div class="card_bottle__actions">
						@if($source === 'favoris')
						<a href="{{ route('favoris.add', ['bottleId' => $bottle->id]) }}" class="btn no-bg">Ajouter</a>
						@else
						<a href="{{ route('bottle.add', ['bottle_id' => $bottle->id, 'source' => $source]) }}" class="btn no-bg">Ajouter</a>
						@endif
					</div>
				</div>
			</section>
		</article>
		@endforeach
		@endif
	</div>
</section>
@endsection
<template id="searchResultBottle">
	<article class="card_bottle" data-js-key="">
		<picture>
			<img data-info="img" loading="lazy" src="" alt="">
		</picture>
		<section>
			<header>
				<p class="card_bottle__metainfo" data-info="type"></p>
				<h3 data-info="name"></h3>
				<p class="card_bottle__metainfo"><span data-info="volume"></span> ml | <span data-info="country"></span></p>
			</header>
			<div>
				<p>Prix: <span data-info="price"></span>$</p>
				<div class="card_bottle__actions">
					@if($source === 'favoris')
					<a href="#" data-route-template="{{ route('favoris.add', ['bottleId' => ':bottle_id']) }}" class="btn no-bg">Ajouter</a>
					@else
					<a href="#" data-route-template="{{ route('bottle.add', ['bottle_id' => ':bottle_id', 'source' => ':source']) }}" class="btn no-bg">Ajouter</a>
					@endif

				</div>

			</div>
		</section>
	</article>
</template>