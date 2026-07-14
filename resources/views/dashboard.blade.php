<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Recipe Dashboard') }}
        </h2>
    </x-slot>

    <style>
        /* Base Variables */
        :root {
            --bg-primary: #f4f7f6;
            --bg-sidebar: #1e293b;
            --text-light: #ffffff;
            --text-dark: #334155;
            --accent-orange: #ff6b6b;
            --accent-green: #10b981;
            --card-bg: #ffffff;
        }

        /* Dashboard Layout */
        .dashboard-container {
            display: flex;
            min-height: calc(100vh - 65px); /* Offsets standard Laravel nav bar height */
            background-color: var(--bg-primary);
            color: var(--text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background-color: var(--bg-sidebar);
            color: var(--text-light);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .logo h2 {
            font-size: 1.5rem;
            margin-bottom: 3rem;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .nav-links a {
            color: #94a3b8;
            text-decoration: none;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-links a:hover, .nav-links a.active {
            background-color: #334155;
            color: var(--text-light);
        }

        /* Main Content Area Styling */
        .main-content {
            flex: 1;
            padding: 2.5rem;
            overflow-y: auto;
        }

        /* Header */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .welcome-text h1 {
            font-size: 2rem;
            color: #0f172a;
            font-weight: 700;
            line-height: 1.2;
        }

        .welcome-text p {
            color: #64748b;
            margin-top: 0.2rem;
        }

        .search-bar input {
            padding: 0.8rem 1.5rem;
            width: 300px;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            outline: none;
            font-size: 0.9rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            background-color: #ffffff;
        }

        /* Stats Cards Section */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background-color: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border-left: 5px solid var(--accent-orange);
        }

        .stat-card h3 {
            font-size: 0.9rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0.5rem;
            line-height: 1;
        }

        /* Recipe Grid Section */
        .recipe-section h2 {
            margin-bottom: 1.5rem;
            color: #0f172a;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .recipe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .recipe-card {
            background-color: var(--card-bg);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .recipe-card:hover {
            transform: translateY(-5px);
        }

        /* Background Gradients acting as placeholders for images */
        .card-image {
            height: 180px;
            background-size: cover;
            background-position: center;
        }

        .img-pasta { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
        .img-tacos { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); }
        .img-pancakes { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); }

        .card-content {
            padding: 1.5rem;
        }

        .tag {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 5px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }

        .tag.easy { background-color: #e6f4ea; color: var(--accent-green); }
        .tag.medium { background-color: #fff3cd; color: #856404; }

        .card-content h3 {
            font-size: 1.2rem;
            margin: 0.8rem 0 0.4rem 0;
            color: #0f172a;
            font-weight: 700;
        }

        .time {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .view-btn {
            display: inline-block;
            width: 100%;
            text-align: center;
            background-color: var(--accent-orange);
            color: white;
            text-decoration: none;
            padding: 0.7rem;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .view-btn:hover {
            background-color: #ff5252;
        }

        /* Responsive Design Adjustments */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                padding: 1.5rem;
            }
            .logo h2 {
                margin-bottom: 1.5rem;
            }
            .nav-links {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            .main-content {
                padding: 1.5rem;
            }
            .search-bar {
                width: 100%;
            }
            .search-bar input {
                width: 100%;
            }
        }
    </style>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo">
                <h2>🍳 FlavorForge</h2>
            </div>
            <nav class="nav-links">
                <a href="#" class="active">📊 Dashboard</a>
                <a href="#">📖 My Recipes</a>
                <a href="#">❤️ Favorites</a>
                <a href="#">🛒 Grocery List</a>
                <a href="#">⚙️ Settings</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <div class="welcome-text">
                    <h1>Welcome Back, Chef!</h1>
                    <p>What are we cooking today?</p>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search recipes, ingredients...">
                </div>
            </header>

            <section class="stats-grid">
                <div class="stat-card">
                    <h3>Total Recipes</h3>
                    <p class="stat-number">42</p>
                </div>
                <div class="stat-card">
                    <h3>Favorites</h3>
                    <p class="stat-number">18</p>
                </div>
                <div class="stat-card">
                    <h3>This Week's Cooks</h3>
                    <p class="stat-number">5</p>
                </div>
            </section>

            <section class="recipe-section">
                <h2>Featured Recipes</h2>
                <div class="recipe-grid">
                    <div class="recipe-card">
                        <div class="card-image img-pasta"></div>
                        <div class="card-content">
                            <span class="tag easy">Easy</span>
                            <h3>Creamy Garlic Tuscan Salmon</h3>
                            <p class="time">⏱️ 25 mins</p>
                            <a href="#" class="view-btn">View Recipe</a>
                        </div>
                    </div>

                    <div class="recipe-card">
                        <div class="card-image img-tacos"></div>
                        <div class="card-content">
                            <span class="tag medium">Medium</span>
                            <h3>Street-Style Beef Tacos</h3>
                            <p class="time">⏱️ 40 mins</p>
                            <a href="#" class="view-btn">View Recipe</a>
                        </div>
                    </div>

                    <div class="recipe-card">
                        <div class="card-image img-pancakes"></div>
                        <div class="card-content">
                            <span class="tag easy">Easy</span>
                            <h3>Fluffy Blueberry Pancakes</h3>
                            <p class="time">⏱️ 15 mins</p>
                            <a href="#" class="view-btn">View Recipe</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</x-app-layout>