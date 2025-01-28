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
        // Conserver ou récupérer la source depuis l'URL ou la session
        if ($request->has('source')) {
            $source = $request->input('source');
            session(['add_bottle_source' => $source]); // Mettre à jour la session
        } else {
            $source = session('add_bottle_source', 'default'); // Récupérer depuis la session ou par défaut
        }
    
        // Si une requête de recherche est présente
        if ($request->has('query') && !empty($request->input('query'))) {
            $query = $request->input('query');
            
            // Rechercher les bouteilles correspondant à la requête
            $results = Bottle::where('name', 'LIKE', "%{$query}%")
                ->orWhere('type', 'LIKE', "%{$query}%")
                ->orWhere('country', 'LIKE', "%{$query}%")
                ->get();
    
            // Renvoyer la vue des résultats de recherche
            return view('search.results', [
                'query' => $query,
                'results' => $results,
                'resultCount' => $results->count(),
                'source' => $source, // Ajouter la source à la vue
            ]);
        }
    
        // Si aucune requête de recherche, afficher les bouteilles aléatoires
        $randomBottles = Bottle::inRandomOrder()->take(5)->get();
    
        return view('search.index', compact('randomBottles', 'source'));
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
   
    /**
     * Retourne les 3 meilleures correspondances pour une recherche d’autocomplétion.
     *
     * La requête doit contenir un paramètre `query` qui contient la chaîne de caractères
     * à rechercher.
     *
     * Si la requête est vide, une réponse vide est renvoyée.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        $query = $request->get('query');
    
        // Vérifier que la requête n'est pas vide
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }
    
        // Rechercher les 3 meilleures correspondances
        $matches = Bottle::where('name', 'LIKE', "%{$query}%")
            ->orWhere('country', 'LIKE', "%{$query}%")
            ->orWhere('type', 'LIKE', "%{$query}%")
            ->take(3)
            ->get(['id', 'name', 'country', 'type']);
    
        return response()->json($matches);
    }
    
    
    
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
    
        // Vérifier si la bouteille existe déjà dans le cellier
        $existingBottle = $cellar->bottles()->where('bottle_id', $bottle->id)->first();
    
        if ($existingBottle) {
            // Si la bouteille existe, mettre à jour la quantité
            $currentQuantity = $existingBottle->pivot->quantity;
            $newQuantity = $currentQuantity + $request->input('quantity');
    
            $cellar->bottles()->updateExistingPivot($bottle->id, ['quantity' => $newQuantity]);
    
            // Oublier les sessions après la mise à jour
            session()->forget('add_bottle_source');
            session()->forget('cellar_id');
    
            return redirect()->route('cellar.showBottles', ['cellar' => $cellar->id])
                             ->with('success', 'La quantité de la bouteille a été mise à jour avec succès!');
        } else {
            // Sinon, ajouter la bouteille au cellier
            $cellar->bottles()->attach($bottle, ['quantity' => $request->input('quantity')]);
    
            // Oublier les sessions après l'ajout
            session()->forget('add_bottle_source');
            session()->forget('cellar_id');
    
            return redirect()->route('cellar.index', ['cellar' => $cellar->id])
                             ->with('success', 'Bouteille ajoutée avec succès!');
        }
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
        
        $source = session('add_bottle_source');
        $selectedCellarName = $cellarId ? Cellar::find($cellarId)->name : null;
    
        return view('bottle.addBottle', compact('bottle', 'userCellars', 'source', 'selectedCellarName'));
    }   

}
