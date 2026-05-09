<footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <h3 class="text-2xl font-bold text-red-600 mb-4">Résidence Dorcas</h3>
                    <p class="text-gray-400 mb-4">Résidence Dorcas, votre hébergement de choix à Lomé, Togo. Profitez d'appartements meublés avec Wi-Fi, climatisation, cuisine suréquipée, et restaurant sur place, à 18 minutes de l'aéroport.</p>
                    <div class="flex space-x-4">
                        <a href="https://facebook.com/residencedorcas" aria-label="Page Facebook de Résidence Dorcas" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://instagram.com/residencedorcas" aria-label="Page Instagram de Résidence Dorcas" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://wa.me/22890149918" target="_blanck" aria-label="Contactez-nous sur WhatsApp" class="text-gray-400 hover:text-white">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Liens rapides</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Accueil</a></li>
                        <li><a href="{{ route('chambres.index') }}" class="text-gray-400 hover:text-white">Nos Appartements</a></li>
                        <li><a href="{{ route('services') }}" class="text-gray-400 hover:text-white">Services et commodités</a></li>
                        <li><a href="{{ route('a-propos') }}" class="text-gray-400 hover:text-white">À propos de Résidence Dorcas</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white">Contactez-nous</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-red-600 mt-1 mr-3"></i>
                            <span class="text-gray-400">65WH+RX, Lomé, Togo</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt text-red-600 mr-3"></i>
                            <a href="tel:+22890149918" class="text-gray-400 hover:text-white">+228 90 14 99 18</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-red-600 mr-3"></i>
                            <a href="mailto:info@residencedorcas.com" class="text-gray-400 hover:text-white">info@residencedorcas.com</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fab fa-whatsapp text-red-600 mr-3"></i>
                            <a href="https://wa.me/22890149918"  target="_blanck" class="text-gray-400 hover:text-white">WhatsApp</a>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Newsletter</h4>
                    <p class="text-gray-400 mb-4">Inscrivez-vous pour découvrir nos offres exclusives sur les locations à Lomé.</p>
                    <div class="flex">
                        <input type="email" placeholder="Votre email" class="px-4 py-2 w-full rounded-l-md focus:outline-none focus:ring-2 focus:ring-red-500" aria-label="Saisir votre adresse email pour la newsletter">
                        <button type="button" class="bg-red-600 text-white px-4 py-2 rounded-r-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500" aria-label="S'abonner à la newsletter">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>© <span id="current-year" aria-live="polite"></span> Résidence Dorcas. Tous droits réservés. <a href="/politique-confidentialite" class="text-gray-400 hover:text-white">Politique de confidentialité</a> | <a href="/conditions-utilisation" class="text-gray-400 hover:text-white">Conditions d'utilisation</a></p>
            </div>
        </div>
        <!-- JavaScript for dynamic year and scroll-to-top -->
        <script>
            // Set current year dynamically
            document.getElementById('current-year').textContent = new Date().getFullYear();
            // Scroll to top functionality
            const scrollToTopButton = document.getElementById('scrollToTop');
            window.onscroll = function() {
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    scrollToTopButton.classList.remove('hidden');
                } else {
                    scrollToTopButton.classList.add('hidden');
                }
            };
            scrollToTopButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        </script>
    </footer>
    <button id="scrollToTop" class="fixed bottom-4 right-4 bg-red-600 text-white p-2 rounded-full shadow-lg hidden hover:bg-red-700 transition-all" aria-label="Retour en haut de la page">
        <i class="fas fa-arrow-up"></i>
    </button>
    <!-- Added JavaScript for enhanced functionality -->
    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                mirror: false
            });

            // Scroll to top functionality
            const scrollToTopButton = document.getElementById('scrollToTop');
            
            window.onscroll = function() {
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    scrollToTopButton.classList.remove('hidden');
                } else {
                    scrollToTopButton.classList.add('hidden');
                }
            };

            scrollToTopButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });


            // // Fix for missing images
            // const emptyThumbnails = document.querySelectorAll('img[src=""]');
            // emptyThumbnails.forEach(img => {
            //     img.src = 'https://via.placeholder.com/100';
            // });

            
        });
        // Animation for the progress bar
        const progressStyle = document.createElement('style');
        progressStyle.textContent = `
            @keyframes progress {
                0% { width: 0%; }
                100% { width: 100%; }
            }
        `;
        document.head.appendChild(progressStyle);

    </script>