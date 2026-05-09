<section class="py-24 bg-gray-900 overflow-hidden" id="experience">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Intro --}}
        <div class="text-center mb-24 reveal-up">
            <h2 class="text-4xl md:text-4xl font-black text-white mb-8">Votre futur chez-vous à la <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-white">Résidence Dorcas</span></h2>
            <div class="w-24 h-1 bg-red-600 mx-auto rounded-full"></div>
        </div>

        <div class="space-y-32 relative">
            {{-- Floating vertical line for desktop --}}
            <div class="hidden lg:block absolute left-1/2 top-0 bottom-0 w-px bg-white/10 -translate-x-1/2"></div>

            {{-- Experience 1: Arrivée --}}
            <div class="flex flex-col lg:flex-row items-center gap-16 group">
                <div class="lg:w-1/2 reveal-left order-2 lg:order-1">
                    <div class="relative overflow-hidden rounded-2xl aspect-[4/3] shadow-2xl">
                        <img src="{{ asset('assets/images/exterieur de la résidence dorcas.jpg') }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Arrivée Dorcas">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-8 left-8">
                            <span class="text-red-600 font-bold text-5xl mb-2 block">01</span>
                            <h3 class="text-white text-2xl font-bold uppercase">Un Accueil Privilégié</h3>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2 reveal-right order-1 lg:order-2 text-center lg:text-left">
                    <p class="text-gray-400 text-lg leading-relaxed mb-6 font-medium">
                        Dès votre arrivée au cœur du quartier administratif de Lomé, vous êtes plongé dans un havre de paix. Notre équipe dédiée vous assure un check-in fluide et personnalisé, 24h/24. 
                    </p>
                    <ul class="space-y-4 text-white font-bold inline-block text-left">
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-red-600 rounded-full"></div>
                            Service de navette aéroport
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-red-600 rounded-full"></div>
                            Conciergerie attentive
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Experience 2: Confort --}}
            <div class="flex flex-col lg:flex-row items-center gap-16 group">
                <div class="lg:w-1/2 reveal-left text-center lg:text-right">
                    <p class="text-gray-400 text-lg leading-relaxed mb-6 font-medium">
                        Nos appartements et studios ont été pensés pour le confort absolu. Un design épuré, des matériaux nobles et une literie de haute qualité pour vous sentir comme chez vous.
                    </p>
                    <ul class="space-y-4 text-white font-bold inline-block text-left lg:text-right">
                        <li class="flex items-center lg:flex-row-reverse gap-3">
                            <div class="w-2 h-2 bg-red-600 rounded-full"></div>
                            Climatisation & Wi-Fi Haut Débit
                        </li>
                        <li class="flex items-center lg:flex-row-reverse gap-3">
                            <div class="w-2 h-2 bg-red-600 rounded-full"></div>
                            Espace de travail ergonomique
                        </li>
                    </ul>
                </div>
                <div class="lg:w-1/2 reveal-right">
                    <div class="relative overflow-hidden rounded-2xl aspect-[4/3] shadow-2xl">
                        <img src="{{ asset('assets/images/interieur de la residence face de dorcas.webp') }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Confort Dorcas">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-8 right-8 text-right">
                            <span class="text-red-600 font-bold text-5xl mb-2 block">02</span>
                            <h3 class="text-white text-2xl font-bold uppercase">Le Confort Absolu</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Experience 3: Cuisine/Services --}}
            <div class="flex flex-col lg:flex-row items-center gap-16 group">
                <div class="lg:w-1/2 reveal-left order-2 lg:order-1">
                    <div class="relative overflow-hidden rounded-2xl aspect-[4/3] shadow-2xl">
                        <img src="{{ asset('assets/images/cuisine de la residences dorcas.webp') }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Cuisine Dorcas">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-8 left-8">
                            <span class="text-red-600 font-bold text-5xl mb-2 block">03</span>
                            <h3 class="text-white text-2xl font-bold uppercase">Liberté Gastronomique</h3>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2 reveal-right order-1 lg:order-2 text-center lg:text-left">
                    <p class="text-gray-400 text-lg leading-relaxed mb-6 font-medium">
                        Profitez d'une cuisine entièrement équipée dans chaque appartement ou savourez les spécialités locales livrées directement chez vous. 
                    </p>
                    <ul class="space-y-4 text-white font-bold inline-block text-left">
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-red-600 rounded-full"></div>
                            Équipements électroménagers complets
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-red-600 rounded-full"></div>
                            Espace repas convivial
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .reveal-left { opacity: 0; transform: translateX(-50px); transition: all 1s ease-out; }
    .reveal-right { opacity: 0; transform: translateX(50px); transition: all 1s ease-out; }
    .reveal-visible { opacity: 1 !important; transform: translateX(0) !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1
        };

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal-left, .reveal-right, .reveal-up').forEach(el => {
            revealObserver.observe(el);
        });
    });
</script>
