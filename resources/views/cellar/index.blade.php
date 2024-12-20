@extends('layouts.app')
@section('js', asset('js/pages/celliers.js'))
@section('title', 'Celliers')
@section('content')
<section>
	<header data-js="header">
		<h2>Celliers</h2>
	</header>
	<template id="alerte">
		<div class="alerte">
			<p>{{ session('erreur') }}</p>
			<button data-js-action="fermer">x</button>
		</div>
	</template>
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
	@forelse($cellars as $cellar)
	<article class="cellier">
		<a href="{{route('cellar.showBottles', $cellar->id)}}">{{$cellar->name}}</a>
		<div class="menu-deroulant">
			<input type="checkbox" aria-label="bouton pour ouvrir menu des actions">
			<ul class="menu-deroulant__contenu">
				<a href="{{ route('cellar.edit', $cellar->id) }}">Modifier</a>
				<li data-js-action="afficherModaleConfirmation" data-js-cellier="{{ $cellar->id }}" data-js-Name="{{$cellar->name}}">Supprimer</li>
			</ul>
		</div>
	</article>
	@empty
	<p>Aucun cellier</p>
	@endforelse
	<!-- Bouton Ajouter Cellier sticky -->
</section>
<!-- Template pour l'utilisation des modales, ne pas supprimer-->
<template id="supprimerCellier">
	<div class="modale-action">
		<p class="modale-action__message">Veuillez confirmer la supression du cellier <span data-js-replace="nom">NOM</span></p>
		<div class="modale-action__boutons">
			<button data-js-action="annuler">Annuler</button>
			<button data-js-action="supprimer">Supprimer</button>
		</div>
	</div>
</template>
@endsection