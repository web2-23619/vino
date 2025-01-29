@extends('layouts.app')
@section('js', asset('js/pages/favorites.js'))
@section('title', "Favoris")
@section('content')


<section>
    <header data-js="header">
        <h2>Favoris</h2>
    </header>
    <template id="alerte">
		<div class="alerte">
			<p>{{ session('erreur') }}</p>
			<button data-js-action="fermer">x</button>
		</div>
	</template>
    <div data-js-list></div>
</section>
<template id="noFavorite">
    <article class="noContent">
        <h3>Votre liste de favoris est vide.</h3>
        <p>Ajoutez vos bouteilles préférées ici pour y accéder facilement !</p>
        <a href="{{ route('search.index', ['source' => 'favoris']) }}" class="btn">Découvrir des bouteilles</a>
    </article>
</template>
    <!-- Template pour l'utilisation des modales, ne pas supprimer-->
<template id="supprimerFavoris">
	<div class="modale-action">
		<div class="modale-action__conteneur">
			<p class="modale-action__message">Veuillez confirmer la supression de la bouteille <span data-js-replace="nom">NOM</span></p>
			<div class="modale-action__boutons">
				<button data-js-action="supprimer" class="btn btn_accent btn_thick">Supprimer</button>
				<button data-js-action="annuler" class="btn btn_outline_dark btn_thick">Annuler</button>
			</div>
		</div>
	</div>
</template>
<template id="favoriteBottle">
    <article class="card_bottle" data-js-id="" data-js-Name="">
        <picture>
            <button class="favorite-icon" data-js-favorite="false" title="Ajouter aux favoris">
                ❤️
            </button>
            <img data-info="img" src="" alt="">
        </picture>
        <section>
            <header>
                <div class="card_bottle__header">
                    <p class="card_purchase__metainfo" data-info="type">TYPE</p>
                    <button data-js-action="removeFromFavorites">
                        <svg enable-background="new 0 0 32 32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                            <title>Icone de poubelle</title>
                            <path d="M6,12v15c0,1.654,1.346,3,3,3h14c1.654,0,3-1.346,3-3V12H6z M12,25c0,0.552-0.448,1-1,1s-1-0.448-1-1v-9  c0-0.552,0.448-1,1-1s1,0.448,1,1V25z M17,25c0,0.552-0.448,1-1,1s-1-0.448-1-1v-9c0-0.552,0.448-1,1-1s1,0.448,1,1V25z M22,25  c0,0.552-0.448,1-1,1s-1-0.448-1-1v-9c0-0.552,0.448-1,1-1s1,0.448,1,1V25z" id="XMLID_237_" />
                            <path d="M27,6h-6V5c0-1.654-1.346-3-3-3h-4c-1.654,0-3,1.346-3,3v1H5C3.897,6,3,6.897,3,8v1c0,0.552,0.448,1,1,1h24  c0.552,0,1-0.448,1-1V8C29,6.897,28.103,6,27,6z M13,5c0-0.551,0.449-1,1-1h4c0.551,0,1,0.449,1,1v1h-6V5z" id="XMLID_243_" />
                        </svg>
                    </button>
                </div>
                <h3 data-info="name">NOM</h3>
                <p class="card_purchase__metainfo"><span data-info="volume">VOLUME</span> ml | <span data-info="country">PAYS</span></p>
            </header>
            <div>
                <p>$ <span data-info="price">PRIX</span></p>
                <div >
                    <button href="{{ route('search.index', ['source' => 'cellier']) }}" class="btn btn_cellar" data-js-action="moveToCellar">Envoyer vers le cellier</button>
                    <button href="{{ route('search.index', ['source' => 'listeAchat']) }}" class="btn btn_cellar" data-js-action="moveToPurchaseList">Ajouter à la liste d'achat</button>
                </div>
            </div>
        </section>
    </article>
</template>

    
<template id="action-button">
    <!-- Si on est sur la page des favoris -->
    <div>
        <a href="{{ route('search.index', ['source' => 'favoris']) }}" class="btn">Ajouter Bouteille</a>
    </div>
</template>

   

@endsection
