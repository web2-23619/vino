<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bottle;

class SearchController extends Controller
{
       /**
     * Display the search page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('search.index');
    }

    /**
     * Handle the search request and display results.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        // Validate the search input
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        // Get the search query
        $query = $request->input('query');

        // Search the database for matching bottles
        $results = Bottle::where('name', 'LIKE', "%{$query}%")
            ->orWhere('country', 'LIKE', "%{$query}%")
            ->orWhere('type', 'LIKE', "%{$query}%")
            ->orderBy('name');
        
        // Fetch user's cellars for the dropdown

            $userCellars = auth()->user()->cellars;

            return view('search.results', [
                'query' => $query,
                'results' => $results,
                'userCellars' => $userCellars,
            ]);    
           

       
    }
}


