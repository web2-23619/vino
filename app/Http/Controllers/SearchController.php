<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bottle;
use App\Models\Cellar;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{

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

			return redirect()->route('cellar.showBottles', ['cellar' => $cellar->id])
				->with('success', 'La quantité de la bouteille a été mise à jour avec succès!');
		} else {
			// Sinon, ajouter la bouteille au cellier
			$cellar->bottles()->attach($bottle, ['quantity' => $request->input('quantity')]);

			return redirect()->route('cellar.index', ['cellar' => $cellar->id])
				->with('success', 'Bouteille ajoutée avec succès!');
		}
	}

	public function showAddBottleForm($bottleId, Request $request)
	{
		// Récupérer la bouteille
		$bottle = Bottle::findOrFail($bottleId);
		$userCellars = Auth::user()->cellars;

		if ($request->has('cellar_id')) {
			$cellar_id = $request->input('cellar_id');
		} else {
			$cellar_id = null;
		}

		if ($request->has('source')) {
			$source = $request->input('source');
		}

		$selectedCellarName = $cellar_id ? Cellar::find($cellar_id)->name : null;

		return view('bottle.addBottle', compact('bottle', 'userCellars', 'source', 'selectedCellarName', 'cellar_id'));
	}

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
		}
		if ($request->has('cellar_id')) {
			$cellar_id = $request->input('cellar_id');
		} else{
			$cellar_id = null;
		}
		
		
		// Si aucune requête de recherche, afficher les bouteilles aléatoires
		$randomBottles = Bottle::inRandomOrder()->take(5)->get();

		// recupérer donner pour afficher les filtres
		$countries = Bottle::select('country')->distinct()->get();

		$countryNames = $countries->pluck('country')->toArray();

		$initialCountries = array_slice($countryNames, 0, 5);
		$remainingCountries = array_slice($countryNames, 5);
		$remainingCount = count($remainingCountries);

		$types = Bottle::select('type')
			->distinct()
			->get();

		return view('search.index', compact('randomBottles', 'source', 'initialCountries', 'remainingCountries', 'remainingCount', 'types', 'cellar_id'));
	}

	public function searchApi(Request $request)
	{
		// Valider l’entrée de recherche
		$request->validate([
			'query' => 'required|string|min:2|max:255',
		]);

		// Conserver ou récupérer la source depuis l'URL ou la session
		if ($request->has('source')) {
			$source = $request->input('source');
		}
		if ($request->has('cellar_id')) {
			$cellar_id = $request->input('cellar_id');
		} else {
			$cellar_id = null;
		}
		

		$query = Bottle::query();
		$searchQuery = $request->input('query');

		// Rechercher les bouteilles correspondant à la requête
		$searchTerms = explode(' ', $searchQuery); // Split search query into keyword
		foreach ($searchTerms as $term) {
			// Search for each keyword in 'name', 'country', and 'type' columns
			$query->where(function ($query) use ($term) {
				$query->where('name', 'like', '%' . $term . '%')
					->orWhere('country', 'like', '%' . $term . '%')
					->orWhere('type', 'like', '%' . $term . '%')
					->orWhere('upc_saq', 'like', '%' . $term . '%');
			});
		}

		// filtrer par pays
		$countries = $request->input('countries', []);

		if (!empty($countries)) {
			$query->whereIn('country', $countries);
		}

		// filtrer par pays
		$types = $request->input('types', []);

		if (!empty($types)) {
			$query->whereIn('type', $types);
		}

		// filtrer par range de prix
		if ($request->filled('min_price')) {
			$query->where('price', '>=', $request->input('min_price'));
		}
		if ($request->filled('max_price')) {
			$query->where('price', '<=', $request->input('max_price'));
		}


		// trier
		if ($request->has('tri')) {
			list($criteria, $order) = explode("_", $request->tri);
			$query->orderBy($criteria, $order);
		} else {
			// par default
			$query->orderBy('name', 'asc');
		}


		// Handling Pagination
		$results = $query->paginate(30);

		// Récupérer les celliers de l’utilisatrice pour la liste déroulante
		$userCellars = auth()->check() ? auth()->user()->cellars : [];
		$source = $request->input('source');

		return response()->json(['searchQuery' => $searchQuery, 'results' => $results, 'source' => $source, 'cellar_id' => $cellar_id], 200);
	}
}
