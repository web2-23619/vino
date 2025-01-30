<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
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
		return view('welcome');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$credentials = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required'],
		]);

		if (Auth::attempt($credentials)) {
			$request->session()->regenerate();

			
			return redirect()->route('user.profile')->with('success', 'Connexion réussie!');
		}

		return redirect(route('login'))
			->withErrors(trans('auth.failed'))
			->withInput();
	}

	/**
	 * used to return token for token based authentification
	 */
	public function apiLogin(Request $request)
	{
		$credentials = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required'],
		]);

		if (Auth::attempt($credentials)) {
			$user = Auth::user();

			// creer un token pour sanctum
			$token = $user->createToken('Vino')->plainTextToken;

			return response()->json([
				'message' => 'Login successful',
				'token' => $token,
			]);
		}

		return response()->json([
			'message' => 'Informations d\'autentification invalides',
		], 401);
	}


	
	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Request $request)
	{
		Session::flush();
		Auth::logout();

		return redirect()->route('welcome')->with('success', 'Déconnexion réussie!');
	}

	public function deletedUser(){

		return redirect()->route('welcome')->with('success', 'Utilisateur supprimé!');
	}

	/**
	 * destroy api token
	 */
	public function apiLogout(Request $request)
	{
		// Révoquez le jeton qui a été utilisé pour authentifier l'utilisateur
		$request->user()->tokens->each(function ($token) {
			$token->delete();
		});

		return response()->json(['message' => 'Logged out successfully']);
	}
}
