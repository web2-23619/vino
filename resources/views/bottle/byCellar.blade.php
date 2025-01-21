@extends('layouts.app')
@section('js', asset('js/pages/bottleByCellar.js'))
@section('title', "Mes bouteilles")
@section('content')
<section>
	<header data-js="header">
		<h2>{{$cellar->name}}</h2>
	</header>
	<template id="alerte">
		<div class="alerte">
			<p>{{ session('erreur') }}</p>
			<button data-js-action="fermer">x</button>
		</div>
	</template>
	@forelse($bottles as $bottle)
	<article class="card_bottle" data-js-key="{{$cellar->id}}|{{$bottle['id']}}" data-js-name="{{$bottle['name']}}">
		<picture>
			<img src="{{$bottle['image_url']}}" alt="">
		</picture>
		<section>
			<header>
				<div class="card_bottle__header">
					<p class="card_bottle__metainfo">{{$bottle['type']}}</p>
					<button data-js-action="afficherModaleConfirmation">
						<svg enable-background="new 0 0 32 32" id="Glyph" version="1.1" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<title>Icone de poubelle</title>
							<path d="M6,12v15c0,1.654,1.346,3,3,3h14c1.654,0,3-1.346,3-3V12H6z M12,25c0,0.552-0.448,1-1,1s-1-0.448-1-1v-9  c0-0.552,0.448-1,1-1s1,0.448,1,1V25z M17,25c0,0.552-0.448,1-1,1s-1-0.448-1-1v-9c0-0.552,0.448-1,1-1s1,0.448,1,1V25z M22,25  c0,0.552-0.448,1-1,1s-1-0.448-1-1v-9c0-0.552,0.448-1,1-1s1,0.448,1,1V25z" id="XMLID_237_" />
							<path d="M27,6h-6V5c0-1.654-1.346-3-3-3h-4c-1.654,0-3,1.346-3,3v1H5C3.897,6,3,6.897,3,8v1c0,0.552,0.448,1,1,1h24  c0.552,0,1-0.448,1-1V8C29,6.897,28.103,6,27,6z M13,5c0-0.551,0.449-1,1-1h4c0.551,0,1,0.449,1,1v1h-6V5z" id="XMLID_243_" />
						</svg>
					</button>
				</div>
				<h3>{{$bottle['name']}}</h3>
				<p class="card_bottle__metainfo">{{$bottle['volume']}} ml | {{$bottle['country']}}</p>
			</header>
			<div>
				<div class="card_bottle__actions">
					<button data-js-action="reduire">-</button>
					<span data-js-quantite="quantite">{{$bottle['quantity']}}</span>
					<button data-js-action="augmenter">Ajouter</button>
				</div>
			</div>
		</section>
	</article>
	@empty
	<article class="noContent">
		<h3>
			Il semblerait que vous n'ayez pas de bouteilles en stock.
		</h3>
		<p>Démarrez votre collection en ajoutant vos bouteilles recément achetées ou reçues</p>
		<a href=" {{ route('search.index', ['source' => 'cellier', 'cellar_id' => $cellar->id]) }}" class="btn">Agrandir ma collection</a>
	</article>
	@endforelse
</section>
<!-- Template pour l'utilisation des modales, ne pas supprimer-->
<template id="retirerBouteille">
	<div class="modale-action">
		<div class="modale-action__conteneur">
			<p class="modale-action__message">Veuillez confirmer le retrait de la bouteille <span data-js-replace="nom">NOM</span></p>
			<div class="modale-action__boutons">
				<button data-js-action="supprimer" class="btn btn_accent btn_thick">Supprimer</button>
				<button data-js-action="annuler" class="btn btn_outline_dark btn_thick">Annuler</button>
			</div>
		</div>
	</div>
</template>
@endsection