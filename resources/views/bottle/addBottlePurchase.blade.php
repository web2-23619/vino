@extends('layouts.app')

@section('title', 'Ajouter une bouteille')

@section('content')
<h2>Ajouter à la liste d'achat</h2>

<form action="{{ route('achat.add.submit') }}" method="POST">
    @csrf
    <input type="hidden" name="bottle_id" value="{{ $bottle->id }}">

        <div class="result-actions">
        <!-- <button type="button" class="btn-add" data-bottle-id="{{ $bottle->id }}">+</button> -->
        <select name="cellar_id" class="select-cellar">
            @foreach($userCellars as $cellar)
                <option value="{{ $cellar->id }}">{{ $cellar->name }}</option>
            @endforeach
            <option value="wishlist" selected>Liste d'achat</option>
        </select>
    </div>

    <!-- Quantity to add -->
    <div>
        <label for="quantity">Quantité :</label>
        <input type="number" name="quantity" id="quantity" min="1" required>
    </div>

    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>
@endsection