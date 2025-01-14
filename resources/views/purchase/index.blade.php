@extends('layouts.app')
@section('js', asset('js/pages/purchase.js'))
@section('title', "Liste d'achat")
@section('content')
<section>
	<header data-js="header">
		<h2>Liste d'achat</h2>
	</header>
	<template id="alerte">
		<div class="alerte">
			<p>{{ session('erreur') }}</p>
			<button data-js-action="fermer">x</button>
		</div>
	</template>
	@forelse($purchases as $purchase)
	<article class="card_purchase" data-js-id="{{$purchase->id}}">
		<button class="btn" data-js-action="supprimer">X</button>
		<picture>
			<img src="{{$purchase->bottle->image_url}}" alt="">
		</picture>
		<section>
			<header>
				<p class="card_purchase__metainfo">{{$purchase->bottle->type}}</p>
				<h3>{{$purchase->bottle->name}}</h3>
				<p class="card_purchase__metainfo">{{$purchase->bottle->volume}} ml | {{$purchase->bottle->country}}</p>
			</header>
			<div>
				<p>$ {{$purchase->bottle->price}} / un</p>
				<div class="card_purchase__actions">
					<button class="invisible">-</button>
					<span>{{$purchase->quantity}}</span>
					<button class="invisible">+</button>
				</div>
			</div>
		</section>
	</article>
	@empty
	<p>Aucune bouteille à acheter</p>
	@endforelse
</section>
@endsection