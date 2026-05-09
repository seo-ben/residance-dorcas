<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.3/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.9/cdn.js" defer integrity="sha512-Qg4yHOPXaMOpvyQ8hk5ZVYUIXGE/0hxftn0lecaz04ohvI0ytM7AXpSzK1sfcYk79B1WexR3nG37Q/JboHLB2Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    
    <!-- Meta tags SEO -->
    <title>{{ config('app.name', 'Résidence Dorcas') }} - Locations d'Appartements Meublés à Lomé</title>
    <meta name="description" content="Résidence Dorcas : Votre partenaire immobilier à Lomé. Locations d'appartements et studios meublés de haut standing, avec Wi-Fi, climatisation et sécurité 24h/24.">
    <meta name="keywords" content="Résidence Dorcas, location appartement Lomé, immobilier Togo, studio meublé Lomé, location courte durée, location longue durée Togo">
    <meta name="author" content="Résidence Dorcas">
    <meta name="robots" content="index, follow">
    
    <!-- Meta tags Open Graph pour les réseaux sociaux -->
    <meta property="og:title" content="Résidence Dorcas - Appartements meublés à Lomé, Togo">
    <meta property="og:description" content="Découvrez la Résidence Dorcas à Lomé, Togo : appartements meublés avec Wi-Fi, climatisation, cuisine suréquipée, restaurant sur place, et navette aéroport.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://residencedorcas.com">
    <meta property="og:site_name" content="Résidence Dorcas">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:image" content="/assets/images/dorcash/og-image.jpg">
    
    <!-- Meta tags Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ config('app.name', 'Résidence Douceur') }}">
    <meta name="twitter:description" content="Résidence Douceur - Votre havre de paix et de bien-être dans un cadre chaleureux et accueillant.">
    
    <!-- Favicon et icônes -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/dorcash/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/dorcash/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/dorcash/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/dorcash/apple-touch-icon.png') }}">
    <link rel="android-chrome-icon" sizes="192x192" href="{{ asset('assets/images/dorcash/android-chrome-192x192.png') }}">
    <link rel="android-chrome-icon" sizes="512x512" href="{{ asset('assets/images/dorcash/android-chrome-512x512.png') }}">
    
    <!-- Manifest pour PWA -->
    {{-- <link rel="manifest" href="{{ asset('manifest.json') }}"> --}}
    
    <!-- Thème couleur pour les navigateurs mobiles (rouge du logo) -->
    <meta name="theme-color" content="#E53E3E">
    <meta name="msapplication-TileColor" content="#E53E3E">
    <meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}">
    

    <style>
       /* Base Styles */
        html {
            scroll-behavior: smooth;
        }

        /* Hero Section */
        .hero-image {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                            url('assets/images/exterieur de la résidence dorcas.png');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            position: relative;
        }

        /* User Avatar */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Dropdown Menu */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            min-width: 240px;
            padding: 8px 0;
            margin-top: 8px;
            z-index: 50;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu::before {
            content: '';
            position: absolute;
            top: -6px;
            right: 16px;
            width: 12px;
            height: 12px;
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-bottom: none;
            border-right: none;
            transform: rotate(45deg);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 14px;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .dropdown-item:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 12px;
            color: #6b7280;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 8px 0;
        }

        .user-info {
            padding: 16px 20px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .user-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 15px;
            margin-bottom: 2px;
        }

        .user-email {
            font-size: 13px;
            color: #6b7280;
        }

        .user-role {
            display: inline-block;
            background: #fee2e2;
            color: #991b1b;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 12px;
            margin-top: 4px;
            font-weight: 500;
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border: 2px solid white;
        }

        /* Shape Divider */
        .custom-shape-divider-bottom-1619712660 {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
        }

        .custom-shape-divider-bottom-1619712660 svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 150px;
        }

        .custom-shape-divider-bottom-1619712660 .shape-fill {
            fill: #FFFFFF;
        }

        /* Search Form */
        .search-form {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Image Gallery */
        .image-gallery {
            transition: all 0.3s ease;
            overflow: hidden;
            border-radius: 8px;
        }

        .image-gallery:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Testimonial Cards */
        .testimonial-card {
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 0 auto;
            padding: 2rem;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .show-for-demo {
            display: flex !important;
        }

        /* Loading Animation */
        .loading {
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top: 3px solid #dc2626;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .hero-image {
                min-height: 90vh;
            }
            
            .custom-shape-divider-bottom-1619712660 svg {
                height: 120px;
            }
        }

        @media (max-width: 768px) {
            .hero-image {
                min-height: 85vh;
            }
            
            .custom-shape-divider-bottom-1619712660 svg {
                height: 100px;
            }
            
            .modal-content {
                width: 95%;
                padding: 1.5rem;
            }

            .dropdown-menu {
                min-width: 200px;
                right: -20px;
            }
        }

        @media (max-width: 640px) {
            .hero-image {
                min-height: 80vh;
            }
            
            .custom-shape-divider-bottom-1619712660 svg {
                height: 80px;
            }
            
            .modal-content {
                width: 100%;
                padding: 1rem;
                margin: 0 1rem;
            }

            .dropdown-menu {
                right: -40px;
            }
        }

    </style>
    <title>{{ config('app.name', '') }} @yield('title')</title>
    @livewireStyles

     <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ApartmentComplex",
        "name": "Résidence Dorcas",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "65WH+RX",
            "addressLocality": "Lomé",
            "addressCountry": "TG"
        },
        "telephone": "+22890149918",
        "email": "info@residencedorcas.com",
        "url": "https://residencedorcas.com",
        "description": "Résidence Dorcas offre des appartements meublés à Lomé, Togo, avec Wi-Fi, climatisation, cuisine suréquipée, et restaurant sur place, à 18 minutes de l’aéroport.",
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "5.0",
            "reviewCount": "1"
        }
    }
    </script>
    
