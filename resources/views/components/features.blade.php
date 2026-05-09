<section class="py-16 lg:py-24 bg-white px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="lg:text-center mb-16">
            <h2 class="text-base text-red-600 font-semibold tracking-wide uppercase">L'excellence Dorcas</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Pourquoi choisir notre Résidence ?
            </p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                Plus qu'un simple séjour, nous vous offrons un environnement conçu pour la productivité et le repos.
            </p>
        </div>

        <div class="mt-10">
            <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                @foreach([
                    ['t' => 'Confort de Classe Mondiale', 'd' => 'Des lits queen-size avec sur-matelas pour des nuits vraiment réparatrices.', 'i' => 'M5 3v18M19 3v18M5 10.5h14M5 8h14M5 13h14'],
                    ['t' => 'Fibre Optique Dédiée', 'd' => 'Vidéoconférence sans interruption avec une connexion internet stable et rapide.', 'i' => 'M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0'],
                    ['t' => 'Sécurité Renforcée 24h/7', 'd' => 'Gardiennage physique et système de vidéosurveillance pour votre tranquillité d\'esprit.', 'i' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['t' => 'Emplacement Premium', 'd' => 'Au cœur du quartier administratif, à deux pas des décideurs et des institutions.', 'i' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                ] as $f)
                <div class="relative">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-red-600 text-white shadow-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['i'] }}" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-bold text-gray-900">{{ $f['t'] }}</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        {{ $f['d'] }}
                    </dd>
                </div>
                @endforeach
            </dl>
        </div>
    </div>
</section>
