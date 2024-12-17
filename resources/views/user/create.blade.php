@extends('layouts.app')
@section('title', 'Enregistrement')
@section('content')
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<section class="register-page">
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Vino Logo">
    </div>
    <h2 class="register-header">Enregistrement</h2>
    <form class="register-form" action="{{ route('user.store') }}" method="POST">
        @csrf 
        <div class="form-group">
            <label for="username">Nom</label>
            <input type="text" id="username" name="username" placeholder="Votre nom" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Votre email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
        </div>
        <div>
            <button type="submit" class="btn">Enregistrer</button>
        </div>
    </form>
</section>
@endsection
