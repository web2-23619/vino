@extends('layouts.app')
@section('js', asset('js/pages/profile.js'))
@section('title', 'Mon Profil')

@section('content')
<section>


	<article class="profile">
		<h2 class="profile-header">{{ $user->username }}</h2>
		<template id="alerte">
			<div class="alerte">
				<p>{{ session('erreur') }}</p>
				<button data-js-action="fermer">x</button>
			</div>
		</template>
	</article>
		<!-- Boutons d'actions -->
		<div class="profile-actions">
			<a href="{{ route('user.edit', $user->id) }}" class="btn btn_semi_accent btn_thick">Modifier profil</a>
			<button data-js-action="supprimerUser" 
					data-js-name="{{ $user->username }}" 
					data-js-user-id="{{ $user->id }}" 
					class="btn btn_semi_accent btn_thick">
				Supprimer profil
			</button>
			<button data-js-action="afficherModaleDeconnexion" 
					data-js-name="{{ $user->username }}" 
					class="btn btn_accent btn_thick">
				Déconnexion
			</button>
		</div>

	<!-- Stats de profil -->
	<div class="profile-stats">
		<!-- nombre de celliers -->
		<div class="stat-item">
			<a href="{{ route('cellar.index') }}">
				<p class="stat-number">{{ $cellarsCount }}</p>
			</a>
			<p class="stat-label">Inventaire</p>
		</div>

		<!-- nombre de bouteilles -->
		<div class="stat-item">
			<a href="{{ route('user.showBottles', $user->id) }}">
				<p class="stat-number">{{ $bottlesCount }}</p>
			</a>
			<p class="stat-label">Bouteilles</p>
		</div>

		<!-- a acheter -->
		<div class="stat-item">
			<a href="{{ route('purchase.index') }}">
				<p class="stat-number">{{ $toBuyCount }}</p>
			</a>
			<p class="stat-label">À acheter</p>
		</div>
	</div>


	<!-- Confirmation Modal pour Supprimer -->
	<template id="supprimerUser">
		<div class="modale-action">
			<div class="modale-action__conteneur">
				<p class="modale-action__message">
					Êtes-vous sûr de vouloir supprimer votre profil, <span data-js-replace="nom">NOM</span> ?
				</p>
				<div class="modale-action__boutons">
					<button data-js-action="supprimer" class="btn btn_accent btn_thick">Supprimer</button>
					<button data-js-action="annuler" class="btn btn_outline_dark btn_thick">Annuler</button>
				</div>
			</div>
		</div>
	</template>

	<!-- Confirmation Modal pour Deconnexion -->
	<template id="deconnexionUser">
		<div class="modale-action">
			<div class="modale-action__conteneur">
				<p class="modale-action__message">
					Êtes-vous sûr de vouloir vous déconnecter, <span data-js-replace="nom">NOM</span> ?
				</p>
				<div class="modale-action__boutons">
					<button data-js-action="deconnexion" class="btn btn_accent btn_thick">Déconnexion</button>
					<button data-js-action="annuler" class="btn btn_outline_dark btn_thick">Annuler</button>
				</div>
			</div>
		</div>
	</template>


	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
		@csrf
	</form>
</section>
@endsection