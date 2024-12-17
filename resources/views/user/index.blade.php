@extends('layouts.app')
@section('title', 'Liste des Utilisateurs')
@section('content')
<section>
    <header>
        <h2>Liste des Utilisateurs</h2>
    </header>

    <!-- Tableau pour afficher la liste des utilisateurs -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td> 
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Aucun utilisateur trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection
