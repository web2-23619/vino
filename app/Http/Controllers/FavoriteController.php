<?php
namespace App\Http\Controllers;

use App\Models\Bottle;
use App\Models\User;
use App\Models\Cellar;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Afficher les favoris de l'utilisateur connecté (front-end).
     */
    public function index()
    {
        $favorites = Auth::user()->favorites()->get();
        return view('favorites.index', compact('favorites'));
    }

    /**
     * Ajouter une bouteille aux favoris (front-end).
     */
    public function add(Request $request, $bottleId)
    {
        // Vérifie si la bouteille existe
        $bottle = Bottle::findOrFail($bottleId);

        // Ajouter la bouteille aux favoris de l'utilisateur connecté
        Auth::user()->favorites()->syncWithoutDetaching([$bottleId]);

        // Redirige vers la page des favoris avec un message de succès
        return redirect()->route('favorites.index')->with('success', "{$bottle->name} a été ajoutée à vos favoris.");
    }

    /**
     * Supprimer une bouteille des favoris (front-end).
     */
    public function remove($bottleId)
    {
        Auth::user()->favorites()->detach($bottleId);
        return redirect()->back()->with('success', 'Bouteille retirée des favoris.');
    }

    /**
     * API : Afficher tous les favoris de l'utilisateur connecté.
     */
    public function allFavoritesApi()
    {
        $favorites = Auth::user()->favorites()->get();
        
        if ($favorites->isEmpty()) {
            return response()->json(['message' => 'No favorites found'], 404);
        }
    
        return response()->json(['favorites' => $favorites], 200);
    }
    

    /**
     * API : Ajouter une bouteille aux favoris.
     */
    public function addFavoriteApi(Request $request)
    {
        $request->validate(['bottle_id' => 'required|exists:bottles,id']);
        $bottleId = $request->input('bottle_id');
        Auth::user()->favorites()->syncWithoutDetaching([$bottleId]);
        return response()->json(['favoris' => $bottleId,'message' => 'Bouteille ajoutée aux favoris avec succès.'], 200);
    }

    /**
     * API : Supprimer une bouteille des favoris.
     */
    public function removeFavoriteApi($bottleId)
    {
        // Vérifie si l'utilisateur est authentifié
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié.'], 401); // 401 Unauthorized
        }
    
        // Vérifie si la bouteille existe dans les favoris
        $favorite = $user->favorites()->where('bottle_id', $bottleId)->first();
    
        if (!$favorite) {
            return response()->json(['message' => 'Cette bouteille n\'est pas dans vos favoris.'], 404); // 404 Not Found
        }
    
        // Détache la bouteille des favoris
        $user->favorites()->detach($bottleId);
    
        return response()->json(['message' => 'Bouteille retirée des favoris avec succès.'], 200); // 200 OK
    }
    

    public function toggle(Request $request)
    {
        $user = Auth::user();
        $bottleId = $request->input('bottle_id');

        if (!$bottleId) {
            return response()->json(['error' => 'ID de la bouteille manquant.'], 400);
        }

        // Ajouter ou retirer des favoris
        if ($user->favorites()->where('bottle_id', $bottleId)->exists()) {
            $user->favorites()->detach($bottleId);
            return response()->json(['message' => 'Bouteille retirée des favoris.']);
        } else {
            $user->favorites()->attach($bottleId);
            return response()->json(['message' => 'Bouteille ajoutée aux favoris.']);
        }
    }

}
