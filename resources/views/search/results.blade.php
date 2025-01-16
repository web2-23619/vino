@extends('layouts.app')
@section('js', asset('js/pages/search.js'))
@section('title', 'Résultats de Recherche')

@section('content')
<section>
  
        <div class="result" >
            <header>
                <h2>Résultats pour "{{ $query }}"</h2>
            </header>
       

    {{-- nombres des resultats --}}
    <p>
        {{ $resultCount }} {{ Str::plural('résultat', $resultCount) }} trouvé{{ $resultCount > 1 ? 's' : '' }}.
    </p>
    
    <form class="form-group" action="{{ route('search.results') }}" method="POST">
	@csrf
	<div class="search-container">
		<input
			type="text"
			id="search"
			name="query"
			placeholder="Entrez votre recherche"
			minlength="2"
			value="{{ request('query') }}"
			required
			autocomplete="off" />
		<button type="submit">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
				<path d="M10 2a8 8 0 015.61 13.61l4.2 4.2a1 1 0 01-1.42 1.42l-4.2-4.2A8 8 0 1110 2zm0 2a6 6 0 100 12 6 6 0 000-12z" />
			</svg>
		</button>
	</div>
</form>

    </div>

    @if($results->isEmpty())
        <p>Aucune bouteille trouvée</p>
    @else

        @foreach($results as $bottle)
        <article class="card_bottle" data-js-key="{{$bottle['id']}}">
            <picture>
                <img src="{{ $bottle->image_url }}" alt="">
            </picture>
            <section>
                <header>
                    <p class="card_bottle__metainfo">{{ ucfirst($bottle->type) }}</p>
                    <h3>{{ $bottle->name }}</h3>
                    <p class="card_bottle__metainfo">{{ $bottle->volume }} ml | {{ $bottle->country }}</p>
                </header>
                <div>
                    <p>Prix: {{ number_format($bottle->price, 2) }} $</p>
                    <div class="card_bottle__actions">
  <a href="{{ route('bottle.add', ['bottle_id' => $bottle->id, 'source' => session('add_bottle_source', 'default'), 'cellar_id' => request('cellar_id')]) }}" class="btn-add">Ajouter</a>
                    </div>
                </div>
            </section>
        </article>
        @endforeach
    @endif
</section>
@endsection
