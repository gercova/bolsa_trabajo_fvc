@extends('library.layout')

@section('title', 'Biblioteca Virtual - Inicio')
@section('page_title', 'Inicio')

@section('reader_content')
<div class="space-y-10" x-data="readerHomeApp()">

    {{-- Greeting Header (Matching Screenshot 2) --}}
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
            Hola, {{ $user->names }}.
        </h1>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- SECTION 1: Mi biblioteca (Favorites - Matching Screenshot 2)       --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="space-y-4">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Mi biblioteca</h2>
                <p class="text-xs text-slate-500">Aquí se listan los últimos libros agregados a tu biblioteca</p>
            </div>
            @if($favoriteBooks->count() > 0)
                <a href="{{ route('biblioteca.favorites') }}"
                    class="text-xs font-bold text-slate-600 hover:text-blue-600 transition">
                    Ver más
                </a>
            @endif
        </div>

        @if($favoriteBooks->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @foreach($favoriteBooks as $book)
                    <div class="group bg-white rounded-xl border border-slate-200/90 p-3 shadow-xs hover:shadow-md transition-all flex flex-col justify-between relative">
                        
                        {{-- Cover Image --}}
                        <a href="{{ route('biblioteca.read', $book->slug) }}" class="block mb-2.5">
                            <div class="w-full aspect-[2/3] rounded-lg overflow-hidden bg-slate-100 border border-slate-100 flex items-center justify-center relative">
                                @if($book->cover_url)
                                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="p-3 text-center flex flex-col items-center justify-center h-full w-full bg-slate-50 text-slate-400">
                                        <i class="bi bi-book text-3xl mb-1 text-slate-300"></i>
                                        <span class="text-[10px] font-bold text-slate-500 line-clamp-2">{{ $book->title }}</span>
                                    </div>
                                @endif

                                {{-- Read Badge on Hover --}}
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="px-3 py-1.5 bg-white text-slate-900 font-bold text-xs rounded-lg shadow-sm">
                                        <i class="bi bi-book-half mr-1 text-blue-600"></i> Leer
                                    </span>
                                </div>
                            </div>
                        </a>

                        {{-- Metadata --}}
                        <div class="space-y-1">
                            <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>

                            <a href="{{ route('biblioteca.read', $book->slug) }}" class="block">
                                <h3 class="text-xs font-bold text-slate-900 line-clamp-1 leading-snug group-hover:text-blue-600 transition" title="{{ $book->title }}">
                                    {{ $book->title }}
                                </h3>
                            </a>

                            <p class="text-[11px] text-slate-500 line-clamp-1" title="{{ $book->author }}">
                                {{ $book->author }}
                            </p>
                        </div>

                        {{-- Action Strip --}}
                        <div class="pt-2 mt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                            <a href="{{ route('biblioteca.read', $book->slug) }}"
                                class="text-blue-600 hover:text-blue-800 font-bold text-[11px]">
                                Leer ahora
                            </a>
                            <button type="button" @click="toggleFavorite({{ $book->id }})"
                                class="text-rose-500 hover:scale-110 transition p-1" title="Quitar de favoritos">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State (Exact text from Screenshot 2) --}}
            <div class="py-10 text-center text-slate-400 text-xs font-medium">
                Aún no has agregado libros a tu biblioteca
            </div>
        @endif

    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- SECTION 2: Leídos últimamente (Matching Screenshot 2)              --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="space-y-4 pt-4 border-t border-slate-100">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Leídos últimamente</h2>
                <p class="text-xs text-slate-500">Aquí se listan los últimos 5 libros que has leído</p>
            </div>
            @if($recentlyReadBooks->count() > 0)
                <a href="{{ route('biblioteca.search') }}"
                    class="text-xs font-bold text-slate-600 hover:text-blue-600 transition">
                    Ver más
                </a>
            @endif
        </div>

        @if($recentlyReadBooks->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @foreach($recentlyReadBooks as $book)
                    <div class="group bg-white rounded-xl border border-slate-200/90 p-3 shadow-xs hover:shadow-md transition-all flex flex-col justify-between relative">
                        
                        {{-- Cover Image --}}
                        <a href="{{ route('biblioteca.read', $book->slug) }}" class="block mb-2.5">
                            <div class="w-full aspect-[2/3] rounded-lg overflow-hidden bg-slate-100 border border-slate-100 flex items-center justify-center relative">
                                @if($book->cover_url)
                                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="p-3 text-center flex flex-col items-center justify-center h-full w-full bg-slate-50 text-slate-400">
                                        <i class="bi bi-book text-3xl mb-1 text-slate-300"></i>
                                        <span class="text-[10px] font-bold text-slate-500 line-clamp-2">{{ $book->title }}</span>
                                    </div>
                                @endif

                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="px-3 py-1.5 bg-white text-slate-900 font-bold text-xs rounded-lg shadow-sm">
                                        <i class="bi bi-book-half mr-1 text-blue-600"></i> Continuar lectura
                                    </span>
                                </div>
                            </div>
                        </a>

                        {{-- Metadata (Matching Screenshot 2) --}}
                        <div class="space-y-1">
                            <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>

                            <a href="{{ route('biblioteca.read', $book->slug) }}" class="block">
                                <h3 class="text-xs font-bold text-slate-900 line-clamp-1 leading-snug group-hover:text-blue-600 transition" title="{{ $book->title }}">
                                    {{ $book->title }}
                                </h3>
                            </a>

                            <p class="text-[11px] text-slate-500 line-clamp-1" title="{{ $book->author }}">
                                {{ $book->author }}
                            </p>
                        </div>

                        {{-- Action Strip --}}
                        <div class="pt-2 mt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                            <a href="{{ route('biblioteca.read', $book->slug) }}"
                                class="text-blue-600 hover:text-blue-800 font-bold text-[11px]">
                                Leer
                            </a>
                            <button type="button" @click="toggleFavorite({{ $book->id }})"
                                :class="favoritesMap[{{ $book->id }}] ? 'text-rose-500' : 'text-slate-400 hover:text-rose-500'"
                                class="transition p-1" title="Favorito">
                                <i class="bi" :class="favoritesMap[{{ $book->id }}] ? 'bi-heart-fill' : 'bi-heart'"></i>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="py-10 text-center text-slate-400 text-xs font-medium">
                No has leído libros recientemente. Explora nuestro catálogo en la sección <a href="{{ route('biblioteca.search') }}" class="text-blue-600 font-bold underline">Buscar</a>.
            </div>
        @endif

    </div>

</div>
@endsection

@push('scripts')
<script>
    function readerHomeApp() {
        return {
            favoritesMap: {
                @foreach($recentlyReadBooks as $b)
                    {{ $b->id }}: {{ $b->is_favorited ? 'true' : 'false' }},
                @endforeach
            },

            async toggleFavorite(bookId) {
                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch(`/biblioteca/favorito/${bookId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        }
                    });
                    const json = await res.json();
                    if (json.success) {
                        this.favoritesMap[bookId] = json.favorited;
                    }
                } catch (e) {
                    console.error('Error toggling favorite:', e);
                }
            }
        };
    }
</script>
@endpush
