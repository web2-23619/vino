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
		$countries = Bottle::select('country')->distinct()->get();

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

	//public function showAddBottleForm($bottle_id)
	//{
		// Récupérer la bouteille par ID
		//$bottle = Bottle::findOrFail($bottle_id);

		// Récupérer la liste d'achat de l'utilisateur connecté
		//$userCellars = Auth::user()->cellars;

		// Retourner la vue pour le formulaire d'ajout de la bouteille
		//return view('bottle.addBottlePurchase', compact('bottle', 'userCellars'));
	//}

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

		$purchases = Purchase::join('bottles', 'purchases.bottle_id', '=', 'bottles.id')
			->where('user_id', Auth::user()->id)
			->select('purchases.id as purchase_id', 'purchases.quantity as purchase_quantity', 'bottles.*')
			->orderBy('bottles.name', 'asc')
			->get();

		$empty = true;
		if (count($purchases) > 0) {
			$empty = false;
		}

		return response()->json(['purchases' => $purchases, 'empty' => $empty, 'filtered'=>false], 200);
	}



	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($purchaseId)
	{
		// Find the purchase by its ID
		$purchase = Purchase::findOrFail($purchaseId);

		// Delete the purchase
		if ($purchase->delete()) {
			// Return a response indicating success
			return response()->json(['message' => 'Bouteille retiré avec succes de la liste d\'achat'], 200);
		} else {
			return response()->json(['message' => 'Erreur au retrait de la bouteille'], 400);
		}
	}
	public function addToCellar(Request $request)
	{
		try {
			// ✅ Validate request
			$validated = $request->validate([
				'bottleId' => 'required|integer|exists:bottles,id',
				'cellarId' => 'required|integer|exists:cellars,id',
				'quantity' => 'required|integer|min:1',
			]);
	
			\Log::info('Validated data:', $validated);
	
			// ✅ Check if the bottle already exists in the cellar
			$cellarBottle = DB::table('cellar_has_bottles')
				->where('bottle_id', $validated['bottleId'])
				->where('cellar_id', $validated['cellarId'])
				->first();
	
			if ($cellarBottle) {
				// ✅ Update quantity if bottle exists
				DB::table('cellar_has_bottles')
					->where('bottle_id', $validated['bottleId'])
					->where('cellar_id', $validated['cellarId'])
					->update([
						'quantity' => $cellarBottle->quantity + $validated['quantity'],
						'updated_at' => now(),
					]);
				\Log::info('Bottle quantity updated in cellar.');
			} else {
				// ✅ Insert if bottle is new
				DB::table('cellar_has_bottles')->insert([
					'cellar_id' => $validated['cellarId'],
					'bottle_id' => $validated['bottleId'],
					'quantity' => $validated['quantity'],
					'created_at' => now(),
					'updated_at' => now(),
				]);
				\Log::info('New bottle added to cellar.');
			}
	
			// ✅ Verify if bottle exists in purchases before deletion
			$purchaseExists = DB::table('purchases')
				->where('bottle_id', $validated['bottleId'])
				->where('user_id', Auth::id())
				->exists();
	
			\Log::info('Does purchase exist before deletion?', ['exists' => $purchaseExists]);
	
			if (!$purchaseExists) {
				\Log::error('Purchase entry not found, skipping deletion.');
			} else {
				// Force execution and return the delete result
	$deletedRows = DB::table('purchases')
	->where('bottle_id', $validated['bottleId'])
	->where('user_id', Auth::id())
	->delete();
	
	if ($deletedRows === 0) {
	return response()->json(['error' => 'Delete failed, bottle still in purchases'], 500);
	}
	
	\Log::info('Successfully deleted bottle from purchases:', ['count' => $deletedRows]);
	
			}
	
			// ✅ Redirect to inventory page
			return redirect()->route('inventaire')->with('success', 'Bouteille ajoutée avec succès!');
	
		} catch (\Exception $e) {
			\Log::error('Erreur lors de l\'ajout au cellier', ['error' => $e->getMessage()]);
			return response()->json(['error' => 'Erreur lors de l\'ajout'], 500);
		}
	}
	
}