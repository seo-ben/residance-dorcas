<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#f8f9fc] to-[#eef1f8] font-sans">
        <div class="w-full max-w-md px-6">
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <div class="text-center mb-10">
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Mot de passe oublié</h2>
                        <p class="mt-2 text-gray-400 text-sm font-medium leading-relaxed px-4">
                            {{ __('Indiquez-nous votre adresse e-mail et nous vous enverrons un lien de réinitialisation.') }}
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="mb-6 font-bold text-xs text-emerald-600 bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                            {{ session('status') }}
                        </div>
                    @endif

                    <x-validation-errors class="mb-6 rounded-xl bg-red-50 p-4 text-xs text-red-600 border border-red-100" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-label for="email" value="{{ __('Email') }}" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1" />
                            <x-input id="email" class="block w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-300 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none" type="email" name="email" :value="old('email')" required autofocus placeholder="votre@email.com" />
                        </div>

                        <button type="submit" class="w-full bg-gray-900 hover:bg-red-600 text-white py-3.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all duration-300 shadow-sm hover:shadow-red-200 active:scale-[0.98]">
                            {{ __('Envoyer le lien') }}
                        </button>
                    </form>
                </div>

                <div class="bg-gray-50/50 border-t border-gray-100 p-6 text-center">
                    <a href="{{ route('login') }}" class="text-xs font-bold text-red-600 hover:text-red-500 transition-colors uppercase tracking-widest flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('Retour à la connexion') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>