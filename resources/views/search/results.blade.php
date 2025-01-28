@extends('layouts.app')
@section('js', asset('js/pages/search.js'))
@section('title', 'Résultats de Recherche')

@section('content')
<section>

	<div class="result">
		<header>
			<h2>Résultats pour "<span data-info="searchQuery"></span>"</h2>
		</header>


		{{-- barre de recherche --}}
		@include('search.form')

		{{-- nombres des resultats --}}
		<p data-info="resultCount">
			X résultat trouvé
		</p>

	</div>
	<div data-js-list>

	</div>
</section>
@endsection
<template id="searchResultBottle">
	<article class="card_bottle" data-js-key="">
		<picture>
			<img loading="lazy" src="" alt="">
		</picture>
		<section>
			<header>
				<p class="card_bottle__metainfo"></p>
				<h3></h3>
				<p class="card_bottle__metainfo"> ml | </p>
			</header>
			<div>
				<p>Prix: $</p>
				<div class="card_bottle__actions">
					<a href="" class="btn no-bg">Ajouter</a>
				</div>
			</div>
		</section>
	</article>
</template>
