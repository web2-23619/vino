@extends('layouts.app')
@section('js', asset('js/pages/listeAchatCellier.js'))
@section('title', 'Ajouter une bouteille')

@section('content')
<section class="page">


    <header>
        <h2 class="register-header">Ajouter la bouteille</h2>
    </header>

    <!-- Formulaire avec action conditionnelle -->
    <form class="form" action="{{ route('bottle.add.submit') }}" method="POST" id="addBottleForm">
        @csrf
        <input type="hidden" name="bottle_id" value="{{ $bottle->id }}">
        <input type="hidden" name="source" value="{{ $source }}">

        <!-- Sélectionner un cellier parmi les celliers de l'utilisateur -->
        <div class="form-group">
            <label for="cellar_id">Sélectionner un cellier :</label>
            <select name="cellar_id" id="cellar_id" required>
                @if($source == 'default')
                    <!-- Si la source est 'default', afficher tous les celliers ET la liste d'achat -->
                    @foreach ($userCellars as $cellar)
                        <option value="{{ $cellar->id }}"
                            @if(session('cellar_id') == $cellar->id) selected @endif>
                            {{ $cellar->name }}
                        </option>
                    @endforeach
                    <!-- Ajouter l'option pour la liste d'achat -->
                    <option value="wishlist">Liste d'achat</option>
                @elseif($source == 'listeAchat')
                    <!-- Si la source est 'listeAchat', afficher uniquement l'option pour la liste d'achat -->
                    <option value="wishlist" selected>Liste d'achat</option>
                @elseif($source == 'mesBouteilles')
                    <!-- Si la source est 'mesBouteilles', afficher tous les celliers -->
                    @foreach ($userCellars as $cellar)
                        <option value="{{ $cellar->id }}">{{ $cellar->name }}</option>
                    @endforeach
                @elseif($source == 'cellier' && session('cellar_id'))
                    <!-- Si la source est 'cellier', afficher le cellier sélectionné en premier -->
                    <option value="{{ session('cellar_id') }}" selected>{{ $selectedCellarName }}</option>
                    @foreach ($userCellars as $cellar)
                        @if($cellar->id != session('cellar_id'))  <!-- Exclure le cellier déjà sélectionné -->
                            <option value="{{ $cellar->id }}">{{ $cellar->name }}</option>
                        @endif
                    @endforeach
                @else
                    <!-- Cas par défaut, afficher tous les celliers -->
                    @foreach ($userCellars as $cellar)
                        <option value="{{ $cellar->id }}">{{ $cellar->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Quantité à ajouter -->
        <div class="form-group">
            <label for="quantity">Quantité :</label>
            <input type="number" name="quantity" id="quantity" min="1" required>
        </div>

        <button type="submit" class="btn">Ajouter au cellier</button>
    </form>
</section>
@endsection
