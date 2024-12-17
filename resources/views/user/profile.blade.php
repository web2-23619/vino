@extends('layouts.app')
@section('title', 'Mon Profil')
@section('content')
<section class="profile-page">
    <!-- Header Section -->
    <header class="profile-header">
        <h2 class="username">{{$user->username}}</h2>
    </header>

    <!-- Stats Section -->
    <div class="profile-stats">
        <div class="stat-item">
            <p class="stat-number">2</p>
            <p class="stat-label">Celliers</p>
        </div>
        <div class="stat-item">
            <p class="stat-number">8</p>
            <p class="stat-label">Bouteilles</p>
        </div>
        <div class="stat-item">
            <p class="stat-number">3</p>
            <p class="stat-label">À acheter</p>
        </div>
    </div>

    <!-- Placeholder for Bottles List -->
    <div class="bottles-list">
        <div class="bottle-item">
            <div class="bottle-placeholder"></div>
            <p class="bottle-name">Rouge | Shiraz</p>
            <p class="bottle-desc">Importation privée 2024 <br> 750 ml | Italie</p>
            <div class="bottle-quantity">
                <button>-</button>
                <span>2</span>
                <button>+</button>
            </div>
        </div>
        <div class="bottle-item">
            <div class="bottle-placeholder"></div>
            <p class="bottle-name">Rouge | Shiraz</p>
            <p class="bottle-desc">Importation privée 2024 <br> 750 ml | Italie</p>
            <div class="bottle-quantity">
                <button>-</button>
                <span>2</span>
                <button>+</button>
            </div>
        </div>
    </div>
</section>
@endsection
