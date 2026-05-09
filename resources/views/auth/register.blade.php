<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#f8f9fc] to-[#eef1f8] font-sans py-12">
        <div class="w-full max-w-md px-6">
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <div class="p-8 md:p-10">
                    <div class="text-center mb-10">
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Créer un compte</h2>
                        <p class="mt-2 text-gray-400 text-sm font-medium">Rejoignez-nous pour digitaliser votre établissement</p>
                    </div>

                    <x-validation-errors class="mb-6" />

                    <div class="space-y-4 mb-8">
                        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 bg-white border border-gray-200 hover:border-red-100 hover:bg-red-50/50 py-3 rounded-xl transition-all duration-300 group">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            <span class="text-sm font-bold text-gray-600 group-hover:text-red-600 transition-colors">S'inscrire avec Google</span>
                        </a>

                        <div class="relative flex items-center">
                            <div class="flex-grow border-t border-gray-100"></div>
                            <span class="flex-shrink mx-4 text-[10px] font-bold text-gray-300 uppercase tracking-widest">ou avec email</span>
                            <div class="flex-grow border-t border-gray-100"></div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-label for="name" value="{{ __('Nom complet') }}" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1" />
                            <x-input id="name" class="block w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-300 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Jean Dupont" />
                        </div>

                        <div>
                            <x-label for="telephone" value="{{ __('Téléphone') }}" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1" />
                            <div class="flex gap-2">
                                <div class="relative min-w-[100px]">
                                    <select name="indicatif" class="appearance-none block w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none cursor-pointer">
                                        <optgroup label="Afrique">
                                            <option value="+228" {{ old('indicatif') == '+228' ? 'selected' : '' }}>TG +228</option>
                                            <option value="+229" {{ old('indicatif') == '+229' ? 'selected' : '' }}>BJ +229</option>
                                            <option value="+225" {{ old('indicatif') == '+225' ? 'selected' : '' }}>CI +225</option>
                                            <option value="+221" {{ old('indicatif') == '+221' ? 'selected' : '' }}>SN +221</option>
                                            <option value="+226" {{ old('indicatif') == '+226' ? 'selected' : '' }}>BF +226</option>
                                            <option value="+233" {{ old('indicatif') == '+233' ? 'selected' : '' }}>GH +233</option>
                                            <option value="+234" {{ old('indicatif') == '+234' ? 'selected' : '' }}>NG +234</option>
                                        </optgroup>
                                        <optgroup label="Europe">
                                            <option value="+33" {{ old('indicatif') == '+33' ? 'selected' : '' }}>FR +33</option>
                                            <option value="+32" {{ old('indicatif') == '+32' ? 'selected' : '' }}>BE +32</option>
                                            <option value="+41" {{ old('indicatif') == '+41' ? 'selected' : '' }}>CH +41</option>
                                            <option value="+49" {{ old('indicatif') == '+49' ? 'selected' : '' }}>DE +49</option>
                                            <option value="+44" {{ old('indicatif') == '+44' ? 'selected' : '' }}>UK +44</option>
                                        </optgroup>
                                        <optgroup label="Amérique">
                                            <option value="+1" {{ old('indicatif') == '+1' ? 'selected' : '' }}>US/CA +1</option>
                                            <option value="+55" {{ old('indicatif') == '+55' ? 'selected' : '' }}>BR +55</option>
                                        </optgroup>
                                        <optgroup label="Asie / Russie">
                                            <option value="+7" {{ old('indicatif') == '+7' ? 'selected' : '' }}>RU +7</option>
                                            <option value="+86" {{ old('indicatif') == '+86' ? 'selected' : '' }}>CN +86</option>
                                            <option value="+91" {{ old('indicatif') == '+91' ? 'selected' : '' }}>IN +91</option>
                                            <option value="+81" {{ old('indicatif') == '+81' ? 'selected' : '' }}>JP +81</option>
                                        </optgroup>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-400">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                                <x-input id="telephone" class="block w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-300 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none" type="text" name="telephone" :value="old('telephone')" required placeholder="90 90 90 90" autocomplete="telephone" />
                            </div>
                        </div>

                        <div>
                            <x-label for="email" value="{{ __('Email') }}" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1" />
                            <x-input id="email" class="block w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-300 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none" type="email" name="email" :value="old('email')" required placeholder="votre@email.com" />
                        </div>

                        <div>
                            <x-label for="password" value="{{ __('Mot de passe') }}" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1" />
                            <x-input id="password" class="block w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-300 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none" type="password" name="password" required placeholder="••••••••" />
                        </div>

                        <div>
                            <x-label for="password_confirmation" value="{{ __('Confirmer') }}" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1" />
                            <x-input id="password_confirmation" class="block w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-300 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none" type="password" name="password_confirmation" required placeholder="••••••••" />
                        </div>

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="mt-4">
                                <x-label for="terms">
                                    <div class="flex items-center group cursor-pointer">
                                        <x-checkbox name="terms" id="terms" required class="w-4 h-4 text-red-600 border-gray-300 rounded-md focus:ring-red-500/20 transition-all" />
                                        <div class="ms-3 text-[10px] font-medium text-gray-500 leading-relaxed uppercase tracking-wider">
                                            {!! __('J\'accepte les :terms_of_service et la :privacy_policy', [
                                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-red-600 hover:text-red-500 font-bold transition-all">'.__('Conditions d\'utilisation').'</a>',
                                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-red-600 hover:text-red-500 font-bold transition-all">'.__('Politique de confidentialité').'</a>',
                                            ]) !!}
                                        </div>
                                    </div>
                                </x-label>
                            </div>
                        @endif

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-gray-900 hover:bg-red-600 text-white py-3.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all duration-300 shadow-sm hover:shadow-red-200 active:scale-[0.98]">
                                {{ __('Créer mon compte') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-gray-50/50 border-t border-gray-100 p-6 text-center">
                    <p class="text-xs font-medium text-gray-500">
                        {{ __('Déjà inscrit ?') }}
                        <a href="{{ route('login') }}" class="font-bold text-red-600 hover:text-red-500 ml-1 transition-colors">
                            {{ __('Se connecter') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>