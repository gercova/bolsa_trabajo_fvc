@extends('library.layout')

@section('title', 'Mi Biblioteca - Biblioteca Virtual')
@section('page_title', 'Mi Biblioteca')

@section('reader_content')
<div class="space-y-6" x-data="favoritesApp()">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <i class="bi bi-bookmark-heart-fill text-blue-600"></i> Mi Biblioteca
            </h1>
            <p class="text-xs text-slate-500">Tus libros y documentos favoritos guardados para consulta y lectura rápida.</p>
        </div>

        {{-- Career Filter --}}
        <div>
            <select @change="filterCareer($event.target.value)"
                class="text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-700">
                <option value="all" {{ $selectedCareer === 'all' ? 'selected' : '' }}>Todas las carreras</option>
                @foreach($studyPrograms as $prog)
                    <option value="{{ $prog->id }}" {{ (string)$selectedCareer === (string)$prog->id ? 'selected' : '' }}>
                        {{ $prog->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Books Grid --}}
    @if($books->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
            @foreach($books as $book)
                <div x-data="{ removed: false }" x-show="!removed" x-transition.opacity
                    class="group bg-white rounded-xl border border-slate-200/90 p-3 shadow-xs hover:shadow-md transition-all flex flex-col justify-between relative">
                    
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
                        <button type="button" @click="removeFavorite({{ $book->id }}, () => removed = true)"
                            class="text-rose-500 hover:text-slate-400 transition p-1" title="Quitar de mi biblioteca">
                            <i class="bi bi-trash"></i>
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
            <div class="w-12 h-12 mx-auto rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl">
                <i class="bi bi-bookmark-x"></i>
            </div>
            <p class="text-sm font-semibold text-slate-700">Aún no has agregado libros a tu biblioteca.</p>
            <p class="text-xs text-slate-500">Puedes guardar libros en tus favoritos desde el catálogo de búsqueda haciendo clic en el icono de corazón.</p>
            <div>
                <a href="{{ route('biblioteca.search') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs inline-flex items-center gap-1.5 transition">
                    <i class="bi bi-search"></i> Explorar Catálogo
                </a>
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function favoritesApp() {
        return {
            filterCareer(careerId) {
                const url = new URL(window.location.href);
                if (careerId === 'all') {
                    url.searchParams.delete('career');
                } else {
                    url.searchParams.set('career', careerId);
                }
                url.searchParams.delete('page');
                window.location.href = url.toString();
            },

            async removeFavorite(bookId, callback) {
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
                    if (json.success && !json.favorited) {
                        callback();
                    }
                } catch (e) {
                    console.error('Error removing favorite:', e);
                }
            }
        };
    }
</script>
@endpush
