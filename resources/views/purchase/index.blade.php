@extends('layouts.app')
@section('js', asset('js/pages/purchase.js'))
@section('title', "Liste d'achat")
@section('content')
<section>
    <header data-js="header">
        <h2>Liste d'achat</h2>
    </header>

    <!-- Cellar Dropdown -->
    <div class="display-options">
        <div class="order">
            <label for="cellarDropdown">Sélectionnez un cellier</label>
            <select id="cellarDropdown" name="cellar">
                <option value="" disabled selected>Chargement des celliers...</option>
            </select>
        </div>

        <div class="order">
            <label for="order">Tri</label>
            <select name="order" id="order">
                <option value="name_asc">Nom ascendant: A-Z</option>
                <option value="name_desc">Nom descendant: Z-A</option>
                <option value="price_asc">Prix ascendant: 0 - 100</option>
                <option value="price_desc">Prix descendant: 100 - 0</option>
            </select>
        </div>
    </div>

    <div data-js-list>
        <!-- Purchases will be dynamically added here -->
    </div>
</section>

<!-- Alert Template -->
<template id="alerte">
    <div class="alerte">
        <p>{{ session('erreur') }}</p>
        <button data-js-action="fermer">x</button>
    </div>
</template>

<!-- Template for no purchases -->
<template id="noPurchase">
    <article class="noContent">
        <h3>
            Il semblerait que vous n'ayez rien à acheter.
        </h3>
        <p>Créez votre liste d'achat afin de ne rien oublier lors de votre prochaine visite à la SAQ!</p>
        <a href="{{ route('search.index', ['source' => 'listeAchat']) }}" class="btn">Découvrir des bouteilles</a>
    </article>
</template>

<!-- Template for a bottle -->
<template id="bottle">
    <article class="card_bottle" data-js-id="" data-js-Name="">
        <picture>
            <img data-info="img" src="" alt="">
        </picture>
        <section>
            <header>
                <div class="card_bottle__header">
                    <p class="card_purchase__metainfo" data-info="type">TYPE</p>
                    <button data-js-action="afficherModaleConfirmation">
                        <svg enable-background="new 0 0 32 32" id="Glyph" version="1.1" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
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
                <div class="card_bottle__actions">
                    <button data-js-action="reduire"><span>-</span></button>
                    <span data-js-quantite="quantite" data-info="quantity">QUANTITE</span>
                    <button data-js-action="augmenter"><span>+</span></button>
                </div>
            </div>
            <button class="btn btn_accent btn_cellar" data-js-action="addToCellar">Ajouter au cellier</button>
        </section>
    </article>
</template>

<!-- Template for the action button -->
<template id="action-button">
    <div>
        <a href="{{ route('search.index', ['source' => 'listeAchat']) }}" class="btn">Ajouter Bouteille</a>
    </div>
</template>
@endsection
