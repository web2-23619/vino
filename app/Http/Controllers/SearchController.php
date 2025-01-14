<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bottle;
use App\Models\Cellar;
use Illuminate\Support\Facades\Auth;

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
        ->get();

        // Récupérer les celliers de l’utilisatrice pour la liste déroulante
        $userCellars = auth()->check() ? auth()->user()->cellars : [];

        return view('search.results', compact('query', 'results', 'userCellars'));
    }



    public function addBottle(Request $request)
    {
        // Validation de la requête
        $request->validate([
            'cellar_id' => 'required|exists:cellars,id',
            'bottle_id' => 'required|exists:bottles,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        // Vérifier si le cellier appartient à l'utilisateur connecté
        $user = Auth::user();
        $cellar = Cellar::where('id', $request->input('cellar_id'))
                        ->where('user_id', $user->id)
                        ->first();
    
        // Si le cellier n'existe pas ou n'appartient pas à l'utilisateur
        if (!$cellar) {
            return redirect()->route('cellar.index')->with('error', 'Cellier non trouvé ou accès non autorisé.');
        }
    
        // Récupérer la bouteille
        $bottle = Bottle::find($request->input('bottle_id'));
    
        // Ajouter la bouteille au cellier avec la quantité
        $cellar->bottles()->attach($bottle, ['quantity' => $request->input('quantity')]);
    
        // Rediriger vers la page des bouteilles du cellier
        return redirect()->route('cellar.showBottles', ['cellar' => $cellar->id])
                         ->with('success', 'Bouteille ajoutée avec succès!');
    }
    
    public function showAddBottleForm($bottle_id)
{
    // Récupérer la bouteille par ID
    $bottle = Bottle::findOrFail($bottle_id);

    // Récupérer les celliers de l'utilisateur connecté
    $userCellars = Auth::user()->cellars;

    // Retourner la vue pour le formulaire d'ajout de la bouteille
    return view('bottle.addBottle', compact('bottle', 'userCellars'));
}

}
