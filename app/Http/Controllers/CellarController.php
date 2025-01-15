<?php

namespace App\Http\Controllers;

use App\Models\Bottle;
use App\Models\Cellar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CellarController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$user_id = Auth::user()->id;
		$cellars = Cellar::select()->where('user_id', $user_id)->orderBy('name')->get();
		// Retourner la vue avec la liste des celliers
		return view('cellar.index', ['cellars' => $cellars, 'addButton' => 'Cellier']);
	}


	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		return view('cellar.create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		// Validation des données du formulaire
		$request->validate([
			'name' => 'required|string|max:255',
			// 'quantity' => 'required|integer|min:0',
		]);

		// Création du nouveau cellier
		$cellar = new Cellar([
			'name' => $request->input('name'),
			// 'quantity' => $request->input('quantity'),
			'user_id' => Auth::user()->id,
		]);

		$cellar->save();

		return redirect()->route('cellar.index')->with('succes', 'Cellier ajouté avec succès!');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Cellar $cellar)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Cellar $cellar)
	{

		if ($cellar->user_id !== Auth::user()->id) {
		    return redirect()->route('cellar.index')->with('erreur', 'Accès non autorisé!');
		}

		return view('cellar.edit', compact('cellar'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Cellar $cellar)
	{
		// Validation des données du formulaire
		$request->validate([
			'name' => 'required|string|max:255',
			// 'quantity' => 'required|integer|min:0',
		]);


		if ($cellar->user_id !== Auth::user()->id) {
			return redirect()->route('cellar.index')->with('erreur', 'Accès non autorisé!');
		}

		// Mise à jour des données du cellier
		$cellar->update([
			'name' => $request->input('name'),
			// 'quantity' => $request->input('quantity'),
		]);

		return redirect()->route('cellar.index')->with('succes', 'Cellier modifié avec succès!');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Cellar $cellar)
	{
		$cellar->delete();

		return response()->json(['message' => 'Cellier supprimé avec succes'], 200);
	}

	public function showBottles(Cellar $cellar)
	{
		// récupere les bouteilles d'un cellier
		$cellar = Cellar::with('bottles')->find($cellar->id);

		// Flatten the bottles from all cellars into a single collection
		$bottles = $cellar->bottles->map(function ($bottle) {
			return [
				'id' => $bottle->id,
				'name' => $bottle->name,
				'price' => $bottle->price,
				'image_url' => $bottle->image_url,
				'country' => $bottle->country,
				'volume' => $bottle->volume,
				'type' => $bottle->type,
				'quantity' => $bottle->pivot->quantity,  // Access quantity from pivot table
			];
		});

		// Pass the bottles to the view
		return view('bottle.byCellar', ['bottles'=>$bottles, 'cellar'=>$cellar]);
	}

	public function apiRemoveBottle($cellar_id, $bottle_id){

		$cellar = Cellar::find($cellar_id);
		$bottle = Bottle::find($bottle_id);

		if($cellar && $bottle){
			$cellar->bottles()->detach($bottle->id);
			return response()->json(['message' => 'Bouteille retiré avec succès'], 200);
		} else {
			return response()->json(['message' => 'Erreur au retrait de la bouteille'], 400);
		}

	}

	/**
	 * Update the quantity of a bottle in a cellar
	 */	
	public function updateQuantityApi(Request $request, $cellarId, $bottleId){

		$request->validate([
			'quantity' => 'required|integer|min:0',
		]);    

    	$cellar = Auth::user()->cellars()->find($cellarId);
    	if (!$cellar) {
        	return response()->json(['error' => 'Cellier non trouvé ou accès non autorisé'], 404);
    	}

    	// Vérification si la bouteille est dans ce cellier
    	$bottle = $cellar->bottles()->where('bottle_id', $bottleId)->first();
    	if (!$bottle) {
        	return response()->json(['error' => 'Bouteille non trouvée dans ce cellier'], 404);
    	}

    	// Mettre à jour la quantité dans la table pivot
    	$bottle->pivot->quantity = $request->input('quantity');
    	$bottle->pivot->save();

    	// Retourner la réponse avec la nouvelle quantité mise à jour
    	return response()->json([
        	'message' => 'Quantité mise à jour avec succès',
        	'quantity' => $bottle->pivot->quantity,
    	]);
	}


	// fonction pour ajouter la bouteille dans la page de recherche
	//public function addBottle(Request $request)
	//{
		// Validate the request
		//$request->validate([
			//'bottle_id' => 'required|exists:bottles,id',
			//'cellar_id' => 'required',
		//]);
	
		// Check if the user is authenticated
		//if (!auth()->check()) {
			//return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
		//}
	
		//$user = auth()->user();
	
		//if ($request->cellar_id === 'wishlist') {
			//return response()->json(['message' => 'Bouteille ajoutée à la liste d\'achat!']);
		//} else {
			//$cellar = Cellar::where('id', $request->cellar_id)
				//->where('user_id', $user->id)
				//->first();

			//if (!$cellar) {
				//return response()->json(['message' => 'Cellier non trouvé.'], 404);
			//}
	
			//$cellar->bottles()->attach($request->bottle_id, ['quantity' => 1]);
	
			//return response()->json(['message' => 'Bouteille ajoutée au cellier!']);
		//}
	//}
	
}