@extends('layouts.app')
@section('js', asset('js/pages/search.js'))
@section('title', 'Résultats de Recherche')

@section('content')
<section>

	<div class="result">
		<header>
			<h2>Résultats pour "{{ $query }}"</h2>
		</header>


		{{-- barre de recherche --}}
		@include('search.form')

		{{-- nombres des resultats --}}
		<p>
			{{ $resultCount }} {{ Str::plural('résultat', $resultCount) }} trouvé{{ $resultCount > 1 ? 's' : '' }}.
		</p>

	</div>

	@if($results->isEmpty())
	<p>Aucune bouteille trouvée</p>
	@else

	@foreach($results as $bottle)
	<article class="card_bottle" data-js-key="{{$bottle['id']}}">
		<picture>
			<img src="{{ $bottle->image_url }}" alt="">
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
					<a href="{{ route('bottle.add', ['bottle_id' => $bottle->id, 'source' => session('add_bottle_source', 'default'), 'cellar_id' => request('cellar_id')]) }}" class="btn no-bg">Ajouter</a>
				</div>
			</div>
		</section>
	</article>
	@endforeach
	@endif
</section>
@endsection