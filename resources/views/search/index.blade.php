@extends('layouts.app')
@section('js', asset('js/pages/search.js'))
@section('title', 'Recherche')

@section('content')
<section class="search-page">
    <h2 class="search-header">Recherche</h2>

    {{-- recherche form --}}
    @include('search.form')
</section>
@endsection
