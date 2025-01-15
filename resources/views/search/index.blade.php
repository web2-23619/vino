@extends('layouts.app')

@section('title', 'Recherche')

@section('content')
<section class="search-page">
    <h2 class="search-header">Recherche</h2>
    <form class="search-form" action="{{ route('search.results') }}" method="POST">
        @csrf
        <div class="form-group">
            <input
                type="text"
                name="query"
                placeholder="Entrez votre recherche"
                required
            />
            <button type="submit" class="btn-search">
              
            </button>
        </div>
    </form>
</section>
@endsection