</head>
<body class="font-sans antialiased text-gray-800 flex flex-col min-h-screen">

    <!-- Barre de navigation principale avec position fixe et ombre -->
    <nav class="bg-white shadow-md fixed top-0 left-0 w-full z-50" x-data="{ open: false, userDropdown: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Section logo et nom du site -->
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <img src="{{ asset('assets/images/Residence Dorcas logo.jpg') }}" 
                                alt="Residence Dorcas Logo" 
                                class="h-10 w-10 sm:h-12 sm:w-12 rounded-full object-cover">
                            <span class="text-xl sm:text-2xl font-bold text-red-600  sm:inline">Residence Dorcas</span>
                        </a>
                    </div>
                    <!-- Menu de navigation pour les écrans moyens et grands -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <!-- Lien Accueil avec surbrillance conditionnelle -->
                        <a href="{{ route('home') }}" 
                        class="{{ request()->routeIs('home') ? 'border-red-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Accueil
                        </a>
                        <!-- Lien Appartements avec surbrillance conditionnelle -->
                        <a href="{{ route('chambres.index') }}" 
                        class="{{ request()->routeIs('chambres.index') ? 'border-red-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Appartements
                        </a>
                        <!-- Lien Services -->
                        <a href="{{ route('services') }}" 
                        class="{{ request()->routeIs('services') ? 'border-red-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Services
                        </a>
                        <!-- Lien Location de Voiture -->
                        <a href="{{ route('vehicules.index') }}" 
                        class="{{ request()->routeIs('vehicules.*') ? 'border-red-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Location de Voiture
                        </a>
                        <!-- Lien À propos -->
                        <a href="{{ route('a-propos') }}" 
                        class="{{ request()->routeIs('a-propos') ? 'border-red-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            À propos
                        </a>
                        <!-- Lien Contact -->
                        <a href="{{ route('contact') }}" 
                        class="{{ request()->routeIs('contact') ? 'border-red-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Contact
                        </a>
                    </div>
                </div>
                
                <!-- Section des actions utilisateur (notifications et menu déroulant) pour écrans moyens et grands -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                    @auth
                        <!-- Notifications (non fonctionnel, en attente d'implémentation) -->
                        <div class="relative">
                            <button class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 rounded-full transition duration-150 ease-in-out">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="notification-badge">3</span>
                                <!-- TODO: Implémenter la logique des notifications -->
                            </button>
                        </div>

                        <!-- Menu déroulant utilisateur -->
                        <div class="relative" x-data="{ userDropdown: false }">
                            <button @click="userDropdown = !userDropdown" 
                                    @click.away="userDropdown = false"
                                    class="user-avatar focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                @if(Auth::user())
                                    {{-- <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="fas fa-user w-full h-full rounded-full object-cover">
                                @else --}}
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                                @endif
                            </button>

                            <!-- Menu déroulant avec animation Alpine.js -->
                            <div x-show="userDropdown" 
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-1 transform scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-1 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="dropdown-menu show"
                                style="display: none;"
                                x-show.transition.opacity.duration.300ms="userDropdown">
                                <!-- Informations utilisateur -->
                                <div class="user-info">
                                    <div class="user-name text-center">{{ Auth::user()->name }} {{ Auth::user()->prenom }}</div>
                                    <div class="user-email">{{ Auth::user()->email }}</div>
                                    <span class="user-role">
                                        @if(Auth::user()->type_utilisateur === 'admin')
                                            Administrateur
                                        @else
                                            Welcome
                                        @endif
                                    </span>
                                </div>

                                <!-- Menu items -->
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    Mon Profil
                                    <!-- TODO: Implémenter la route pour la page Mon Profil -->
                                </a>

                                @if(Auth::user()->type_utilisateur === 'client')
                                    <a href="{{ route('reservations.index') }}" class="dropdown-item">
                                        <i class="fas fa-calendar-check"></i>
                                        Mes Réservations
                                    </a>
                                    <a href="{{ route('client.visites.index') }}" class="dropdown-item">
                                        <i class="fas fa-eye"></i>
                                        Mes Visites
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <i class="fas fa-heart"></i>
                                        Mes Favoris
                                        <!-- TODO: Implémenter la route pour la page Mes Favoris -->
                                    </a>
                                @endif

                                @if(Auth::user()->type_utilisateur === 'proprietaire')
                                    <a href="#" class="dropdown-item">
                                        <i class="fas fa-home"></i>
                                        Mes Propriétés
                                        <!-- TODO: Implémenter la route pour la page Mes Propriétés -->
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <i class="fas fa-chart-line"></i>
                                        Statistiques
                                        <!-- TODO: Implémenter la route pour la page Statistiques -->
                                    </a>
                                @endif

                                @if(Auth::user()->type_utilisateur === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                        <i class="fas fa-tachometer-alt"></i>
                                        Tableau de Bord
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <i class="fas fa-users"></i>
                                        Gestion Utilisateurs
                                        <!-- TODO: Implémenter la route pour la page Gestion Utilisateurs -->
                                    </a>
                                @endif

                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-question-circle"></i>
                                    Aide & Support
                                    <!-- TODO: Implémenter la route pour la page Aide & Support -->
                                </a>

                                <div class="dropdown-divider"></div>

                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Déconnexion
                                    </a>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Boutons de connexion/inscription pour utilisateurs non connectés -->
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-red-600 mr-4">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="bg-red-600 px-4 py-2 rounded-md text-white font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                            Inscription
                        </a>
                    @endauth
                </div>

                <!-- Bouton menu mobile pour écrans petits -->
                <div class="-mr-2 flex items-center sm:hidden gap-3">
                    @auth
                        <div class="user-avatar shadow-sm" style="width: 32px; height: 32px; font-size: 12px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->prenom ?? Auth::user()->name, 0, 1)) }}
                        </div>
                    @endauth
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-900 hover:text-red-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red-500 transition">
                        <span class="sr-only">Ouvrir le menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu mobile -->
        <div x-show="open" class="sm:hidden bg-white shadow-lg">
            <div class="pt-2 pb-3 space-y-1">
                <!-- Lien Accueil avec surbrillance conditionnelle -->
                <a href="{{ route('home') }}" 
                class="{{ request()->routeIs('home') ? 'bg-red-50 border-red-500 text-red-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Accueil
                </a>
                <!-- Lien appartement avec surbrillance conditionnelle -->
                <a href="{{ route('chambres.index') }}" 
                class="{{ request()->routeIs('chambres.index') ? 'bg-red-50 border-red-500 text-red-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Appartements
                </a>
                <!-- Lien Propriétés avec surbrillance conditionnelle -->
                <a href="{{ route('chambres.proprietes.index') }}" 
                class="{{ request()->routeIs('chambres.proprietes.*') ? 'bg-red-50 border-red-500 text-red-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Propriétés
                </a>
                <!-- Lien Services -->
                <a href="{{ route('services') }}" 
                class="{{ request()->routeIs('services') ? 'bg-red-50 border-red-500 text-red-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Services
                </a>
                <!-- Lien Location de Voiture -->
                <a href="{{ route('vehicules.index') }}" 
                class="{{ request()->routeIs('vehicules.*') ? 'bg-red-50 border-red-500 text-red-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Location de Voiture
                </a>
                <!-- Lien À propos -->
                <a href="{{ route('a-propos') }}" 
                class="{{ request()->routeIs('a-propos') ? 'bg-red-50 border-red-500 text-red-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    À propos
                </a>
                <!-- Lien Contact -->
                <a href="{{  route('contact') }}" 
                class="{{ request()->routeIs('contact') ? 'bg-red-50 border-red-500 text-red-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Contact
                </a>
                
                @auth
                    <!-- Menu mobile pour utilisateur connecté -->
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <div class="flex items-center px-4 mb-3">
                            <div class="user-avatar mr-3" style="width: 32px; height: 32px; font-size: 12px;">
                                @if(Auth::user())
                                    {{-- <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="w-full h-full rounded-full object-cover">
                                @else --}}
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-800">{{ Auth::user()->prenom }} {{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                        </div>

                        <!-- Menu items pour utilisateur connecté -->
                        <a href="#" 
                        class="{{ request()->routeIs('profile') ? 'bg-red-50 border-red-500 text-red-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-red-500 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            <i class="fas fa-user mr-2"></i> Mon Profil
                            <!-- TODO: Implémenter la route pour la page Mon Profil -->
                        </a>

                        @if(Auth::user()->type_utilisateur === 'client')
                            <a href="{{ route('reservations.index') }}" 
                            class="{{ request()->routeIs('reservations.index') ? 'bg-red-50 border-red-500 text-red-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-red-500 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                                <i class="fas fa-calendar-check mr-2"></i> Mes Réservations
                            </a>
                            <a href="{{ route('client.visites.index') }}" 
                            class="{{ request()->routeIs('client.visites.*') ? 'bg-red-50 border-red-500 text-red-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-red-500 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                                <i class="fas fa-eye mr-2"></i> Mes Visites
                            </a>
                        @endif

                        @if(Auth::user()->type_utilisateur === 'admin')
                            <a href="{{ route('admin.dashboard') }}" 
                            class="{{ request()->routeIs('admin.dashboard') ? 'bg-red-50 border-red-500 text-red-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-red-500 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                                <i class="fas fa-tachometer-alt mr-2"></i> Tableau de Bord
                            </a>
                        @endif

                        <a href="#" 
                        class="{{ request()->routeIs('settings') ? 'bg-red-50 border-red-500 text-red-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-red-500 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            <i class="fas fa-cog mr-2"></i> Paramètres
                            <!-- TODO: Implémenter la route pour la page Paramètres -->
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" 
                                    class="w-full text-left border-transparent text-red-600 hover:bg-red-50 hover:border-red-500 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                                <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Menu mobile pour utilisateur non connecté -->
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <x-responsive-nav-link href="{{-- route('settings.index') --}}"
                    class="px-4 py-2 text-base font-medium hover:bg-red-50 hover:text-red-600">
                    {{ __('Paramètres') }}
                </x-responsive-nav-link>
                        <a href="{{ route('register') }}" class="block w-full bg-red-600 px-4 py-2 rounded-md text-white font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out text-center">
                            Inscription
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav> 
    <main class="flex-grow flex flex-col pt-16">
        @yield('content')
    </main>

    @include('layouts.footer')
    
    @livewireScripts
    <x-toasts />
    <x-alerts />
    @stack('scripts')
</body>
</html>