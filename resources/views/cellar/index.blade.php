@extends('layouts.app')
@section('title', 'Celliers')
@section('content')
<section>
	<header>
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
				<li>
					<form method="post" action="{{ route('cellar.delete', $cellar->id) }}">
						@csrf
						@method('DELETE')
						<button type=" submit">Supprimer</button>
					</form>
				</li>
			</ul>
		</div>
	</article>
	@empty
	<p>Aucun cellier</p>
	@endforelse
</section>
@endsection