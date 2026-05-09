<section class="py-12 sm:py-16 lg:py-20 bg-gray-50 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Comment ça marche
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                Un processus simple pour trouver et réserver votre hébergement idéal.
            </p>
        </div>

        <div class="mt-16">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    ['num' => 1, 'title' => 'Recherchez', 'desc' => 'Utilisez notre moteur de recherche avancé pour trouver l\'hébergement parfait.', 'img' => 'photo-1560472354-b33ff0c44a43'],
                    ['num' => 2, 'title' => 'Sélectionnez', 'desc' => 'Explorez une liste de propriétés et choisissez celle qui vous convient le mieux.', 'img' => 'photo-1556909114-f6e7ad7d3136'],
                    ['num' => 3, 'title' => 'Réservez', 'desc' => 'Réalisez votre réservation en quelques clics et profitez de votre séjour.', 'img' => 'photo-1556742049-0cfed4f6a45d'],
                    ['num' => 4, 'title' => 'Profitez', 'desc' => 'Découvrez votre nouvelle vie en famille ou en groupe, et profitez de votre séjour.', 'img' => 'photo-1551698618-1dfe5d97d256'],
                ] as $step)
                <div class="relative bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full overflow-hidden">
                        <img src="https://images.unsplash.com/{{ $step['img'] }}?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="{{ $step['title'] }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute top-2 left-2 flex items-center justify-center h-8 w-8 rounded-full bg-red-600 text-white text-sm font-bold">
                        {{ $step['num'] }}
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $step['title'] }}</h3>
                    <p class="text-base text-gray-500">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
