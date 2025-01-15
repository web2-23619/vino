@extends('layouts.app')
@section('js', asset('js/pages/search.js'))
@section('title', 'Résultats de Recherche')

@section('content')
<section class="search-results-page">
    <h2 class="search-header">Résultats pour "{{ $query }}"</h2>

    {{-- nombres des resultats --}}
    <p class="result-count">
        {{ $resultCount }} {{ Str::plural('résultat', $resultCount) }} trouvé{{ $resultCount > 1 ? 's' : '' }}.
    </p>
    {{-- bar de recherche --}}
    @include('search.form')
    
    @if($results->isEmpty())
        <p class="no-results">Aucune bouteille trouvée.</p>
    @else
        <div class="results-list">
            @foreach($results as $bottle)
                <div class="result-item">
                    {{-- Bottle Image --}}
                    <img src="{{ $bottle->image_url }}" alt="{{ $bottle->name }}" class="result-image" />

                    {{-- Bottle Details --}}
                    <div class="result-details">
                        <p class="result-name">{{ $bottle->name }}</p>
                        <p class="result-info">
                            {{ $bottle->volume }} ml | {{ $bottle->country }}<br />
                            {{ ucfirst($bottle->type) }} | {{ number_format($bottle->price, 2) }} $
                        </p>
                        <a href="{{ route('bottle.add', ['bottle_id' => $bottle->id]) }}" class="btn-add">Ajouter</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
