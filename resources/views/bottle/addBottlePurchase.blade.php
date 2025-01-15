@extends('layouts.app')

@section('title', 'Ajouter à la liste d\'achat')

@section('content')
    <header>
        <h2 class="register-header">Ajouter à la liste d'achat</h2>
    </header>
    <form class="add-form form" action="{{ route('achat.add.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="bottle_id" value="{{ $bottle->id }}">

        <!-- Sélectionner une liste d'achat parmi les listes de l'utilisateur -->
        <div class="form-group">
            <label for="cellar_id">Sélectionner une liste :</label>
            <select name="cellar_id" id="cellar_id" class="add-select" required>
                @foreach ($userCellars as $cellar)
                    <option value="{{ $cellar->id }}">{{ $cellar->name }}</option>
                @endforeach
                <option value="wishlist" selected>Liste d'achat</option>
            </select>
        </div>

        <!-- Quantité à ajouter -->
        <div class="form-group">
            <label for="quantity">Quantité :</label>
            <input type="number" name="quantity" id="quantity" class="add-input" min="1" required>
        </div>

        <button type="submit" class="btn add-btn">Ajouter</button>
    </form>
@endsection
