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
    
    {{-- barre de recherche --}}
    @include('search.form')

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
                        <a href="{{ route('bottle.add', ['bottle_id' => $bottle->id]) }}" class="btn-add">Ajouter</a>
                        <a href="{{ route('achat.add', ['bottle_id' => $bottle->id]) }}" class="btn-add">
                            <svg enable-background="new 0 0 32 32" id="Glyph" version="1.1" viewBox="0 0 20 20" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="white">
                                <title>Ajouter au panier</title>
                                <path d="M4 4a1 1 0 0 1 1-1h1.5a1 1 0 0 1 .979.796L7.939 6H19a1 1 0 0 1 .979 1.204l-1.25 6a1 1 0 0 1-.979.796H9.605l.208 1H17a3 3 0 1 1-2.83 2h-2.34a3 3 0 1 1-4.009-1.76L5.686 5H5a1 1 0 0 1-1-1Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </section>
        </article>
        @endforeach
    @endif
</section>
@endsection
