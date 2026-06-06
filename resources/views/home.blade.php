@extends('layouts.app')
@section('title', 'Inicio - Bolsa de Trabajo')

@push('styles')
<style>
    /* Animación nativa para el carrusel infinito sin JS */
    @keyframes slide-left {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .carousel-track {
        display: flex;
        width: max-content;
        animation: slide-left 35s linear infinite;
    }
    .carousel-track:hover {
        animation-play-state: paused;
    }
    /* Limitar líneas de texto para descripciones largas */
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>
@endpush

@section('content')
    <section class="hero-gradient text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 animate-bounce">
                Bienvenido a la Bolsa de Trabajo del<br>
                <em>IESTP Francisco Vigo Caballero</em>
            </h1>
            <p class="text-xl mb-8">Encuentra las mejores oportunidades laborales para estudiantes y egresados</p>
            
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('servicios.ofertas') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <input type="text" name="search" placeholder="Buscar por palabra clave..." class="flex-1 px-6 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300">
                    <button type="submit" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition flex items-center justify-center gap-2">
                        <i class="bi bi-search"></i>
                        Buscar
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 card-hover rounded-lg bg-gray-50 border border-gray-100">
                    <i class="bi bi-briefcase text-4xl text-purple-600 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $jobOffers->count() }}+</h3>
                    <p class="text-gray-600">Ofertas activas</p>
                </div>
                <div class="text-center p-6 card-hover rounded-lg bg-gray-50 border border-gray-100">
                    <i class="bi bi-building text-4xl text-purple-600 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $partners->count() }}+</h3>
                    <p class="text-gray-600">Empresas asociadas</p>
                </div>
                <div class="text-center p-6 card-hover rounded-lg bg-gray-50 border border-gray-100">
                    <i class="bi bi-people text-4xl text-purple-600 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $users->where('role', 'usuario')->count() }}+</h3>
                    <p class="text-gray-600">Estudiantes registrados</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Últimas Ofertas de Empleo</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($jobOffers->take(6) as $offer)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 card-hover flex flex-col h-full relative overflow-hidden">
                        
                        @if($offer->source)
                            <span class="absolute top-4 right-4 bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1 rounded-full">
                                {{ $offer->source }}
                            </span>
                        @endif

                        <div class="flex items-center gap-4 mb-4 mt-2">
                            <div class="w-12 h-12 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-center shrink-0">
                                <i class="bi bi-laptop text-2xl text-gray-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-800 leading-tight">{{ $offer->title }}</h3>
                                <p class="text-purple-600 font-medium text-sm">{{ $offer->company }}</p>
                            </div>
                        </div>

                        <p class="text-gray-500 text-sm mb-4 line-clamp-3 flex-grow">
                            {{ $offer->description ?? 'No hay una descripción detallada para esta oferta de trabajo. Haz clic en "Ver más" para consultar los detalles directamente en la fuente.' }}
                        </p>

                        <div class="space-y-2 mb-6 pt-4 border-t border-gray-50">
                            <p class="text-gray-600 flex items-center gap-2 text-sm font-medium">
                                <i class="bi bi-geo-alt-fill text-gray-400"></i>
                                {{ $offer->location ?? 'Ubicación no especificada' }}
                            </p>
                        </div>

                        <a href="{{ $offer->url ?? '#' }}" target="{{ $offer->url ? '_blank' : '_self' }}" rel="noopener noreferrer" class="mt-auto w-full bg-slate-900 text-white py-2.5 rounded-lg hover:bg-slate-800 transition-colors flex items-center justify-center gap-2 font-semibold">
                            Ver detalles
                            <i class="bi bi-box-arrow-up-right text-sm"></i>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <i class="bi bi-inbox text-4xl text-gray-400 mb-3 block"></i>
                        <h3 class="text-lg font-bold text-gray-700">No hay ofertas disponibles</h3>
                        <p class="text-gray-500">Actualmente no contamos con ofertas de empleo activas. Vuelve pronto.</p>
                    </div>
                @endforelse
            </div>
            
            @if($jobOffers->count() > 6)
                <div class="text-center mt-12">
                    <a href="{{ route('ofertas') }}" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-bold transition-colors">
                        Ver todas las ofertas ({{ $jobOffers->count() }})
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <section class="py-16 bg-white border-t border-gray-100 overflow-hidden">
        <div class="container mx-auto px-4 mb-10">
            <h2 class="text-3xl font-bold text-center text-gray-800">Convenios con las empresas e instituciones que confían en nosotros</h2>
            <p class="text-center text-gray-500 mt-2">Nuestros aliados estratégicos en la formación profesional</p>
        </div>
        
        @if($partners->isNotEmpty())
            <div class="relative w-full max-w-[1400px] mx-auto overflow-hidden flex items-center">
                <div class="absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
                <div class="absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>
                <div class="carousel-track gap-8 px-4">
                    {{-- Iteramos 2 veces el mismo arreglo para lograr el bucle infinito perfecto en la animación del 50% --}}
                    @for ($i = 0; $i < 2; $i++)
                        @foreach($partners as $partner)
                            <div class="w-48 h-32 shrink-0 bg-white border border-gray-100 rounded-xl flex items-center justify-center p-4 shadow-sm hover:shadow-md transition-shadow cursor-default">
                                @if($partner->image_url)
                                    <img src="{{ Storage::url($partner->image_url) }}" alt="{{ $partner->company }}" class="w-full h-full object-contain filter grayscale hover:grayscale-0 transition-all duration-300">
                                @else
                                    <span class="text-gray-400 font-bold text-center text-sm px-2">{{ $partner->company }}</span>
                                @endif
                            </div>
                        @endforeach
                    @endfor
                </div>
            </div>
        @else
            <div class="text-center text-gray-500 py-8">
                Próximamente estaremos añadiendo a nuestros aliados estratégicos.
            </div>
        @endif
    </section>
@endsection