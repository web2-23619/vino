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
    public function index(Request $request)
    {
        session()->forget('add_bottle_source');
        if ($request->has('source')) {
            session(['add_bottle_source' => $request->input('source')]);
        }
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
        
        if ($request->has('cellar_id')) {
            session(['cellar_id' => $request->input('cellar_id')]);
        }
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
        $resultCount = $results->count();
        $source = session('add_bottle_source', 'default');
        return view('search.results', compact('query', 'results','resultCount', 'userCellars', 'source'));
    }
   
    // public function autocomplete(Request $request)
    // {
    //     $query = $request->get('query'); 
    
       
    //     $results = Bottle::where('name', 'LIKE', "%{$query}%")
    //         ->orWhere('country', 'LIKE', "%{$query}%")
    //         ->orWhere('type', 'LIKE', "%{$query}%")
    //         ->distinct()
    //         ->take(10)
    //         ->pluck('name');
    
    //     return response()->json($results);
    // }
    
    
    public function addBottle(Request $request)
    {
        // Validation de la requête
        $request->validate([
            'cellar_id' => 'required|exists:cellars,id',
            'bottle_id' => 'required|exists:bottles,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $user = Auth::user();
        $cellar = Cellar::where('id', $request->input('cellar_id'))
                        ->where('user_id', $user->id)
                        ->first();
    
        if (!$cellar) {
            return redirect()->route('cellar.index')->with('error', 'Cellier non trouvé ou accès non autorisé.');
        }
    
        $bottle = Bottle::find($request->input('bottle_id'));
    
        if ($cellar->bottles()->where('bottle_id', $bottle->id)->exists()) {
            return redirect()->route('cellar.showBottles', ['cellar' => $cellar->id])
                             ->with('error', 'La bouteille est déjà dans ce cellier.');
        }
    
        $cellar->bottles()->attach($bottle, ['quantity' => $request->input('quantity')]);
    
        session()->forget('add_bottle_source');
        session()->forget('cellar_id');
    
        return redirect()->route('cellar.showBottles', ['cellar' => $cellar->id])
                         ->with('success', 'Bouteille ajoutée avec succès!');
    }    
    
    
    public function showAddBottleForm($bottleId, Request $request)
    {
        // Récupérer la bouteille
        $bottle = Bottle::findOrFail($bottleId);
        $userCellars = Auth::user()->cellars;
        $cellarId = $request->input('cellar_id', session('cellar_id'));
        if ($cellarId) {
            session(['cellar_id' => $cellarId]);
        }
        
        $source = session('add_bottle_source', 'default');
        $selectedCellarName = $cellarId ? Cellar::find($cellarId)->name : null;
    
        return view('bottle.addBottle', compact('bottle', 'userCellars', 'source', 'selectedCellarName'));
    }   

}
