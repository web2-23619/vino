@extends('layouts.app')
@section('js', asset('js/pages/purchase.js'))
@section('title', "Liste d'achat")
@section('content')
<section>
	<header data-js="header">
		<h2>Liste d'achat</h2>
	</header>
	@if(session('succes'))
	<div class="alerte alerte_succes">
		<p>{{ session('succes') }}</p>
		<button data-js-action="fermer">x</button>
	</div>
	@endif
	@if(session('erreur'))
	<div class="alerte alerte_erreur">
		<p>{{ session('erreur') }}</p>
		<button data-js-action="fermer">x</button>
	</div>
	@endif
	@forelse($purchases as $purchase)
	<article class="cellier">
		<p>{{$purchase->id}}</p>
	</article>
	@empty
	<p>Aucune bouteilles à acheter</p>
	@endforelse
</section>
@endsection