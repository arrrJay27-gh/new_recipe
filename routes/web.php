<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Models\Recipe; // Imported the Recipe Model

Route::resource('recipes', RecipeController::class);

Route::get('/', function () {
    return view('welcome');
});

// Updated this route to fetch and pass real data to your dashboard
Route::get('/dashboard', function () {
    $recipes = Recipe::all();
    return view('dashboard', compact('recipes'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';