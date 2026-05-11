<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.3/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#f43f5e',
                            600: '#e11d48',
                            700: '#be123c',
                        }
                    }
                }
            }
        }
    </script>

    <title>{{ config('app.name', 'Résidence Dorcas') }} - Admin</title>
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
        
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(0, 0, 0, 0.05);
        }

        .nav-item-active {
            background: linear-gradient(90deg, rgba(225, 29, 72, 0.1) 0%, rgba(225, 29, 72, 0.02) 100%);
            border-right: 3px solid #e11d48;
            color: #e11d48 !important;
        }

        .main-content {
            background-color: #f8fafc;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-900 overflow-hidden" x-data="{ sidebarOpen: true }">
    
    <div class="flex h-screen overflow-hidden bg-slate-50">
        
        <!-- Sidebar -->
        <aside 
            class="glass-sidebar fixed inset-y-0 left-0 z-50 w-72 transition-all duration-300 transform lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            x-cloak
        >
            <div class="flex flex-col h-full">
                <!-- Brand -->
                <div class="flex items-center justify-center h-24 px-6 mb-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="p-2 bg-primary-600 rounded-xl shadow-lg shadow-primary-200 group-hover:scale-105 transition-transform duration-200">
                            <img src="{{ asset('assets/images/Residence Dorcas logo.jpg') }}" alt="Logo" class="h-10 w-10 rounded-lg object-cover invert brightness-200">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold tracking-tight text-slate-800">Dorcas</span>
                            <span class="text-[10px] font-semibold uppercase tracking-widest text-primary-600">Administration</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                    
                    <div class="pb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider px-4">Menu Principal</div>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-chart-line w-6 text-lg {{ request()->routeIs('admin.dashboard') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Tableau de Bord</span>
                    </a>

                    <div class="mt-6 pb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider px-4">Gestion Immobilière</div>

                    <a href="{{ route('admin.proprietes.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.proprietes.*') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-building-user w-6 text-lg {{ request()->routeIs('admin.proprietes.*') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Propriétés</span>
                    </a>

                    <a href="{{ route('admin.types-chambres.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.types-chambres.*') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-layer-group w-6 text-lg {{ request()->routeIs('admin.types-chambres.*') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Types de Chambres</span>
                    </a>

                    <a href="{{ route('admin.equipements.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.equipements.*') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-couch w-6 text-lg {{ request()->routeIs('admin.equipements.*') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Équipements</span>
                    </a>

                    <a href="{{ route('admin.chambres.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.chambres.*') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-door-open w-6 text-lg {{ request()->routeIs('admin.chambres.*') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Appartements</span>
                    </a>

                    <div class="mt-6 pb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider px-4">Opérations</div>

                    <a href="{{ route('admin.demandes-visite.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.demandes-visite.*') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-calendar-check w-6 text-lg {{ request()->routeIs('admin.demandes-visite.*') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Demandes de Visite</span>
                    </a>

                    <a href="{{ route('admin.reservations.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.reservations.*') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-key w-6 text-lg {{ request()->routeIs('admin.reservations.*') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Réservations</span>
                    </a>

                    <a href="{{ route('admin.services.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.services.index') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-bell-concierge w-6 text-lg {{ request()->routeIs('admin.services.index') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Services (Catalogue)</span>
                    </a>

                    <a href="{{ route('admin.services.orders') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.services.orders') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-clipboard-list w-6 text-lg {{ request()->routeIs('admin.services.orders') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Commandes Services</span>
                    </a>

                    <div class="mt-6 pb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider px-4">Autres</div>

                    <a href="{{ route('admin.users') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.users') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-users w-6 text-lg {{ request()->routeIs('admin.users') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Clients & Staff</span>
                    </a>

                    <a href="{{ route('admin.vehicules.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.vehicules.index') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-car w-6 text-lg {{ request()->routeIs('admin.vehicules.index') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Gestion de la Flotte</span>
                    </a>

                    <a href="{{ route('admin.vehicules.rentals') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.vehicules.rentals') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-key w-6 text-lg {{ request()->routeIs('admin.vehicules.rentals') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Locations de Véhicules</span>
                    </a>

                    <a href="{{ route('admin.finance.paiements.pending') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.finance.paiements.pending') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-clock-rotate-left w-6 text-lg {{ request()->routeIs('admin.finance.paiements.pending') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Paiements à Valider</span>
                    </a>

                    <a href="{{ route('admin.finance.encaissement.create') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.finance.encaissement.create') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-cash-register w-6 text-lg {{ request()->routeIs('admin.finance.encaissement.create') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Point d'Encaissement</span>
                    </a>

                    <a href="{{ route('admin.finance.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.finance.index') ? 'nav-item-active' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-sack-dollar w-6 text-lg {{ request()->routeIs('admin.finance.index') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span class="ml-3">Comptabilité</span>
                    </a>
                </nav>

                <!-- Footer User -->
                <div class="p-4 border-t border-slate-100">
                    <div class="flex items-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <img class="h-10 w-10 rounded-lg object-cover ring-2 ring-white shadow-sm" src="{{ Auth::user()->profile_photo_url }}" alt="">
                        <div class="ml-3 overflow-hidden">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Header -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 z-40 sticky top-0">
                <div class="px-6 h-20 flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2.5 rounded-xl bg-slate-100 text-slate-600 hover:text-primary-600 hover:bg-primary-50 transition-all duration-200 lg:hidden">
                            <i class="fa-solid fa-bars-staggered text-xl"></i>
                        </button>
                        <h2 class="ml-4 text-xl font-bold text-slate-800 hidden sm:block">
                            @yield('title', 'Tableau de Bord')
                        </h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="hidden md:flex relative group">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500"></i>
                            <input type="text" placeholder="Rechercher..." class="pl-11 pr-4 py-2.5 w-64 bg-slate-100 border-transparent rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white focus:border-transparent transition-all duration-200">
                        </div>

                        <!-- Notifications -->
                        <button class="relative p-2.5 rounded-xl bg-slate-100 text-slate-500 hover:text-primary-600 hover:bg-primary-50 transition-all duration-200">
                            <i class="fa-regular fa-bell text-xl"></i>
                            <span class="absolute top-2.5 right-2.5 block h-2.5 w-2.5 rounded-full bg-primary-500 ring-2 ring-white"></span>
                        </button>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2.5 rounded-xl bg-slate-100 text-slate-500 hover:text-primary-600 hover:bg-primary-50 transition-all duration-200" title="Déconnexion">
                                <i class="fa-solid fa-power-off text-xl"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto main-content p-6 sm:p-8">
                <div class="max-w-[1600px] mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    <x-toasts />
    <x-alerts />
    
    <script>
        AOS.init({
            duration: 800,
            once: true,
        });
    </script>
</body>
</html>