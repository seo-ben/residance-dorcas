<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-50 to-blue-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-xl overflow-hidden sm:rounded-xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">{{ __('Zone sécurisée') }}</h2>
                <p class="mt-2 text-sm text-gray-600">
                    {{ __('Cette zone est sécurisée. Veuillez confirmer votre mot de passe avant de continuer.') }}
                </p>
            </div>

            <x-validation-errors class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700" />

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                @csrf

                <div>
                    <x-label for="password" value="{{ __('Mot de passe') }}" class="block text-sm font-medium text-gray-700" />
                    <div class="mt-1">
                        <x-input id="password" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password" 
                            autofocus />
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        {{ __('Confirmer') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>