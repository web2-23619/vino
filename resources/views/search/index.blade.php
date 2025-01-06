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
                <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M21.71 20.29l-3.39-3.39A9.09 9.09 0 0019 10.5a9.5 9.5 0 10-9.5 9.5 9.09 9.09 0 006.4-2.67l3.39 3.39a1 1 0 001.42 0 1 1 0 000-1.42zM10.5 18a7.5 7.5 0 117.5-7.5 7.51 7.51 0 01-7.5 7.5z" />
                </svg> -->
            </button>
        </div>
    </form>
</section>
@endsection