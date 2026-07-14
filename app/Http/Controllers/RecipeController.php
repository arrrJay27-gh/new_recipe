<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Convert arrays into structured line-by-line text blocks
    $ingredientsText = implode("\n", $request->input('ingredients', []));
    $instructionsText = implode("\n", $request->input('instructions', []));

    $recipe = new Recipe();
    $recipe->name = $request->name;
    $recipe->category = $request->category;
    $recipe->ingredients = $ingredientsText;      // Saved clean!
    $recipe->instructions = $instructionsText;    // Saved clean!
    $recipe->base_servings = $request->base_servings;
    $recipe->prep_time_minutes = $request->prep_time_minutes;

    if ($request->hasFile('image')) {
        $recipe->image_path = $request->file('image')->store('recipes', 'public');
    }

    $recipe->save();

    return redirect()->back()->with('success', 'Recipe created successfully!');
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