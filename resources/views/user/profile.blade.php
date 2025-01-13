@extends('layouts.app')
@section('js', asset('js/pages/profile.js'))
@section('title', 'Mon Profil')

@section('content')
<section>
    <!-- Header Section -->
    <header data-js="header">
        <h2>Profile</h2>
    </header>

    <article class="profile">
        <h3>{{ $user->username }}</h3>

        <div class="menu-deroulant">
            <input type="checkbox" aria-label="bouton pour ouvrir menu des actions">
            <ul class="menu-deroulant__contenu">
                <li>
                    <a href="{{ route('user.edit', $user->id) }}">Modifier</a>
                </li>
                <li data-js-action="supprimerUser" 
                    data-js-name="{{ $user->username }}" 
                    data-js-user-id="{{ $user->id }}">
                    Supprimer
                </li>
                <li data-js-action="afficherModaleDeconnexion" 
                    data-js-name="{{ $user->username }}">
                    Déconnexion
                </li>
            </ul>
        </div>
    </article>

    <!-- Profile Stats -->
    <div class="profile-stats">
        <div class="stat-item">
            <p class="stat-number">{{ $cellarsCount }}</p>
            <p class="stat-label">Celliers</p>
        </div>

      

        <div class="stat-item">
            <p class="stat-number">{{ $toBuyCount }}</p>
            <p class="stat-label">À acheter</p>
        </div>
    </div>

    <!-- Confirmation Modal pour Supprimer -->
    <template id="supprimerUser">
        <div class="modale-action">
            <p class="modale-action__message">
                Êtes-vous sûr de vouloir supprimer votre profil, <span data-js-replace="nom">NOM</span> ?
            </p>
            <div class="modale-action__boutons">
                <button data-js-action="annuler">Annuler</button>
                <form method="POST" action="{{ route('user.destroy', $user->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" data-js-action="supprimer">Supprimer</button>
                </form>
            </div>
        </div>
    </template>

    <!-- Confirmation Modal pour Deconnexion -->
    <template id="deconnexionUser">
        <div class="modale-action">
            <p class="modale-action__message">
                Êtes-vous sûr de vouloir vous déconnecter, <span data-js-replace="nom">NOM</span> ?
            </p>
            <div class="modale-action__boutons">
                <button data-js-action="annuler">Annuler</button>
                <button data-js-action="deconnexion">Déconnexion</button>
            </div>
        </div>
    </template>

   
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</section>
@endsection
