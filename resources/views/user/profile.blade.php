@extends('layouts.app')
@section('js', asset('js/pages/profile.js'))
@section('title', 'Mon Profil')

@section('content')
<section class="profile-page">
    <!-- Header Section -->
    <header class="profile-header">
        <h2 class="username">{{ $user->username }}</h2>

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
                <li>
    <a data-js-action="logout" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>


            </ul>
        </div>
    </header>

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
</section>
@endsection
