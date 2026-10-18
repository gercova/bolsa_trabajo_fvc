@extends('library.layout')
@section('title', 'Buscar en la Biblioteca Virtual')
@section('page_title', 'Buscar')
@section('reader_content')
<div class="space-y-6" x-data="readerSearchApp()">
    {{-- Search and Filter Toolbar --}}
    <div class="space-y-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Buscar en el Catálogo</h1>
            <p class="text-xs text-slate-500">Explore libros, revistas científicas, papers, tesis y documentos en formato PDF directo por título, autor o temática.</p>
        </div>

        {{-- Search Input with Submit & Clear Actions --}}
        <div class="relative flex items-center">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
            <input type="text" x-model="queryText"
                placeholder="Escriba un título, autor, temática, DOI o código ISBN..."
                @keydown.enter="filter('q', queryText)"
                class="w-full pl-11 pr-28 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-medium focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition text-slate-900">
            
            <div class="absolute right-2 flex items-center gap-1">
                <button type="button" x-show="queryText.length > 0" @click="queryText = ''; filter('q', '')"
                    class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg transition" title="Limpiar texto">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
                <button type="button" @click="filter('q', queryText)"
                    class="px-3.5 py-1.5 bg-[#0b4d57] hover:bg-[#093d45] text-white text-xs font-bold rounded-xl transition shadow-2xs">
                    Buscar
                </button>
            </div>
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

        {{-- Category Filter Pills (Incluye Tesis) --}}
        <div class="flex items-center gap-2 flex-wrap text-xs">
            <span class="text-slate-400 font-bold uppercase text-[10px] mr-1">Tipo:</span>
            @foreach(['all' => 'Todos', 'Libro' => 'Libros', 'Revista' => 'Revistas', 'Paper' => 'Papers', 'Tesis' => 'Tesis', 'Documento' => 'Documentos'] as $catKey =>$catLabel)
                <button type="button" @click="filter('category', '{{ $catKey }}')"
                    class="px-2.5 py-1 rounded-lg text-xs font-semibold transition {{ $selectedCategory ===$catKey ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100' }}">
                    {{ $catLabel }}
                </button>
            @endforeach
        </div>

    </div>

    {{-- Results Counter --}}
    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
        <span>Se encontraron <strong>{{ $books->total() }}</strong> documentos verificados</span>
        @if($searchQuery || $selectedCareer !== 'all' || $selectedCategory !== 'all')
            <a href="{{ route('biblioteca.search') }}" class="text-blue-600 hover:underline font-bold inline-flex items-center gap-1">
                <i class="bi bi-arrow-counterclockwise"></i> Limpiar filtros
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
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                    loading="lazy">
                            @else
                                <div class="p-3 text-center flex flex-col items-center justify-center h-full w-full bg-slate-50 text-slate-400">
                                    <i class="bi bi-file-earmark-pdf text-3xl mb-1 text-rose-500/70"></i>
                                    <span class="text-[10px] font-bold text-slate-600 line-clamp-3">{{ $book->title }}</span>
                                </div>
                            @endif

                            {{-- Career tag --}}
                            <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                                {{ $book->studyProgram ? Str::limit($book->studyProgram->name, 14) : 'General' }}
                            </span>

                            {{-- Category badge --}}
                            <span class="absolute bottom-1.5 right-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-600/90 text-white backdrop-blur-xs">
                                {{ $book->category ?: 'PDF' }}
                            </span>
                        </div>
                    </a>

                    {{-- Metadata --}}
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-0.5 text-amber-400 text-[11px]">
                                @php $stars = (int) round($book->rating ?? 5); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <=$stars ? 'bi-star-fill' : 'bi-star text-slate-300' }}"></i>
                                @endfor
                            </div>
                            @if($book->publication_year)
                                <span class="text-[10px] font-semibold text-slate-400">{{ $book->publication_year }}</span>
                            @endif
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
                            <i class="bi bi-file-earmark-pdf-fill"></i> Leer PDF
                        </a>
                        <button type="button" @click="toggleFavorite({{ $book->id }})"
                            :class="favoritesMap[{{ $book->id }}] ? 'text-rose-500' : 'text-slate-400 hover:text-rose-500'"
                            class="transition p-1" title="Guardar en Favoritos">
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
            queryText: @js($searchQuery),
            favoritesMap: {
                @foreach($books as $b)
                    {{ $b->id }}: {{ !empty($b->is_favorited) ? 'true' : 'false' }},
                @endforeach
            },

            filter(param, value) {
                const url = new URL(window.location.href);
                if (value === 'all' || !value || String(value).trim() === '') {
                    url.searchParams.delete(param);
                } else {
                    url.searchParams.set(param, String(value).trim());
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