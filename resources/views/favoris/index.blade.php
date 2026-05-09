@extends('layouts.plaout')

@section('title', 'Mes Favoris')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Mes appartement Favorites</h1>
        <div class="space-y-6">
            @forelse($favoris as $chambre)
                <!-- Réutilisez le même template que pour la liste des appartement -->
                @include('partials.chambre-card', ['chambre' => $chambre])
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Aucun favori</h3>
                    <p class="text-gray-600">Vous n'avez pas encore ajouté de appartement à vos favoris.</p>
                </div>
            @endforelse
        </div>
        @if($favoris->hasPages())
            <div class="mt-8">
                {{ $favoris->links() }}
            </div>
        @endif
    </div>
</div>
@endsection