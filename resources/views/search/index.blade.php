@extends('layouts.app')
@section('js', asset('js/pages/search.js'))
@section('title', 'Recherche')

@section('content')
<section>
	<h2 class="search-header">Recherche</h2>

	{{-- recherche form --}}
	@include('search.form')

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

</section>
@endsection