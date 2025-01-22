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
	<div class="inventory-action">
			<div id="kebab-menu-wrapper">
				<template id="kebab-menu">
					<div class="menu-deroulant">
						<input type="checkbox" aria-label="bouton pour ouvrir menu des actions">
						<ul class="menu-deroulant__contenu">
							<li data-js-option="modifier"><a href="">Modifier</a></li>
							<li data-js-option="supprimer" data-js-action="afficherModaleConfirmation" data-js-cellier="" data-js-name="">Supprimer</li>
						</ul>
					</div>
				</template>
			</div>
			<select class="cellier-select" name="cellar-select" id="cellar-select">
				@forelse($cellars as $cellar)	
					<option value="{{ $cellar->id }}">{{ $cellar->name }}</option>
				@empty
				<option disable>Aucun cellier</option>
				@endforelse
			</select>

			<a href="{{ route('cellar.create') }}" class="btn btn_accent">Ajouter un cellier</a>
	</div>
</section>
<section class="products">
    <template id="bottle-template">
        <article class="card_bottle" data-js-key="">
            <button data-js-action="supprimer">
				<svg enable-background="new 0 0 32 32" id="Glyph" version="1.1" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
					<title>Icone de poubelle</title>
					<path d="M6,12v15c0,1.654,1.346,3,3,3h14c1.654,0,3-1.346,3-3V12H6z M12,25c0,0.552-0.448,1-1,1s-1-0.448-1-1v-9  c0-0.552,0.448-1,1-1s1,0.448,1,1V25z M17,25c0,0.552-0.448,1-1,1s-1-0.448-1-1v-9c0-0.552,0.448-1,1-1s1,0.448,1,1V25z M22,25  c0,0.552-0.448,1-1,1s-1-0.448-1-1v-9c0-0.552,0.448-1,1-1s1,0.448,1,1V25z" id="XMLID_237_" />
					<path d="M27,6h-6V5c0-1.654-1.346-3-3-3h-4c-1.654,0-3,1.346-3,3v1H5C3.897,6,3,6.897,3,8v1c0,0.552,0.448,1,1,1h24  c0.552,0,1-0.448,1-1V8C29,6.897,28.103,6,27,6z M13,5c0-0.551,0.449-1,1-1h4c0.551,0,1,0.449,1,1v1h-6V5z" id="XMLID_243_" />
				</svg>
            </button>
            <picture>
                <img src="" alt="">
            </picture>
            <section>
                <header>
                    <p class="card_bottle__metainfo"></p>
                    <h3></h3>
                    <p class="card_bottle__metainfo"></p>
                </header>
                <div>
                    <div class="card_bottle__actions">
                        <button data-js-action="reduire">-</button>
                        <span data-js-quantite="quantite"></span>
                        <button data-js-action="augmenter">+</button>
                    </div>
                </div>
            </section>
        </article>
    </template>
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