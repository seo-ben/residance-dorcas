<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-50 to-blue-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-xl overflow-hidden sm:rounded-xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">{{ __('Authentification à deux facteurs') }}</h2>
            </div>

            <div x-data="{ recovery: false }">
                <div class="mb-6 text-sm text-gray-600 text-center" x-show="! recovery">
                    {{ __('Veuillez confirmer l\'accès à votre compte en saisissant le code d\'authentification fourni par votre application d\'authentification.') }}
                </div>

                <div class="mb-6 text-sm text-gray-600 text-center" x-cloak x-show="recovery">
                    {{ __('Veuillez confirmer l\'accès à votre compte en saisissant l\'un de vos codes de récupération d\'urgence.') }}
                </div>

                <x-validation-errors class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700" />

                <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-6">
                    @csrf

                    <div x-show="! recovery">
                        <x-label for="code" value="{{ __('Code d\'authentification') }}" class="block text-sm font-medium text-gray-700" />
                        <div class="mt-1">
                            <x-input id="code" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                type="text" 
                                inputmode="numeric" 
                                name="code" 
                                autofocus 
                                x-ref="code" 
                                autocomplete="one-time-code" />
                        </div>
                    </div>

                    <div x-cloak x-show="recovery">
                        <x-label for="recovery_code" value="{{ __('Code de récupération') }}" class="block text-sm font-medium text-gray-700" />
                        <div class="mt-1">
                            <x-input id="recovery_code" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                type="text" 
                                name="recovery_code" 
                                x-ref="recovery_code" 
                                autocomplete="one-time-code" />
                        </div>
                    </div>

                    <div class="flex flex-col space-y-4">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            {{ __('Se connecter') }}
                        </button>

                        <div class="text-center">
                            <button type="button" 
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                                x-show="! recovery"
                                x-on:click="
                                    recovery = true;
                                    $nextTick(() => { $refs.recovery_code.focus() })
                                ">
                                {{ __('Utiliser un code de récupération') }}
                            </button>

                            <button type="button" 
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                                x-cloak
                                x-show="recovery"
                                x-on:click="
                                    recovery = false;
                                    $nextTick(() => { $refs.code.focus() })
                                ">
                                {{ __('Utiliser un code d\'authentification') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>