@extends('layouts.app')
@section('title', 'Mot de passe oublié')
@section('content')

@if(!$errors->isEmpty())
<div class="error-alert">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<section class="login-page">
    <header class="login-header">
        <h2>Mot de passe oublié</h2>
    </header>
    <form method="POST" action="{{ route('user.email') }}" class="login-form">
        @csrf
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" placeholder="Entrez votre email" value="{{ old('email') }}" required>
        </div>
        <button type="submit" class="btn">Envoyer</button>
    </form>
</section>
@endsection
