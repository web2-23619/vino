@extends('layouts.app')
@section('title', 'Celliers')
@section('content')
<section>
	<header>
		<h2>Celliers</h2>
	</header>
	@foreach($cellars as $cellar)
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
	@endforeach
</section>
@endsection