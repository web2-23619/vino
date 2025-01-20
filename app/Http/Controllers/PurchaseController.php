<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Bottle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$purchases = Purchase::select()->where("user_id", Auth::user()->id)->get();
		return view('purchase.index', ['purchases' => $purchases]);
		// return view('purchase.index', ['purchases'=>$purchases, 'addBottle' => 'Bouteille']);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Purchase $purchase)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Purchase $purchase)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Purchase $purchase)
	{
		// Validation des données du formulaire
		$request->validate([
			'bottle_id' => 'required|exists:bottles,id',
			'quantity' => 'required|integer|min:1',
		]);


		if ($purchase->user_id !== Auth::user()->id) {
			return redirect()->route('purchase.index')->with('erreur', 'Accès non autorisé!');
		}

		// Mise à jour de la quantite de la liste d'achat
		$purchase->update([
			'quantity' => $request->input('quantity'),
		]);

		// return redirect()->route('cellar.index')->with('succes', 'Cellier modifié avec succès!');
	}

	public function showAddBottleForm($bottle_id)
	{
		// Récupérer la bouteille par ID
		$bottle = Bottle::findOrFail($bottle_id);
	
		// Récupérer la liste d'achat de l'utilisateur connecté
		$userCellars = Auth::user()->cellars;
	
		// Retourner la vue pour le formulaire d'ajout de la bouteille
		return view('bottle.addBottlePurchase', compact('bottle', 'userCellars'));
	}

	public function addBottle(Request $request)
	{
		// Validate the request
		$request->validate([
			'bottle_id' => 'required|exists:bottles,id',
			'quantity' => 'required|integer|min:1',
		]);
	
		// Récupérer l'utilisateur connecté
		$user = Auth::user();
	
		// Vérifie si la boutielle existe déjà
		$existingPurchase = Purchase::where('user_id', $user->id)
									->where('bottle_id', $request->input('bottle_id'))
									->first();
		if ($existingPurchase) {
			return redirect()->route('purchase.index')
				->with('error', 'Cette bouteille est déjà dans votre liste d\'achat.');
		} 
			// Créer une nouvelle bouteille si elle n'existe pas dans la liste
		Purchase::create([
			'user_id' => $user->id,
			'bottle_id' => $request->input('bottle_id'),
			'quantity' => $request->input('quantity'),
		]);
		
		session()->forget('add_bottle_source');
		return redirect()->route('purchase.index')->with('success', 'Bouteille ajoutée ou mise à jour avec succès!');
	}


	public function updateQuantityApi(Request $request, Purchase $purchase)
	{
		$request->validate([
			'quantity' => 'required|integer|min:1',
		]);

		if ($purchase->user_id !== Auth::id()) {
			return response()->json(['error' => 'Accès Non-autorisé'], 404);
		}

		// Mettre à jour la quantité
		$purchase->quantity = $request->quantity;
		$purchase->save();

		return response()->json(['message' => 'Quantité mise à jour', 'purchase' => $purchase], 200);
	}

	

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($purchaseId)
	{
		// Find the purchase by its ID
		$purchase = Purchase::findOrFail($purchaseId);

		// Delete the purchase
		if($purchase->delete()){
			// Return a response indicating success
			return response()->json(['message' => 'Bouteille retiré avec succes de la liste d\'achat'], 200);
		} else {
			return response()->json(['message' => 'Erreur au retrait de la bouteille'], 400);
		}

		
	}
}
