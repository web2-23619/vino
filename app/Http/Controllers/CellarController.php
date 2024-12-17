<?php

namespace App\Http\Controllers;

use App\Models\Cellar;
use Illuminate\Http\Request;

class CellarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
		  $user_id = 3;
		  $cellars = Cellar::select()->where('user_id', $user_id)->orderBy('name')->get();
        // Retourner la vue avec la liste des celliers
        return view('cellars.index', compact('cellars'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cellars.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'name' => 'required|string|max:255', 
            'quantity' => 'required|integer|min:0', 
        ]);
    
        // Création du nouveau cellier
        $cellar = new Cellar([
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity'), 
            // 'user_id' => auth()->id(), 
        ]);
    
        $cellar->save();
    
        return redirect()->route('cellars.index')->with('success', 'Cellier ajouté avec succès!');
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
        
        // if ($cellar->user_id !== auth()->id()) {
        //     return redirect()->route('cellars.index')->with('error', 'Accès non autorisé!');
        // }
    
        return view('cellars.edit', compact('cellar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cellar $cellar)
    {
        // Validation des données du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
        ]);
    

        // if ($cellar->user_id !== auth()->id()) {
        //     return redirect()->route('cellars.index')->with('error', 'Accès non autorisé!');
        // }
    
        // Mise à jour des données du cellier
        $cellar->update([
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity'),
        ]);
    
        return redirect()->route('cellars.index')->with('success', 'Cellier modifié avec succès!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cellar $cellar)
    {
    $cellar->delete();

		return	redirect()->route('cellar.index')->with('succes', 'Cellier supprimé avec succès');

    }
}
