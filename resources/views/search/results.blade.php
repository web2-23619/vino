@extends('layouts.app')

@section('title', 'Résultats de Recherche')

@section('content')
<section class="search-results-page">
    <h2 class="search-header">Résultats pour "{{ $query }}"</h2>

    {{-- Check if there are any results --}}
    @if($results->isEmpty())
        <p class="no-results">Aucune bouteille trouvée.</p>
        <a href="{{ route('bottles.create') }}" class="btn-create">+ Créer une bouteille</a>
    @else
        <div class="results-list">
            {{-- Loop through each bottle --}}
            @foreach($results as $bottle)
            <div class="result-item">
                <img src="{{ $bottle->image_url }}" alt="{{ $bottle->name }}" class="result-image" />
                
                <div class="result-details">
                    <p class="result-name">{{ $bottle->name }}</p>
                    <p class="result-info">
                        {{ $bottle->volume }} ml | {{ $bottle->country }}<br />
                        {{ ucfirst($bottle->type) }} | {{ number_format($bottle->price, 2) }} $
                    </p>

                    {{-- Display which cellars this bottle is in --}}
                    @if($bottle->cellars->isNotEmpty())
                        <p class="cellar-info">Présent dans :</p>
                        <ul>
                            @foreach($bottle->cellars as $cellar)
                                <li>
                                    {{ $cellar->name }} (Quantité : {{ $cellar->pivot->quantity }})
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="no-cellar-info">Aucun cellier associé.</p>
                    @endif
                </div>

                <div class="result-actions">
                    {{-- Add button with a dropdown for cellars --}}
                    <button class="btn-add">Ajouter
                    <select>
    @foreach($userCellars as $cellar)
        <option value="{{ $cellar->id }}">{{ $cellar->name }}</option>
    @endforeach
    <option value="wishlist">Liste d'achat</option>
</select>

                    </button>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination links --}}
        {{ $results->links() }}
    @endif
</section>
@endsection
