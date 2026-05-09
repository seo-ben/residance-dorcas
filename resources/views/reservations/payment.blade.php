<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https://js.stripe.com; script-src 'self' 'unsafe-inline' https://js.stripe.com https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; frame-src https://js.stripe.com;">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
    <title>Paiement Sécurisé - {{ config('app.name', '') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .payment-gradient {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }
        
        .animate-slide-up {
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0; 
                transform: translateY(30px) scale(0.95); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }
        
        .card-input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(220, 38, 38, 0.2);
        }
        
        .loading-dots {
            animation: loadingDots 1.5s infinite;
        }
        
        @keyframes loadingDots {
            0%, 20% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }
        
        .pulse-ring {
            animation: pulseRing 2s infinite;
        }
        
        @keyframes pulseRing {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.3); opacity: 0; }
        }
        
        .bg-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(220, 38, 38, 0.1) 0%, transparent 25%),
                radial-gradient(circle at 75% 75%, rgba(153, 27, 27, 0.1) 0%, transparent 25%);
            background-size: 100px 100px;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .shake { animation: shake 0.5s; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-red-50 to-red-100 bg-patter">
    
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-lg animate-slide-up">
            
            <!-- Header avec Logo -->
            <div class="text-center mb-8">
                {{-- <div class="relative inline-block mb-4">
                    
                    <div class="absolute inset-0 w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl pulse-ring"></div>
                </div> --}}
                {{-- <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ config('app.name', '') }}</h1> --}}
                {{-- <p class="text-gray-600 text-sm">Paiement sécurisé SSL 256-bit</p> --}}
            </div>

            <!-- Messages d'erreur -->
            @if($errors->any() || session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 rounded-r-xl p-4 animate-slide-up">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="font-medium text-red-800 mb-1">Erreur de paiement</h3>
                        <div class="text-red-700 text-sm space-y-1">
                            @if(session('error'))
                                <p>{{ session('error') }}</p>
                            @endif
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Messages de succès -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 rounded-r-xl p-4 animate-slide-up">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-green-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <div class="ml-3">
                        <p class="font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Carte de Paiement Principale -->
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
                
                <!-- En-tête avec Montant -->
                <div class="payment-gradient px-6 py-8 text-center text-white">
                    <div class="mb-2">
                        <span class="text-sm opacity-90">Montant à payer</span>
                    </div>
                    <div class="text-4xl font-bold mb-1">
                        {{ number_format($reservation->prix_total, 0, ',', ' ') }} F CFA
                    </div>
                    {{-- <div class="text-lg opacity-90"></div> --}}
                    <div class="mt-4 text-sm opacity-75">
                        Réservation {{ $reservation->reference ?? 'RES-001' }}
                    </div>
                </div>

                <!-- Formulaire -->
                <div class="p-6">
                    <form action="{{ route('reservations.processPayment', $reservation->id) }}" method="POST" id="payment-form" class="space-y-6">
                        @csrf
                        <input type="hidden" name="methode_paiement" value="carte">
                        <input type="hidden" name="montant_type" value="total">
                      

                        <!-- Résumé compact de la réservation -->
                        <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-2xl p-4 border border-red-100">
                            <div class="flex items-center justify-between text-sm">
                                <div class="space-y-1">
                                    <div class="font-medium text-gray-900">{{ $reservation->type_appartement->nom ?? 'Séjour de ' }}</div>
                                    {{-- <div class="text-gray-600">{{ $reservation->nombre_personnes ?? '2' }} personnes</div> --}}
                                </div>
                                <div class="text-right space-y-1">
                                    <div class="font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m') }} - {{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-gray-600">
                                        {{ \Carbon\Carbon::parse($reservation->date_arrivee)->diffInDays(\Carbon\Carbon::parse($reservation->date_depart)) }} nuits
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Types de cartes acceptées -->
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-3">Cartes acceptées</p>
                            <div class="flex items-center justify-center space-x-3">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCA0MCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iIzAwNTFBNSIvPgo8dGV4dCB4PSI1IiB5PSIxNiIgZmlsbD0id2hpdGUiIGZvbnQtc2l6ZT0iMTAiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiI+VklTQTwvdGV4dD4KPC9zdmc+" alt="Visa" class="h-6 rounded shadow-sm">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCA0MCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iI0VCMDAxQiIvPgo8Y2lyY2xlIGN4PSIxNSIgY3k9IjEyIiByPSI3IiBmaWxsPSIjRkY1RjAwIiBmaWxsLW9wYWNpdHk9IjAuNyIvPgo8Y2lyY2xlIGN4PSIyNSIgY3k9IjEyIiByPSI3IiBmaWxsPSIjRkY1RjAwIiBmaWxsLW9wYWNpdHk9IjAuNyIvPgo8L3N2Zz4=" alt="Mastercard" class="h-6 rounded shadow-sm">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCA0MCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iIzAwNzlDMSIvPgo8dGV4dCB4PSI0IiB5PSIxNiIgZmlsbD0id2hpdGUiIGZvbnQtc2l6ZT0iOCIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIj5BTUVYPC90ZXh0Pgo8L3N2Zz4=" alt="Amex" class="h-6 rounded shadow-sm">
                            </div>
                        </div>

                        <!-- Informations de la carte -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom sur la carte</label>
                                <input type="text" name="nom_carte" class="card-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="PAUL GOUR" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Informations de carte</label>
                                <div id="card-element" class="card-input p-4 border border-gray-300 rounded-xl bg-white focus-within:ring-2 focus-within:ring-red-500 focus-within:border-red-500"></div>
                                <div id="card-errors" class="text-red-600 text-sm mt-2"></div>
                            </div>
                        </div>

                        <!-- ID de transaction -->
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <div class="text-xs text-gray-500 mb-1">ID de transaction</div>
                            <div class="font-mono text-sm text-gray-700">{{ strtoupper(uniqid()) }}</div>
                        </div>

                        <!-- Conditions générales -->
                        <div class="pt-4">
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" name="accept_conditions" class="mt-1 mr-3 text-red-600 border-gray-300 rounded focus:ring-red-500" required>
                                <span class="text-sm text-gray-600 leading-relaxed">
                                    J'accepte les <a href="#" class="text-red-600 hover:text-red-800 underline font-medium">conditions générales</a> 
                                    et la <a href="#" class="text-red-600 hover:text-red-800 underline font-medium">politique de confidentialité</a>
                                </span>
                            </label>
                        </div>

                        <!-- Bouton de paiement -->
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-red-600 to-red-800 text-white font-bold text-lg rounded-2xl hover:from-red-700 hover:to-red-900 focus:outline-none focus:ring-4 focus:ring-red-300 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <span id="button-text" class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Payer {{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA
                            </span>
                            <span id="button-loading" class="hidden flex items-center justify-center">
                                <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Traitement en cours
                                <span class="loading-dots ml-1">...</span>
                            </span>
                        </button>

                        <!-- Lien d'annulation -->
                        <div class="text-center pt-2">
                            <a href="{{ route('reservations.cancel', $reservation->id) }}" 
                               class="text-gray-500 hover:text-gray-700 text-sm underline transition-colors">
                                Annuler la réservation
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer sécurité -->
            <div class="text-center mt-6 text-xs text-gray-500">
                <p class="mb-2">🔒 Vos données bancaires sont cryptées selon les normes PCI DSS</p>
                <p>Powered by Stripe • Paiement sécurisé</p>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('payment-form');
            const submitButton = form.querySelector('button[type="submit"]');
            const buttonText = document.getElementById('button-text');
            const buttonLoading = document.getElementById('button-loading');
            const checkbox = document.querySelector('input[name="accept_conditions"]');
            const nomCarteInput = document.querySelector('input[name="nom_carte"]');

            // Variables pour suivre l'état de validation
            let cardValid = false;
            let nameValid = false;
            let conditionsAccepted = false;

            // Function pour mettre à jour l'état du bouton
            function updateButtonState() {
                const isFormValid = cardValid && nameValid && conditionsAccepted;
                
                submitButton.disabled = !isFormValid;
                
                if (isFormValid) {
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            // Configuration Stripe
            const stripe = Stripe('{{ config('services.stripe.key') }}');
            const elements = stripe.elements();
            
            const card = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#1F2937',
                        fontFamily: 'Inter, system-ui, sans-serif',
                        '::placeholder': { 
                            color: '#9CA3AF',
                            fontWeight: '400'
                        },
                        iconColor: '#DC2626'
                    },
                    focus: {
                        color: '#1F2937',
                        iconColor: '#B91C1C'
                    },
                    invalid: {
                        color: '#EF4444',
                        iconColor: '#EF4444'
                    }
                }
            });
            
            card.mount('#card-element');
            const errorElement = document.getElementById('card-errors');
            
            // Gestion des changements de carte
            card.on('change', function(event) {
                if (event.error) {
                    errorElement.textContent = event.error.message;
                    errorElement.classList.add('shake');
                    setTimeout(() => errorElement.classList.remove('shake'), 500);
                    cardValid = false;
                } else {
                    errorElement.textContent = '';
                    cardValid = event.complete;
                }
                updateButtonState();
            });

            // Validation du nom sur la carte
            nomCarteInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '').toUpperCase();
                nameValid = this.value.trim().length >= 2;
                updateButtonState();
            });

            // Gestion du checkbox des conditions
            checkbox.addEventListener('change', function() {
                conditionsAccepted = this.checked;
                updateButtonState();
            });
            
            // Soumission du formulaire
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                
                // Vérification finale
                if (!conditionsAccepted) {
                    checkbox.parentElement.classList.add('shake');
                    setTimeout(() => {
                        checkbox.parentElement.classList.remove('shake');
                    }, 500);
                    return;
                }
                
                submitButton.disabled = true;
                buttonText.classList.add('hidden');
                buttonLoading.classList.remove('hidden');

                const { token, error } = await stripe.createToken(card);
                
                if (error) {
                    errorElement.textContent = error.message;
                    errorElement.classList.add('shake');
                    setTimeout(() => errorElement.classList.remove('shake'), 500);
                    
                    submitButton.disabled = false;
                    buttonText.classList.remove('hidden');
                    buttonLoading.classList.add('hidden');
                } else {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', token.id);
                    form.appendChild(hiddenInput);
                    
                    form.submit();
                }
            });

            // Animation des inputs
            const inputs = document.querySelectorAll('.card-input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 12px 40px rgba(220, 38, 38, 0.2)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });

            // État initial du bouton (désactivé par défaut)
            updateButtonState();
        });
    </script>
</body>
</html>