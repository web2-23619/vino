<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cellar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{

		return view('user.create');
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{

		// Validation
		$request->validate([
			'username' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|string|min:6|max:20',
		]);

		$user = new User;
		$user->fill($request->all());
		$user->password = Hash::make($request->password);
		$user->save();

		if($user->id){
			//creer cellier par default à la création d'un utilisateur
			$cellar = new Cellar([
				'name' => 'Mon cellier',
				'user_id' => $user->id,
			]);
		}

		$cellar->save();

		return redirect()->route('welcome')->with('success', 'Utilisateur créé avec succès!');
	}

	public function profile()
	{
		$user = auth()->user();

	
		// Récupérer les comptes requis de manière dynamique
		$cellarsCount = $user->cellars()->count(); 
	
		// Calculez le nombre total de bouteilles dans toutes les celliers
		$bottlesCount = $user->cellars->sum(function ($cellar) {
			return $cellar->bottles->sum('pivot.quantity');
		});
	
		$toBuyCount = $user->purchases()->sum('quantity'); 
	
		
		return view('user.profile', compact('user', 'cellarsCount', 'bottlesCount', 'toBuyCount'));
	}


	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(User $user)
	{
		return view('user.edit', ['user' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, User $user)
	{

		$request->validate([
			'username' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email,' . $user->id,
		]);


		$user->username = $request->input('username');
		$user->email = $request->input('email');
		$user->save();

		return redirect()->route('user.profile')->with('success', 'Profil mis à jour avec succès!');
	}


	
	public function destroy($userId)
	{
		
		$user = User::findOrFail($userId);
		$user->delete();

		// Retourner une réponse JSON indiquant la réussite
		return response()->json(['message' => 'Utilisateur supprimé avec succès'], 200);
	}



	public function forgot()
	{
		return view('user.forgot');
	}

	public function email(Request $request)
	{
		$request->validate([
			'email' => ['required', 'email', 'exists:users,email']
		]);

		$user = User::where('email', $request->email)->first();

		// Générer un token temporaire
		$tempPassword = Str::random(45);

		// Mettre à jour l'utilisateur avec le token temporaire
		$user->temp_password = Hash::make($tempPassword);
		$user->save();

		$to_name = $user->name;
		$to_email = $user->email;
		$body = "<a href='" . route('user.reset', [$user->id, $tempPassword]) . "'>Cliquez ici pour réinitialiser votre mot de passe</a>";


		Mail::send(
			'user.mail',
			['name' => $to_name, 'body' => $body],
			function ($message) use ($to_email) {
				$message->to($to_email)->subject('Réinitialisation du mot de passe');
			}
		);

		return redirect(route('login'))->with('success', 'Veuillez vérifier votre email pour réinitialiser votre mot de passe.');
	}

	public function reset(User $user, $token)
	{
		// Vérifier le token
		if (Hash::check($token, $user->temp_password)) {
			return view('user.reset', ['user' => $user, 'token' => $token]);
		}

		return redirect(route('user.forgot'))->withErrors('Token invalide ou expiré.');
	}

	public function resetUpdate(User $user, $token, Request $request)
	{
		// Vérifier le token
		if (Hash::check($token, $user->temp_password)) {
			$request->validate([
				'password' => 'required|min:6|max:20|confirmed'
			]);

			// Mettre à jour le mot de passe et réinitialiser le token temporaire
			$user->password = Hash::make($request->password);
			$user->temp_password = null;
			$user->save();

			return redirect(route('login'))->with('success', 'Mot de passe modifié avec succès.');
		}

		return redirect(route('user.forgot'))->withErrors('Token invalide ou expiré.');
	}

	/* afficher toute les bouteilles pour l'utilisateur connecté */
	public function showBottles()
	{
		
		$user = Auth::user();


		// récupere tous les celliers et les bouteilles que chacun contient
		$cellars = $user->cellars()->with(['bottles' => function ($query) {
			$query->withPivot('quantity'); 
		}])->get();

		// Aplatissez les bouteilles de toutes les caves en une seule collection
		$bottles = $cellars->flatMap(function ($cellar) {
			return $cellar->bottles->map(function ($bottle) use ($cellar) {
				return [
					'cellarId' => $cellar->id,
					'cellarName' => $cellar->name,
					'bottleId' => $bottle->id,
					'name' => $bottle->name,
					'quantity' => $bottle->pivot->quantity, 
					'image_url' => $bottle->image_url,
					'price' => $bottle->price,
					'country' => $bottle->country,
					'volume' => $bottle->volume,
					'type' => $bottle->type,
				];
			});
		});

		
		return view('bottle.byUser', compact('bottles'));
	}

	public function updateQuantityApi(Request $request){

    	$request->validate([
        	'bottles' => 'required|array',
        	'bottles.*.cellarId' => 'required|integer|exists:cellars,id',
        	'bottles.*.bottleId' => 'required|integer|exists:bottles,id',
        	'bottles.*.quantity' => 'required|integer|min:0',
    	]);

    	$user = Auth::user();
    	$updatedBottles = [];

    	foreach ($request->input('bottles') as $bottleData) {
        	// Vérification du cellier
        	$cellar = $user->cellars()->find($bottleData['cellarId']);
        	if (!$cellar) {
            	return response()->json(['error' => 'Cellier non trouvé ou accès non autorisé'], 404);
        	}

        	// Vérification de la bouteille dans le cellier
        	$bottle = $cellar->bottles()->where('bottle_id', $bottleData['bottleId'])->first();
        	if (!$bottle) {
            	return response()->json(['error' => 'Bouteille non trouvée dans ce cellier'], 404);
        	}

        	// Mise à jour de la quantité dans la table pivot
        	$bottle->pivot->quantity = $bottleData['quantity'];
        	$bottle->pivot->save();

        	// Ajout des bouteilles mises à jour à la réponse
        	$updatedBottles[] = [
            	'bottleId' => $bottle->id,
            	'quantity' => $bottle->pivot->quantity,
            	'cellarId' => $cellar->id,
        	];
    	}

    	return response()->json([
        	'message' => 'Quantité(s) mise(s) à jour avec succès',
        	'updatedBottles' => $updatedBottles,
    	]);
	}

}
