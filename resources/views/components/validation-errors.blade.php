@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'bg-red-50 p-4 rounded-xl shadow-sm mb-6']) }}>
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-black text-red-400  tracking-wider">
                    Veuillez vérifier vos informations
                </h3>
            </div>
        </div>

    </div>
@endif
