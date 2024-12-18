@extends('layouts.app')
@section('title', 'Mon Profil')

@section('content')
<section class="profile-page">
    <!-- Header Section -->
    <header class="profile-header">
        <div class="profile-header__container">
            <h2 class="username">{{ $user->username }}</h2>
            
            <!-- Menu Déroulant -->
            <div class="menu-deroulant">
                <input type="checkbox" aria-label="bouton pour ouvrir menu des actions">
                <ul class="menu-deroulant__contenu">
                    <li>
                        <a href="{{ route('user.edit', $user->id) }}">Modifier</a>
                    </li>
                    <li
                            data-js-action="supprimerUser" 
                            data-js-name="{{ $user->username }}" 
                            data-js-user-id="{{ $user->id }}">
                            Supprimer
                    </li>
                    <li>
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Déconnexion
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Alerts -->
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

    <!-- Stats Section -->
     <div class="profile-stats">
        <div class="stat-item">
            <p class="stat-number">2</p>
            <p class="stat-label">Celliers</p>
        </div>
        <div class="stat-item">
            <p class="stat-number">8</p>
            <p class="stat-label">Bouteilles</p>
        </div>
        <div class="stat-item">
            <p class="stat-number">3</p>
            <p class="stat-label">À acheter</p>
        </div>
    </div> 

    <!-- Bottles List -->
    <!-- <div class="bottles-list">
        @for($i = 0; $i < 2; $i++)
        <div class="bottle-item">
            <div class="bottle-placeholder"></div>
            <div>
                <p class="bottle-name">Rouge | Shiraz</p>
                <p class="bottle-desc">Importation privée 2024 <br> 750 ml | Italie</p>
            </div>
            <div class="bottle-quantity">
                <button>-</button>
                <span>2</span>
                <button>+</button>
            </div>
        </div>
        @endfor
    </div>
</section> -->

<!-- Confirmation Modal for Supprimer -->
<template id="supprimerUser">
    <div class="modale-action">
        <p class="modale-action__message">
            Êtes-vous sûr de vouloir supprimer votre profil, <span data-js-replace="nom">NOM</span> ?
        </p>
        <div class="modale-action__boutons">
            <button data-js-action="annuler">Annuler</button>
            <form method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Supprimer</button>
            </form>
        </div>
    </div>
</template>

@endsection
