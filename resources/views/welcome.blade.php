<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DishLen - Recipe Management System</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50 text-gray-800" x-data="{ activeCategory: 'All' }">
        
        <!-- Navigation Header -->
        <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">

                <div class="flex items-center gap-2">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-1.2 0-2.4.6-3 1.7a3 3 0 00-3.6 4.1 3.5 3.5 0 00.6 6.7V17c0 1.1.9 2 2 2h8a2 2 0 002-2v-1.5a3.5 3.5 0 00.6-6.7 3 3 0 00-3.6-4.1A3.5 3.5 0 0012 3zM7.5 12h9M9.5 15.5h5"></path>
                        </svg>
                        <span class="text-xl font-extrabold text-gray-900 tracking-tight">Dish<span class="text-blue-500">Len</span></span>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition">Go to Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="relative bg-white overflow-hidden py-20 border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center gap-12">
                <div class="flex-1 space-y-6">
                    <span class="bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full">Explore your culinary creativity</span>
                    <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        Your Personal Digital <br><span class="text-blue-600">Recipe Vault</span>
                    </h1>
                    <p class="text-lg text-gray-600 max-w-lg">
                        Create, organize, and view your favorite cooking recipes in one single seamless interface. Start managing your personal cookbook today.
                    </p>
                    <div class="pt-2 flex gap-4">
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition">Get Started Free</a>
                        <a href="#recipe-section" class="border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-xl transition">Explore Menu</a>
                    </div>
                </div>
                <div class="flex-1 w-full max-w-md md:max-w-none">
                    <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=1000&q=80" alt="Cooking Landing" class="rounded-2xl shadow-2xl w-full h-96 object-cover object-center">
                </div>
            </div>
        </header>

        <!-- Recipe Menu Grid Section -->
        <section id="recipe-section" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900">Explore Recipes</h2>
                <p class="text-gray-500 max-w-md mx-auto">Browse through our collection of delicious dishes categorized to suit your culinary craving.</p>
                
                <!-- Category Filter Buttons -->
                <div class="flex flex-wrap justify-center gap-2 pt-4">
                    <template x-for="category in ['All', 'Breakfast', 'Lunch', 'Dinner', 'Desserts']">
                        <button 
                            @click="activeCategory = category"
                            :class="activeCategory === category 
                                ? 'bg-blue-600 text-white shadow-md' 
                                : 'bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-900 border'"
                            class="px-5 py-2 rounded-full font-bold text-sm transition duration-200"
                            x-text="category">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Recipe List -->
            @php
                // Fetch recipes directly to display them on the welcome page
                $landingRecipes = \App\Models\Recipe::latest()->get();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse ($landingRecipes as $r)
                    @php
                        // Convert the comma-separated categories to a Javascript-readable array format
                        $categoriesArray = $r->category ? explode(', ', $r->category) : [];
                        $categoriesJson = json_encode($categoriesArray);
                    @endphp
                    
                    <!-- Card Filtered dynamic showing using Alpine -->
                    <div 
                        x-show="activeCategory === 'All' || {{ $categoriesJson }}.includes(activeCategory)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 hover:border-blue-400 overflow-hidden transition-all duration-300 flex flex-col group cursor-pointer"
                        onclick="window.location.href='{{ route('login') }}'">
                        
                        <!-- Recipe Thumbnail Image -->
                        <div class="h-56 overflow-hidden relative">
                            @if($r->image_path)
                                <img src="{{ asset('storage/' . $r->image_path) }}" alt="{{ $r->name }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500 ease-in-out">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">No Image provided</div>
                            @endif
                        </div>

                        <!-- Card Body Details -->
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <!-- Display Categories -->
                                @if($r->category)
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        @foreach($categoriesArray as $cat)
                                            <span class="text-[10px] uppercase font-extrabold tracking-wider bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md">
                                                {{ $cat }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">{{ $r->name }}</h3>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-50 flex items-center justify-between text-gray-500 text-sm">
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $r->prep_time_minutes ? $r->prep_time_minutes . ' mins' : 'N/A' }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        {{ $r->base_servings ? $r->base_servings . ' servings' : 'N/A' }}
                                    </span>
                                </div>
                                <span class="text-blue-600 font-bold group-hover:translate-x-1 transition-transform duration-200">View Details &rarr;</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white rounded-2xl border">
                        <p class="text-gray-400 text-lg">No recipes found. Register or log in to add your first recipe!</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white border-t mt-20 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} DishLen. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>