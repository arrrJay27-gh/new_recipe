<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Recipe Dashboard') }}
            </h2>
            <div class="flex items-center gap-4">
                <!-- Changed z-50 to z-10 so the user dropdown can display on top of it -->
                <button onclick="document.getElementById('add-recipe-modal').style.display = 'flex'" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-bold z-10">
                    + Add Recipe
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- ADD MODAL (Dynamic ingredients and steps via Alpine.js) -->
            <div id="add-recipe-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                <div class="bg-white p-6 rounded-lg w-full max-w-lg shadow-xl max-h-[90vh] overflow-y-auto"
                     x-data="{ 
                        ingredients: [''], 
                        steps: [''] 
                     }">
                    <h2 class="text-xl font-bold mb-4 text-gray-900">Add New Recipe</h2>
                    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Recipe Name -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                            <input type="text" name="name" class="w-full border rounded p-2 text-gray-900 focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <!-- Multiple Category Checkboxes -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Category (Select one or more)</label>
                            <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded border">
                                <label class="inline-flex items-center text-gray-700 text-sm">
                                    <input type="checkbox" name="category[]" value="Breakfast" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                                    Breakfast
                                </label>
                                <label class="inline-flex items-center text-gray-700 text-sm">
                                    <input type="checkbox" name="category[]" value="Lunch" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                                    Lunch
                                </label>
                                <label class="inline-flex items-center text-gray-700 text-sm">
                                    <input type="checkbox" name="category[]" value="Dinner" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                                    Dinner
                                </label>
                                <label class="inline-flex items-center text-gray-700 text-sm">
                                    <input type="checkbox" name="category[]" value="Desserts" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                                    Desserts
                                </label>
                            </div>
                        </div>
                        
                        <!-- Dynamic Ingredients -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-gray-700 text-sm font-bold">Ingredients with Amount</label>
                                <button type="button" @click="ingredients.push('')" class="bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full transition">
                                    + Add Ingredient
                                </button>
                            </div>
                            <div class="space-y-2">
                                <template x-for="(ingredient, index) in ingredients" :key="index">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-gray-400 w-24 whitespace-nowrap" x-text="'Ingredient #' + (index + 1)"></span>
                                        <input type="text" name="ingredients[]" class="flex-1 border rounded p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500" placeholder="e.g. 1 kg Pork Belly" required>
                                        <button type="button" x-show="ingredients.length > 1" @click="ingredients.splice(index, 1)" class="text-red-500 hover:text-red-700 font-bold px-2 text-lg">
                                            &times;
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Dynamic Instructions/Steps -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-gray-700 text-sm font-bold">Instructions (Steps)</label>
                                <button type="button" @click="steps.push('')" class="bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full transition">
                                    + Add Step
                                </button>
                            </div>
                            <div class="space-y-2">
                                <template x-for="(step, index) in steps" :key="index">
                                    <div class="flex items-start gap-2">
                                        <span class="text-xs font-semibold text-gray-400 w-16 pt-2.5 whitespace-nowrap" x-text="'Step #' + (index + 1)"></span>
                                        <textarea name="instructions[]" rows="2" class="flex-1 border rounded p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500" placeholder="e.g. Boil water and add onions." required></textarea>
                                        <button type="button" x-show="steps.length > 1" @click="steps.splice(index, 1)" class="text-red-500 hover:text-red-700 font-bold px-2 text-lg pt-1">
                                            &times;
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Servings & Prep Time -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Servings</label>
                                <input type="number" name="base_servings" class="w-full border rounded p-2 text-gray-900 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Prep Time (mins)</label>
                                <input type="number" name="prep_time_minutes" class="w-full border rounded p-2 text-gray-900 focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Recipe Image</label>
                            <input type="file" name="image" class="w-full text-sm">
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="document.getElementById('add-recipe-modal').style.display = 'none'" class="px-4 py-2 text-gray-600 font-semibold hover:bg-gray-100 rounded">Cancel</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- VIEW / EDIT / DELETE DETAILS MODAL -->
            <div id="view-recipe-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl relative">
                    
                    <!-- Header Image -->
                    <div class="relative">
                        <img id="view-image" class="w-full h-64 object-cover rounded-t-lg">
                        <button onclick="document.getElementById('view-recipe-modal').style.display = 'none'" class="absolute top-4 right-4 bg-black bg-opacity-50 hover:bg-opacity-80 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">✕</button>
                    </div>

                    <!-- READ-ONLY MODE -->
                    <div id="modal-view-mode" class="p-8">
                        <h2 id="view-name" class="text-3xl font-bold text-gray-900"></h2>
                        
                        <div class="flex items-center gap-4 mt-2 text-gray-600">
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm font-medium">Servings: <span id="view-servings"></span></span>
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm font-medium">Prep: <span id="view-time"></span> mins</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <h3 class="font-bold text-lg border-b pb-2 text-gray-800">Ingredients</h3>
                                <p id="view-ingredients" class="mt-4 text-gray-700 whitespace-pre-line"></p>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg border-b pb-2 text-gray-800">Instructions</h3>
                                <p id="view-instructions" class="mt-4 text-gray-700 whitespace-pre-line"></p>
                            </div>
                        </div>

                        <div class="mt-8 pt-4 border-t flex justify-between gap-4">
                            <!-- Delete Button -->
                            <form id="delete-recipe-form" method="POST" onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">
                                    Delete Recipe
                                </button>
                            </form>

                            <div class="flex gap-2">
                                <button onclick="toggleEditMode(true)" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2 rounded-lg font-semibold">
                                    Edit Details
                                </button>
                                <button onclick="document.getElementById('view-recipe-modal').style.display = 'none'" class="bg-gray-800 text-white px-5 py-2 rounded-lg hover:bg-black font-semibold">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- EDIT MODE (Shows upon clicking "Edit Details") -->
                    <div id="modal-edit-mode" class="p-8 hidden">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-2">Edit Recipe Details</h2>
                        
                        <form id="edit-recipe-form" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Recipe Name</label>
                                <input type="text" id="edit-name" name="name" class="w-full border rounded p-2 text-gray-900 focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <!-- Edit Multiple Categories Checkboxes -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Category (Select one or more)</label>
                                <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded border">
                                    <label class="inline-flex items-center text-gray-700 text-sm">
                                        <input type="checkbox" name="category[]" value="Breakfast" class="edit-category-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                                        Breakfast
                                    </label>
                                    <label class="inline-flex items-center text-gray-700 text-sm">
                                        <input type="checkbox" name="category[]" value="Lunch" class="edit-category-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                                        Lunch
                                    </label>
                                    <label class="inline-flex items-center text-gray-700 text-sm">
                                        <input type="checkbox" name="category[]" value="Dinner" class="edit-category-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                                        Dinner
                                    </label>
                                    <label class="inline-flex items-center text-gray-700 text-sm">
                                        <input type="checkbox" name="category[]" value="Desserts" class="edit-category-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                                        Desserts
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Servings</label>
                                    <input type="number" id="edit-servings" name="base_servings" class="w-full border rounded p-2 text-gray-900 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Prep Time (mins)</label>
                                    <input type="number" id="edit-time" name="prep_time_minutes" class="w-full border rounded p-2 text-gray-900 focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Ingredients</label>
                                <textarea id="edit-ingredients" name="ingredients" class="w-full border rounded p-2 text-gray-900 focus:ring-2 focus:ring-blue-500" rows="4"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Instructions</label>
                                <textarea id="edit-instructions" name="instructions" class="w-full border rounded p-2 text-gray-900 focus:ring-2 focus:ring-blue-500" rows="4"></textarea>
                            </div>

                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Change Image (Optional)</label>
                                <input type="file" name="image" class="w-full">
                            </div>

                            <div class="flex justify-end gap-2 border-t pt-4">
                                <button type="button" onclick="toggleEditMode(false)" class="px-4 py-2 text-gray-600 font-semibold hover:bg-gray-100 rounded">
                                    Back to View
                                </button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            
            <!-- Recipe List Card Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                @forelse ($recipes as $r)
                    <!-- Added smooth transition, scale, custom border, and shadow hover effects -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 hover:border-blue-500 overflow-hidden relative border transition-all duration-300 ease-in-out cursor-pointer group" @click="openViewModal({{ json_encode($r) }})">
                        @if($r->image_path)
                            <!-- Image slightly zooms on hover using group-hover -->
                            <div class="overflow-hidden h-48 w-full">
                                <img src="{{ asset('storage/' . $r->image_path) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300 ease-in-out">
                            </div>
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                        @endif
                        <div class="p-6">
                            <!-- Category tags displayed on the card -->
                            @if($r->category)
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach(explode(', ', $r->category) as $cat)
                                        <span class="text-[10px] uppercase font-bold tracking-wider bg-blue-50 text-blue-600 px-2 py-0.5 rounded">
                                            {{ $cat }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">{{ $r->name }}</h3>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    {{ $r->prep_time_minutes ? $r->prep_time_minutes . ' mins' : 'No time set' }}
                                </span>
                                <span class="text-blue-600 font-semibold text-sm group-hover:translate-x-1 transition-transform duration-200 inline-block">
                                    View Details →
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500 text-lg">No recipes found. Click "+ Add Recipe" to get started!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Event Handler Scripts -->
    <script>
        let currentRecipe = null;

        function openViewModal(recipe) {
            currentRecipe = recipe;
            toggleEditMode(false); // Default back to viewing details

            // Populating visual read-only elements
            document.getElementById('view-name').innerText = recipe.name || 'Untitled Recipe';
            document.getElementById('view-servings').innerText = recipe.base_servings || 'N/A';
            document.getElementById('view-time').innerText = recipe.prep_time_minutes || '0';
            document.getElementById('view-ingredients').innerText = recipe.ingredients || 'No ingredients listed.';
            document.getElementById('view-instructions').innerText = recipe.instructions || 'No instructions provided.';
            
            const imgEl = document.getElementById('view-image');
            imgEl.src = recipe.image_path ? '/storage/' + recipe.image_path : '/images/default-recipe.jpg';

            // Configure Edit Form fields
            document.getElementById('edit-name').value = recipe.name || '';
            document.getElementById('edit-servings').value = recipe.base_servings || '';
            document.getElementById('edit-time').value = recipe.prep_time_minutes || '';
            document.getElementById('edit-ingredients').value = recipe.ingredients || '';
            document.getElementById('edit-instructions').value = recipe.instructions || '';

            // Split category string (e.g., "Lunch, Dinner") and check matching checkboxes
            const recipeCategories = recipe.category ? recipe.category.split(', ') : [];
            const checkboxes = document.querySelectorAll('.edit-category-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = recipeCategories.includes(checkbox.value);
            });

            // Dynamically assign target routes
            document.getElementById('edit-recipe-form').action = `/recipes/${recipe.id}`;
            document.getElementById('delete-recipe-form').action = `/recipes/${recipe.id}`;

            document.getElementById('view-recipe-modal').style.display = 'flex';
        }

        function toggleEditMode(isEditing) {
            if (isEditing) {
                document.getElementById('modal-view-mode').classList.add('hidden');
                document.getElementById('modal-edit-mode').classList.remove('hidden');
            } else {
                document.getElementById('modal-view-mode').classList.remove('hidden');
                document.getElementById('modal-edit-mode').classList.add('hidden');
            }
        }

        // Handle overlay closing behaviors
        window.onclick = function(event) {
            const addModal = document.getElementById('add-recipe-modal');
            const viewModal = document.getElementById('view-recipe-modal');
            if (event.target == addModal) addModal.style.display = "none";
            if (event.target == viewModal) viewModal.style.display = "none";
        }
    </script>
</x-app-layout>