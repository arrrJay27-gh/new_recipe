<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Recipe Dashboard') }}
            </h2>
            <!-- Triggers the modal using plain JavaScript in case Alpine's scope is blocked -->
            <button onclick="document.getElementById('add-recipe-modal').style.display = 'flex'" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-bold z-50">
                + Add Recipe
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- ADD MODAL (Targeted by ID and handled by simple JS fallback) -->
            <div id="add-recipe-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                <div class="bg-white p-6 rounded-lg w-full max-w-lg shadow-xl">
                    <h2 class="text-xl font-bold mb-4 text-gray-900">Add New Recipe</h2>
                    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                            <input type="text" name="name" class="w-full border rounded p-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                            <select name="category" class="w-full border rounded p-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="breakfast">Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="dinner">Dinner</option>
                                <option value="desserts">Desserts</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Instructions</label>
                            <textarea name="instructions" class="w-full border rounded p-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Servings</label>
                                <input type="number" name="base_servings" class="w-full border rounded p-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Prep Time (mins)</label>
                                <input type="number" name="prep_time_minutes" class="w-full border rounded p-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Recipe Image</label>
                            <input type="file" name="image" class="w-full">
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="document.getElementById('add-recipe-modal').style.display = 'none'" class="px-4 py-2 text-gray-600 font-semibold hover:bg-gray-100 rounded">Cancel</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- VIEW MODAL -->
            <div id="view-recipe-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                    <img id="view-image" class="w-full h-64 object-cover rounded-t-lg hidden">
                    
                    <div class="p-8">
                        <h2 id="view-name" class="text-3xl font-bold text-gray-900"></h2>
                        <div class="flex items-center gap-4 mt-2 text-gray-600">
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm">Servings: <span id="view-servings"></span></span>
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm">Time: <span id="view-time"></span> mins</span>
                        </div>
                        
                        <h3 class="mt-6 font-bold text-lg border-b pb-2 text-gray-800">Instructions</h3>
                        <p id="view-instructions" class="mt-4 text-gray-700 whitespace-pre-line"></p>

                        <button onclick="document.getElementById('view-recipe-modal').style.display = 'none'" class="mt-8 w-full bg-gray-800 text-white py-2 rounded-lg hover:bg-black font-semibold">
                            Close
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Recipe List -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                @forelse ($recipes as $r)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden relative border">
                        @if($r->image_path)
                            <img src="{{ asset('storage/' . $r->image_path) }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900">{{ $r->name }}</h3>
                            <button onclick="openViewModal({{ json_encode($r) }})" class="mt-4 text-blue-600 font-semibold underline hover:text-blue-800">
                                View Details →
                            </button>
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

    <!-- Vanilla Javascript Handlers to completely bypass Alpine scope problems -->
    <script>
        function openViewModal(recipe) {
            document.getElementById('view-name').innerText = recipe.name || 'Untitled Recipe';
            document.getElementById('view-servings').innerText = recipe.base_servings || 'N/A';
            document.getElementById('view-time').innerText = recipe.prep_time_minutes || '0';
            document.getElementById('view-instructions').innerText = recipe.instructions || 'No instructions provided.';
            
            const imgEl = document.getElementById('view-image');
            if (recipe.image_path) {
                imgEl.src = '/storage/' + recipe.image_path;
                imgEl.classList.remove('hidden');
            } else {
                imgEl.classList.add('hidden');
            }

            document.getElementById('view-recipe-modal').style.display = 'flex';
        }

        // Close modals if user clicks outside of them
        window.onclick = function(event) {
            const addModal = document.getElementById('add-recipe-modal');
            const viewModal = document.getElementById('view-recipe-modal');
            if (event.target == addModal) {
                addModal.style.display = "none";
            }
            if (event.target == viewModal) {
                viewModal.style.display = "none";
            }
        }
    </script>
</x-app-layout>