@extends('welcome')

@section('content')
    <h1>Ajouter un Cellier</h1>
    
    <form action="{{ route('cellars.store') }}" method="POST">
        @csrf
        <label for="name">Nom:</label> 
        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        @error('name') 
            <div>{{ $message }}</div>
        @enderror
        
        <label for="quantity">Quantité:</label>
        <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" required>
        @error('quantity') 
            <div>{{ $message }}</div>
        @enderror
        
        <button type="submit">Ajouter</button>
    </form>
@endsection
