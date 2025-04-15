<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function index(Request $request)
    {
        // Get the selected view from the request, default to 'direct'
        $viewType = $request->input('view', 'direct');

            // Return the appropriate view based on the selection
            if ($viewType === 'api') {
                return view('api_view'); // Create this view
            } elseif ($viewType === 'direct') {
                return view('direct_view.register'); // Create this view
            }

            // Default to the view selection page
            return view('view_selection');
    }
    public function show($view = null) // Set a default value
    {
        if (is_null($view)) {
            return view('view_selection'); 
            //return response()->json(['message' => 'No view specified'], 400); // Handle the case where no view is provided
        }
    
        // Logic to handle the view
        return view($view.'/login'); // Assuming you have a view file named 'direct.blade.php'
    }
    
    
}
