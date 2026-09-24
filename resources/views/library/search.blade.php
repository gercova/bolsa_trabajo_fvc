@extends('library.layout')

@section('title', 'Buscar en la Biblioteca Virtual')
@section('page_title', 'Buscar')

@section('reader_content')
<div class="space-y-6" x-data="readerSearchApp()">

    {{-- Search and Filter Toolbar --}}
    <div class="space-y-4">
        
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Buscar en el Catálogo</h1>
            <p class="text-xs text-slate-500">Explore libros, revistas científicas, investigaciones y documentos por título, autor o temática.</p>
        </div>

        {{-- Search Input --}}
        <div class="relative">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" placeholder="Escriba un título, autor, temática o código ISBN..."
                value="{{ $searchQuery }}"
                @keydown.enter="filter('q', $event.target.value)"
                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-medium focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition text-slate-900">
        </div>

        {{-- Career Filter Chips --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
            <button type="button" @click="filter('career', 'all')"
                class="px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition {{ $selectedCareer === 'all' ? 'bg-[#0b4d57] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Todas las carreras
            </button>
            @foreach($studyPrograms as $prog)
                <button type="button" @click="filter('career', '{{ $prog->id }}')"
                    class="px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition {{ (string)$selectedCareer === (string)$prog->id ? 'bg-[#0b4d57] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $prog->name }}
                </button>
            @endforeach
        </div>

        {{-- Category Filter Pills --}}
        <div class="flex items-center gap-2 text-xs">
            <span class="text-slate-400 font-bold uppercase text-[10px] mr-1">Tipo:</span>
            <button type="button" @click="filter('category', 'all')"
                class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $selectedCategory === 'all' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-slate-500 hover:text-slate-800' }}">
                Todos
            </button>
            <button type="button" @click="filter('category', 'Libro')"
                class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $selectedCategory === 'Libro' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-slate-500 hover:text-slate-800' }}">
                Libros
            </button>
            <button type="button" @click="filter('category', 'Revista')"
                class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $selectedCategory === 'Revista' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-slate-500 hover:text-slate-800' }}">
                Revistas
            </button>
            <button type="button" @click="filter('category', 'Paper')"
                class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $selectedCategory === 'Paper' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-slate-500 hover:text-slate-800' }}">
                Papers
            </button>
            <button type="button" @click="filter('category', 'Documento')"
                class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $selectedCategory === 'Documento' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-slate-500 hover:text-slate-800' }}">
                Documentos
            </button>
        </div>

    </div>

    {{-- Results Counter --}}
    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
        <span>Se encontraron <strong>{{ $books->total() }}</strong> resultados</span>
        @if($searchQuery || $selectedCareer !== 'all' || $selectedCategory !== 'all')
            <a href="{{ route('biblioteca.search') }}" class="text-blue-600 hover:underline font-bold">
                Limpiar filtros
            </a>
        @endif
    </div>

    {{-- Books Grid --}}
    @if($books->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
            @foreach($books as $book)
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

                            {{-- Career tag --}}
                            <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                                {{ $book->studyProgram ? Str::limit($book->studyProgram->name, 12) : 'General' }}
                            </span>
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
                            <h3 class="text-xs font-bold text-slate-900 line-clamp-2 leading-snug group-hover:text-blue-600 transition" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h3>
                        </a>

                        <p class="text-[11px] text-slate-500 line-clamp-1" title="{{ $book->author }}">
                            {{ $book->author }}
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-2 mt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                        <a href="{{ route('biblioteca.read', $book->slug) }}"
                            class="text-blue-600 hover:text-blue-800 font-bold text-[11px] inline-flex items-center gap-1">
                            <i class="bi bi-eye"></i> Leer
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

        <div class="pt-4">
            {{ $books->links() }}
        </div>
    @else
        <div class="py-16 text-center text-slate-400 space-y-3">
            <i class="bi bi-search text-3xl text-slate-300"></i>
            <p class="text-sm font-semibold text-slate-700">No encontramos coincidencias para tu búsqueda.</p>
            <p class="text-xs text-slate-500">Prueba con palabras clave más generales o selecciona otra carrera.</p>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function readerSearchApp() {
        return {
            favoritesMap: {
                @foreach($books as $b)
                    {{ $b->id }}: {{ $b->is_favorited ? 'true' : 'false' }},
                @endforeach
            },

            filter(param, value) {
                const url = new URL(window.location.href);
                if (value === 'all' || !value) {
                    url.searchParams.delete(param);
                } else {
                    url.searchParams.set(param, value);
                }
                url.searchParams.delete('page');
                window.location.href = url.toString();
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
