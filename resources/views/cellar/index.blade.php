@extends('layouts.app')
@section('title', 'Celliers')
@section('content')
<section>
	<header data-js="header">
		<h2>Celliers</h2>
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
	@forelse($cellars as $cellar)
	<article class="cellier">
		<p>{{$cellar->name}}</p>
		<div class="menu-deroulant">
			<input type="checkbox" aria-label="bouton pour ouvrir menu des actions">
			<ul class="menu-deroulant__contenu">
				<!-- <li><a class="button" href="">Modifier</a></li> -->
				<li data-js-action="supprimerCellier" data-js-cellier="{{ $cellar->id }}" data-js-cellierName="{{$cellar->name}}">Supprimer</li>
			</ul>
		</div>
	</article>
	@empty
	<p>Aucun cellier</p>
	@endforelse
</section>
<!-- Template pour l'utilisation des modales, ne pas supprimer-->
<template id="supprimerCellier">
	<div class="modale-action">
		<p class="modale-action__message">Veuillez confirmer la supression du cellier <span data-js-replace="nom">NOM</span></p>
		<div class="modale-action__boutons">
			<button data-js-action="annuler">Annuler</button>
			<form method="post">
				@csrf
				@method('DELETE')
				<button type=" submit">Supprimer</button>
			</form>
		</div>
	</div>
</template>
@endsection