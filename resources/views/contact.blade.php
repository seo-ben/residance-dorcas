@extends('layouts.plaout')

@section('title', 'Contactez la Résidence Dorcas | Assistance 24/7 à Lomé')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <section class="relative py-12 bg-gray-900 overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-40">
            <img src="{{ asset('brain/01c72661-5ef8-4046-ad83-e7ed6cd9e76e/residence_dorcas_reception_1777903676876.png') }}" 
                 alt="Réception" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900 via-transparent to-gray-900"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div data-aos="zoom-in">
                <span class="inline-block px-3 py-1 mb-4 text-[10px] font-black tracking-[0.3em] text-red-500 border-l-4 border-red-500 bg-white/10 backdrop-blur-sm">
                    BESOIN D'AIDE ?
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-white leading-tight mb-6">
                    Nous sommes à votre <br>
                    <span class="text-red-500">entière disposition</span>
                </h1>
                <p class="text-lg text-gray-300 font-medium max-w-2xl mx-auto">
                    Une question sur une réservation, un service particulier ou un besoin spécifique ? Notre équipe vous répond en moins de 30 minutes.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                
                <!-- Contact Info Cards -->
                <div class="lg:col-span-5 space-y-8" data-aos="fade-right">
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 mb-2">Restons en contact</h2>
                        <p class="text-gray-500 font-medium">Choisissez le canal qui vous convient le mieux.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- WhatsApp Card -->
                        <a href="https://wa.me/22890149918" target="_blank" class="group p-6 bg-emerald-50 rounded-3xl border border-emerald-100 flex items-center gap-6 hover:shadow-xl hover:shadow-emerald-500/10 transition-all">
                            <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                                <i class="fab fa-whatsapp text-2xl"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">WhatsApp Business</span>
                                <span class="text-lg font-bold text-gray-900">+228 90 14 99 18</span>
                            </div>
                        </a>

                        <!-- Phone Card -->
                        <div class="p-6 bg-red-50 rounded-3xl border border-red-100 flex items-center gap-6">
                            <div class="w-14 h-14 bg-red-600 rounded-2xl flex items-center justify-center text-white">
                                <i class="fas fa-phone-alt text-2xl"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-red-600 uppercase tracking-widest mb-1">Téléphone Direct</span>
                                <span class="text-lg font-bold text-gray-900">+228 90 14 99 18</span>
                            </div>
                        </div>

                        <!-- Email Card -->
                        <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100 flex items-center gap-6">
                            <div class="w-14 h-14 bg-gray-900 rounded-2xl flex items-center justify-center text-white">
                                <i class="fas fa-envelope text-2xl"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Email Officiel</span>
                                <span class="text-lg font-bold text-gray-900">info@residencedorcas.com</span>
                            </div>
                        </div>

                        <!-- Address Card -->
                        <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100 flex items-center gap-6">
                            <div class="w-14 h-14 bg-gray-900 rounded-2xl flex items-center justify-center text-white">
                                <i class="fas fa-map-marker-alt text-2xl"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Localisation</span>
                                <span class="text-lg font-bold text-gray-900">65WH+RX, Lomé, Togo</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-7" data-aos="fade-left">
                    <div class="bg-white rounded-[40px] shadow-2xl shadow-gray-200/50 border border-gray-100 p-8 md:p-12">
                        <form action="#" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Nom Complet</label>
                                    <input type="text" placeholder="Ex: Jean Dupont" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all font-medium text-gray-900 placeholder-gray-300">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Adresse Email</label>
                                    <input type="email" placeholder="Ex: jean@mail.com" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all font-medium text-gray-900 placeholder-gray-300">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Objet de la demande</label>
                                <select class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all font-medium text-gray-900 cursor-pointer">
                                    <option>Réservation d'appartement</option>
                                    <option>Location de véhicule</option>
                                    <option>Demande de visite</option>
                                    <option>Autre demande</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Message</label>
                                <textarea rows="5" placeholder="Comment pouvons-nous vous aider ?" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all font-medium text-gray-900 placeholder-gray-300 resize-none"></textarea>
                            </div>

                            <button type="submit" class="w-full py-5 bg-gray-900 hover:bg-red-600 text-white font-black uppercase tracking-[0.2em] text-xs rounded-2xl transition-all duration-300 shadow-xl hover:shadow-red-600/20 active:scale-[0.98]">
                                Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="h-96 w-full grayscale hover:grayscale-0 transition-all duration-700 overflow-hidden">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d1.2222!3d6.1248!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMDcnMjkuMyJOIDHCsDEzJzE5LjkiRQ!5e0!3m2!1sfr!2stg!4v1620000000000!5m2!1sfr!2stg" 
            width="100%" 
            height="100%" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
    </section>
</div>
@endsection