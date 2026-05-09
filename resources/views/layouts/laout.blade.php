p<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title> 
    @livewireStyles
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                        Hôtel
                    </a>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        @auth
                            @if (Auth::user()->type_utilisateur === 'client')
                                <a href="{{ route('reservations.create') }}" class="border-b-2 border-transparent hover:border-blue-600 px-3 py-2 text-sm font-medium">Réserver</a>
                                <a href="{{ route('profile.show') }}" class="border-b-2 border-transparent hover:border-blue-600 px-3 py-2 text-sm font-medium">Profil</a>
                            @endif
                            @if (Auth::user()->type_utilisateur === 'admin')
                                <a href="{{ route('dashboard') }}" class="border-b-2 border-transparent hover:border-blue-600 px-3 py-2 text-sm font-medium">Tableau de bord</a>
                                <a href="{{ route('admin.users') }}" class="border-b-2 border-transparent hover:border-blue-600 px-3 py-2 text-sm font-medium">Utilisateurs</a>
                                <a href="{{ route('admin.settings') }}" class="border-b-2 border-transparent hover:border-blue-600 px-3 py-2 text-sm font-medium">Paramètres</a>
                            @endif
                        @endauth
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-700 hover:text-blue-600">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-blue-600">Connexion</a>
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 hover:text-blue-600">Inscription</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>
    @livewireScripts
</body>
</html>