@extends('welcome')

@section('content')
    <h1>Mes Celliers</h1>
    
    @if($cellars->isEmpty())
        <div class="no-cellars">
            <p>Aucun cellier disponible</p>
        </div>
    @else
        <div class="cellars-list">
            @foreach ($cellars as $cellar)
                <div class="cellar-item">
                    <div class="cellar-info">
                        <p><strong>{{ $cellar->name }}</strong></p>
                    </div>

                    <div class="cellar-actions">
                        <div class="dropdown">
                            <button class="dropbtn">...</button>
                            <div class="dropdown-content">
                                <a href="{{ route('cellars.edit', $cellar->id) }}">Modifier</a>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Bouton Ajouter Cellier sticky -->
    <a href="{{ route('cellars.create') }}" class="add-cellar-btn">Ajouter un Cellier</a>

@endsection
