<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{  

public function index(Request $request)
{
    // Simulan ang query nang walang user_id filter
    $query = Recipe::query();

    // I-filter gamit ang category parameter kung mayroon sa URL
    if ($request->filled('category')) {
        $category = $request->query('category');
        $query->where('category', 'LIKE', '%' . $category . '%');
    }

    $recipes = $query->latest()->get();

    return view('dashboard', compact('recipes'));
}
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming form fields
        $request->validate([
            'name' => 'required|string|max:255',
            'base_servings' => 'nullable|integer',
            'prep_time_minutes' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // 2. Format the arrays into clean text strings
        $categoryString = implode(', ', $request->input('category', []));
        $ingredientsText = implode("\n", $request->input('ingredients', []));
        $instructionsText = implode("\n", $request->input('instructions', []));

        // 3. Create a new recipe instance
        $recipe = new Recipe();
        $recipe->name = $request->name;
        $recipe->category = $categoryString; // Converts the array into a clean string (e.g., "Lunch, Dinner")
        $recipe->ingredients = $ingredientsText;
        $recipe->instructions = $instructionsText;
        $recipe->base_servings = $request->base_servings;
        $recipe->prep_time_minutes = $request->prep_time_minutes;

        // 4. Handle image upload if a file was selected
        if ($request->hasFile('image')) {
            $recipe->image_path = $request->file('image')->store('recipes', 'public');
        }

        // 5. Save and redirect
        $recipe->save();

        return redirect()->route('dashboard')->with('success', 'Recipe created successfully!');
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
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Find the recipe being updated
        $recipe = Recipe::findOrFail($id);

        // 2. Validate incoming fields
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'base_servings' => 'nullable|integer',
            'prep_time_minutes' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 3. Format multiple categories selection
        $categoryString = implode(', ', $request->input('category', []));

        // 4. Update core properties directly
        $recipe->name = $request->name;
        $recipe->category = $categoryString;
        $recipe->ingredients = $request->ingredients; // Saved cleanly as plain text
        $recipe->instructions = $request->instructions; // Saved cleanly as plain text
        $recipe->base_servings = $request->base_servings;
        $recipe->prep_time_minutes = $request->prep_time_minutes;

        // 5. Handle image uploads safely
        if ($request->hasFile('image')) {
            $recipe->image_path = $request->file('image')->store('recipes', 'public');
        }

        // 6. Save the changes to your database
        $recipe->save();

        // 7. REDIRECT BACK: This resolves the blank page issue
        return redirect()->route('dashboard')->with('success', 'Recipe updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}