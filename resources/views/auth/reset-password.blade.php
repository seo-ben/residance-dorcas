<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-50 to-blue-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-xl overflow-hidden sm:rounded-xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">{{ __('Réinitialisation du mot de passe') }}</h2>
                <p class="mt-2 text-sm text-gray-600">{{ __('Créez un nouveau mot de passe sécurisé') }}</p>
            </div>

            <x-validation-errors class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700" />

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <x-label for="email" value="{{ __('Email') }}" class="block text-sm font-medium text-gray-700" />
                    <div class="mt-1">
                        <x-input id="email" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                            type="email" 
                            name="email" 
                            :value="old('email', $request->email)" 
                            required 
                            autofocus 
                            autocomplete="username" />
                    </div>
                </div>

                <div>
                    <x-label for="password" value="{{ __('Nouveau mot de passe') }}" class="block text-sm font-medium text-gray-700" />
                    <div class="mt-1">
                        <x-input id="password" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password" />
                    </div>
                </div>

                <div>
                    <x-label for="password_confirmation" value="{{ __('Confirmer le mot de passe') }}" class="block text-sm font-medium text-gray-700" />
                    <div class="mt-1">
                        <x-input id="password_confirmation" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password" />
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        {{ __('Réinitialiser le mot de passe') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>