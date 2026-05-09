<section class="py-12 sm:py-16 lg:py-20 bg-red-700 px-4 sm:px-6 lg:px-8 text-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">
                Ce que disent nos clients
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-red-100">
                Découvrez les témoignages de nos clients satisfaits.
            </p>
        </div>

        <div class="mt-16 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach([
                ['name' => 'Sophie Dupont', 'stay' => 'Séjour de 2 semaines', 'text' => "J'ai passé un séjour incroyable. Tout était parfait, de la propreté à l'emplacement. Équipe très réactive.", 'img' => 'photo-1438761681033-6461ffad8d80'],
                ['name' => 'Marc Lefèvre', 'stay' => 'Location mensuelle', 'text' => "En tant que professionnel, j'ai apprécié la flexibilité et la qualité. Parfaitement équipé et bien situé.", 'img' => 'photo-1544005313-94ddf0286df2'],
                ['name' => 'Aminata Diallo', 'stay' => 'Séjour familial', 'text' => "Villa magnifique. Les enfants ont adoré la piscine et nous avons apprécié le confort. À renouveler !", 'img' => 'photo-1554151228-14d9def656e4']
            ] as $testimonial)
            <div class="bg-white bg-opacity-10 backdrop-blur-sm p-6 rounded-xl testimonial-card">
                <div class="flex items-center mb-4">
                    <img class="h-12 w-12 rounded-full object-cover" src="https://images.unsplash.com/{{ $testimonial['img'] }}?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="{{ $testimonial['name'] }}">
                    <div class="ml-4">
                        <h4 class="font-semibold">{{ $testimonial['name'] }}</h4>
                        <p class="text-red-200 text-sm">{{ $testimonial['stay'] }}</p>
                    </div>
                </div>
                <div class="mb-4 flex text-yellow-400">
                    @for($i=0; $i<5; $i++) <i class="fas fa-star"></i> @endfor
                </div>
                <p class="text-red-100 italic">"{{ $testimonial['text'] }}"</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
