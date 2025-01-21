@extends('layouts.app')
@section('js', asset('js/pages/celliers.js'))
@section('title', 'Celliers')
@section('content')
<section>
	<header data-js="header">
		<h2>Inventaire</h2>
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
	<div>
			<div class="menu-deroulant">
				<input type="checkbox" aria-label="bouton pour ouvrir menu des actions">
				<ul class="menu-deroulant__contenu">
					@forelse($cellars as $cellar)
					<li><a href="{{ route('cellar.edit', $cellar->id) }}">Modifier</a></li>
					<li data-js-action="afficherModaleConfirmation" data-js-cellier="{{ $cellar->id }}" data-js-Name="{{$cellar->name}}">Supprimer</li>
					@empty
					<p>Aucun cellier</p>
					@endforelse
				</ul>
			</div>
			<select class="cellier-select" name="cellar-select" id="cellar-select">
				@forelse($cellars as $cellar)	
					<option value="{{ $cellar->id }}">{{ $cellar->name }}</option>
				@empty
				<option disable>Aucun cellier</option>
				@endforelse
			</select>
	</div>
</section>
<!-- Template pour l'utilisation des modales, ne pas supprimer-->
<template id="supprimerCellier">
	<div class="modale-action">
		<div class="modale-action__conteneur">
			<p class="modale-action__message">Veuillez confirmer la supression du cellier <span data-js-replace="nom">NOM</span></p>
			<div class="modale-action__boutons">
				<button data-js-action="supprimer" class="btn btn_accent btn_thick">Supprimer</button>
				<button data-js-action="annuler" class="btn btn_outline_dark btn_thick">Annuler</button>
			</div>
		</div>
	</div>
</template>
@endsection