<section class="py-12 sm:py-16 lg:py-20 bg-white" id="faq">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto divide-y-2 divide-gray-200">
            <h2 class="text-center text-3xl font-extrabold text-gray-900 sm:text-4xl">Questions Fréquentes</h2>
            <dl class="mt-6 space-y-6 divide-y divide-gray-200">
                @foreach([
                    ['q' => 'Quels sont les types de location disponibles ?', 'a' => 'Nous proposons des locations de courte durée (nuitées), moyenne durée et longue durée (bail mensuel) selon vos besoins.'],
                    ['q' => 'Comment programmer une visite pour un appartement ?', 'a' => 'Vous pouvez choisir l\'option "Programmer une visite" sur la page de l\'appartement. Des frais de visite de 5 000 FCFA sont requis pour valider votre rendez-vous.'],
                    ['q' => 'La résidence dispose-t-elle d\'un parking sécurisé ?', 'a' => 'Oui, nous disposons d\'un parking privé gratuit et sécurisé 24h/24 pour tous nos résidents.'],
                    ['q' => 'Le Wi-Fi est-il inclus ?', 'a' => 'Absolument. Nous offrons une connexion Wi-Fi haut débit par fibre optique dans l\'intégralité du bâtiment.'],
                ] as $item)
                <div class="pt-6">
                    <dt class="text-lg">
                        <span class="font-medium text-gray-900">{{ $item['q'] }}</span>
                    </dt>
                    <dd class="mt-2 text-base text-gray-500">
                        {{ $item['a'] }}
                    </dd>
                </div>
                @endforeach
            </dl>
        </div>
    </div>
</section>
