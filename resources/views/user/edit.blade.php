@extends('layouts.app')

@section('title', 'Modifier Profil')

@section('content')
<section>
    <header>
        <h2>Modifier Profil</h2>
    </header>

    <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form">
            <label for="username">Nom</label>
            <input type="text" id="username" name="username" value="{{ $user->username }}" required>
        </div>

        <div class="form">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" required>
        </div>

        <button type="submit">Enregistrer</button>
    </form>
</section>
@endsection
