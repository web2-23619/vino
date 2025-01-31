<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Bottle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class PurchaseController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$countries = Bottle::select('country')
			->selectRaw('COUNT(*) as total')
			->groupBy('country')
			->orderByDesc('total')
			->get();

		$countryNames = $countries->pluck('country')->toArray();

		$initialCountries = array_slice($countryNames, 0, 5);
		$remainingCountries = array_slice($countryNames, 5);
		$remainingCount = count($remainingCountries);

		$types = Bottle::select('type')
			->distinct()
			->get();

		return view('purchase.index', ['initialCountries' => $initialCountries, 'remainingCountries' => $remainingCountries, 'remainingCount' => $remainingCount, 'types' => $types]);
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

	public function showAddBottleForm(Request $request, $bottle_id)
{
    $bottle = Bottle::findOrFail($bottle_id);
    $userCellars = Auth::user()->cellars;

    // Retrieve 'source' from the request query parameter
    $source = request()->query('source', 'cellier'); 

    // If the source is 'listeAchat', override it to 'cellier' so users can select a cellar
    if ($source == 'listeAchat') {
        $source = 'cellier';
    }
	$quantity = $request->query('quantity', 1);

    return view('bottle.addBottle', compact('bottle', 'userCellars', 'source', 'quantity'));
}


	public function addBottle(Request $request)
	{
		// Validation de la requête
		$request->validate([
			'bottle_id' => 'required|exists:bottles,id',
			'quantity' => 'required|integer|min:1',
		]);

		// Récupérer l'utilisateur connecté
		$user = Auth::user();

		// Vérifie si la bouteille existe déjà dans les achats de l'utilisateur
		$existingPurchase = Purchase::where('user_id', $user->id)
			->where('bottle_id', $request->input('bottle_id'))
			->first();

		if ($existingPurchase) {
			// Si la bouteille existe, mettre à jour la quantité
			$currentQuantity = $existingPurchase->quantity;
			$newQuantity = $currentQuantity + $request->input('quantity');

			// Mettre à jour la quantité de la bouteille
			$existingPurchase->update(['quantity' => $newQuantity]);


			return redirect()->route('purchase.index')
				->with('success', 'La quantité de la bouteille a été mise à jour avec succès!');
		} else {
			// Sinon, créer une nouvelle entrée d'achat
			Purchase::create([
				'user_id' => $user->id,
				'bottle_id' => $request->input('bottle_id'),
				'quantity' => $request->input('quantity'),
			]);

			return redirect()->route('purchase.index')
				->with('success', 'Bouteille ajoutée avec succès!');
		}
	}



	public function updateQuantityApi(Request $request, Purchase $purchase)
	{
		$request->validate([
			'quantity' => 'required|integer|min:0',
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
	 * retourne tous les achats
	 */
	public function AllPurchaseApi(Request $request)
	{
		$user = Auth::user();
	
		// Récupération des achats avec les informations sur les bouteilles associées
		$purchases = Purchase::join('bottles', 'purchases.bottle_id', '=', 'bottles.id')
			->where('user_id', $user->id)
			->select('purchases.id as purchase_id', 'purchases.quantity as purchase_quantity', 'bottles.*')
			->orderBy('bottles.name', 'asc')
			->get();
	
		$empty = true;
		if (count($purchases) > 0) {
			$empty = false;
		}
	
		// Ajout du champ is_favorite pour chaque bouteille
		$purchases = $purchases->map(function ($purchase) use ($user) {
			return [
				'purchase_id' => $purchase->purchase_id,
				'purchase_quantity' => $purchase->purchase_quantity,
				'id' => $purchase->id,
				'name' => $purchase->name,
				'price' => $purchase->price,
				'image_url' => $purchase->image_url,
				'country' => $purchase->country,
				'volume' => $purchase->volume,
				'type' => $purchase->type,
				'is_favorite' => $user ? $user->isFavorite($purchase->id) : false, // Vérifier si la bouteille est favorite
			];
		});
	
		return response()->json([
			'purchases' => $purchases,
		], 200);
	}
	


	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($purchaseId)
	{
		// Trouver l’achat par son ID
		$purchase = Purchase::findOrFail($purchaseId);

		
		if ($purchase->delete()) {
			return response()->json(['message' => 'Bouteille retiré avec succes de la liste d\'achat'], 200);
		} else {
			return response()->json(['message' => 'Erreur au retrait de la bouteille'], 400);
		}
	}
	
	// ajouter la bouteille a cellier de liste d'achat
	public function addToCellar(Request $request)
	{
		try {
			// Validation
			$validated = $request->validate([
				'bottleId' => 'required|integer|exists:bottles,id',
				'cellarId' => 'required|integer|exists:cellars,id',
				'quantity' => 'required|integer|min:1',
			]);
	
	
	
			// Vérifiez si la bouteille existe déjà dans la cellier
			$cellarBottle = DB::table('cellar_has_bottles')
				->where('bottle_id', $validated['bottleId'])
				->where('cellar_id', $validated['cellarId'])
				->first();
	
			if ($cellarBottle) {
				// Mettre à jour la quantité si la bouteille existe
				DB::table('cellar_has_bottles')
					->where('bottle_id', $validated['bottleId'])
					->where('cellar_id', $validated['cellarId'])
					->update([
						'quantity' => $cellarBottle->quantity + $validated['quantity'],
						'updated_at' => now(),
					]);
			
			} else {
				// Insérer si la bouteille est neuve
				DB::table('cellar_has_bottles')->insert([
					'cellar_id' => $validated['cellarId'],
					'bottle_id' => $validated['bottleId'],
					'quantity' => $validated['quantity'],
					'created_at' => now(),
					'updated_at' => now(),
				]);
			
			}
	
			// Vérifier si la bouteille existe dans les achats avant la suppression
			$purchaseExists = DB::table('purchases')
				->where('bottle_id', $validated['bottleId'])
				->where('user_id', Auth::id())
				->exists();

	
			if (!$purchaseExists) {
			} else {
		
	       $deletedRows = DB::table('purchases')
	       ->where('bottle_id', $validated['bottleId'])
	       ->where('user_id', Auth::id())
	       ->delete();
	
	      if ($deletedRows === 0) {
	     return response()->json(['error' => 'Delete failed, bottle still in purchases'], 500);
	    }
	
	
			}
	
			// Rediriger vers la page d’inventaire
			return redirect()->route('inventaire')->with('success', 'Bouteille ajoutée avec succès!');
	
		} catch (\Exception $e) {
			return response()->json(['error' => 'Erreur lors de l\'ajout'], 500);
		}
	}
	
}