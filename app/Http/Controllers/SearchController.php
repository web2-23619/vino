<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bottle;

class SearchController extends Controller
{
    /**
     * afficher la page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('search.index');
    }

    /**
     * Traiter la demande de recherche et afficher les résultats.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        // Valider l’entrée de recherche
        $request->validate([
            'query' => 'required|string|min:2|max:255',
        ]);

        // Obtenir la requête de recherche
        $query = $request->input('query');

        // Rechercher dans la base de données des bouteilles correspondantes
        $results = Bottle::where('name', 'LIKE', "%{$query}%")
        ->orWhere('country', 'LIKE', "%{$query}%")
        ->orWhere('type', 'LIKE', "%{$query}%")
        ->orWhere('price', 'LIKE', "%{$query}%")
        ->orWhere('upc_saq', 'LIKE', "%{$query}%")
        ->orderBy('name')
        ->paginate(10);

        // Récupérer les celliers de l’utilisatrice pour la liste déroulante
        $userCellars = auth()->check() ? auth()->user()->cellars : [];

        return view('search.results', compact('query', 'results', 'userCellars'));
    }
}
