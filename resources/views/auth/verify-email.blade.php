<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-50 to-blue-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-xl overflow-hidden sm:rounded-xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">{{ __('Vérification de l\'email') }}</h2>
                <p class="mt-2 text-sm text-gray-600">
                    {{ __('Avant de continuer, veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer. Si vous n\'avez pas reçu l\'e-mail, nous pouvons vous en envoyer un autre.') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 text-sm text-green-600 bg-green-50 p-4 rounded-lg text-center">
                    {{ __('Un nouveau lien de vérification a été envoyé à l\'adresse e-mail que vous avez fournie dans vos paramètres de profil.') }}
                </div>
            @endif

            <div class="space-y-6">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        {{ __('Renvoyer l\'email de vérification') }}
                    </button>
                </form>

                <div class="flex items-center justify-center space-x-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('profile.show') }}"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        {{ __('Modifier le profil') }}
                    </a>

                    <span class="text-gray-300">|</span>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            {{ __('Se déconnecter') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>