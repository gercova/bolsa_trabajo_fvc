@extends('admin.library.layout')

@section('title', 'Libros Asignados - Biblioteca Virtual')

@section('library_content')
<div class="p-5 sm:p-7 space-y-6" x-data="assignedBooksApp()">

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TOPBAR / FILTERS HEADER (Matching Screenshot 1)                     --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-xs border border-slate-200/80 space-y-4">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            {{-- Title & Mobile Sidebar Toggle --}}
            <div class="flex items-center gap-3">
                <button type="button" @click="toggleSidebar()"
                    class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 lg:hidden">
                    <i class="bi bi-list text-xl"></i>
                </button>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        Libros Asignados
                    </h1>
                    <p class="text-xs text-slate-500">Catálogo general de materiales y bibliografía académica asignada.</p>
                </div>
            </div>

            {{-- Right: New Content Button & Record Counter --}}
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-xs font-semibold text-slate-500">
                    <strong class="text-slate-800">{{ number_format($totalCount) }}</strong> registros
                </span>

                <button type="button" @click="openCreateModal()"
                    class="px-4 py-2 bg-[#0b4d57] hover:bg-teal-900 text-white font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-2">
                    <i class="bi bi-plus-lg"></i>
                    <span>Subir Contenido</span>
                </button>
            </div>

        </div>

        {{-- Filters Bar: Career Dropdown, Search Input, View Mode --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 pt-3 border-t border-slate-100">
            
            {{-- Career Selector (Matching Screenshot 1) --}}
            <div class="w-full sm:w-64">
                <select name="career" @change="handleCareerFilter($event.target.value)"
                    class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition text-slate-700">
                    <option value="all" {{ $selectedCareer === 'all' ? 'selected' : '' }}>Todas las carreras</option>
                    @foreach($studyPrograms as $prog)
                        <option value="{{ $prog->id }}" {{ (string)$selectedCareer === (string)$prog->id ? 'selected' : '' }}>
                            {{ $prog->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Search Input (Matching Screenshot 1) --}}
            <div class="relative flex-1">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" placeholder="Buscar por título, autor, editorial o ISBN..."
                    value="{{ $searchQuery }}"
                    @keydown.enter="handleSearch($event.target.value)"
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition text-slate-800">
            </div>

            {{-- Grid / List Mode Switcher --}}
            <div class="flex items-center border border-slate-200 rounded-xl p-0.5 bg-slate-50 text-slate-500">
                <button type="button" @click="currentView = 'grid'"
                    :class="currentView === 'grid' ? 'bg-white text-teal-900 shadow-xs' : 'hover:text-slate-900'"
                    class="p-1.5 px-2.5 rounded-lg text-sm transition" title="Vista en Cuadrícula">
                    <i class="bi bi-grid-fill"></i>
                </button>
                <button type="button" @click="currentView = 'list'"
                    :class="currentView === 'list' ? 'bg-white text-teal-900 shadow-xs' : 'hover:text-slate-900'"
                    class="p-1.5 px-2.5 rounded-lg text-sm transition" title="Vista en Lista">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>

        </div>

    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl flex items-center justify-between">
            <span class="flex items-center gap-2">
                <i class="bi bi-check-circle-fill text-emerald-600"></i>
                {{ session('success') }}
            </span>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- CONTENT: GRID VIEW (Matching Screenshot 1)                         --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div x-show="currentView === 'grid'">
        @if($books->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-4 sm:gap-5">
                @foreach($books as $book)
                    <div class="group bg-white rounded-xl border border-slate-200/80 p-3 shadow-xs hover:shadow-md transition-all flex flex-col justify-between relative">
                        
                        {{-- Cover Image Container (Ratio ~2:3) --}}
                        <div class="relative w-full aspect-[2/3] rounded-lg overflow-hidden bg-slate-100 border border-slate-100 flex items-center justify-center mb-2.5">
                            @if($book->cover_url)
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="p-3 text-center flex flex-col items-center justify-center h-full w-full bg-slate-100 text-slate-400">
                                    <i class="bi bi-book text-3xl mb-1 text-slate-300"></i>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase line-clamp-2 leading-tight">{{ $book->title }}</span>
                                </div>
                            @endif

                            {{-- Career Badge (Top Left) --}}
                            <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                                {{ $book->studyProgram ? Str::limit($book->studyProgram->name, 12) : 'General' }}
                            </span>

                            {{-- Active/Inactive indicator --}}
                            @if(!$book->is_active)
                                <span class="absolute bottom-1.5 left-1.5 px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-amber-500 text-white">
                                    Inactivo
                                </span>
                            @endif
                        </div>

                        {{-- Metadata --}}
                        <div class="space-y-1">
                            {{-- Rating Stars (Matching Screenshot 1) --}}
                            <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>

                            {{-- Title --}}
                            <h3 class="text-xs font-bold text-slate-900 line-clamp-1 leading-snug group-hover:text-teal-800 transition" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h3>

                            {{-- Author --}}
                            <p class="text-[11px] text-slate-500 line-clamp-1 leading-none" title="{{ $book->author }}">
                                {{ $book->author }}
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-2.5 mt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                            <a href="{{ route('biblioteca.read', $book->slug) }}" target="_blank"
                                class="text-teal-700 hover:text-teal-900 font-bold text-[11px] inline-flex items-center gap-1"
                                title="Leer documento">
                                <i class="bi bi-eye"></i> Ver
                            </a>

                            <div class="flex items-center gap-1.5">
                                <button type="button" @click="openEditModal({{ $book->id }})"
                                    class="text-slate-400 hover:text-indigo-600 p-1 rounded" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                
                                <form action="{{ route('admin.library.destroy', $book->id) }}" method="POST"
                                    onsubmit="return confirm('¿Está seguro de eliminar este documento del catálogo?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 p-1 rounded" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pt-4">
                {{ $books->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center space-y-3">
                <div class="w-12 h-12 mx-auto rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl">
                    <i class="bi bi-journal-x"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800">No se encontraron libros en esta selección</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    Intente cambiando el filtro de carrera o los términos de búsqueda, o agregue nuevos contenidos al catálogo.
                </p>
                <button type="button" @click="openCreateModal()"
                    class="px-4 py-2 bg-[#0b4d57] text-white text-xs font-bold rounded-xl shadow-xs inline-flex items-center gap-2">
                    <i class="bi bi-plus-lg"></i> Agregar Nuevo Libro
                </button>
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- CONTENT: LIST VIEW                                                 --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div x-show="currentView === 'list'" style="display: none;">
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Documento</th>
                            <th class="py-3 px-4">Carrera / Área</th>
                            <th class="py-3 px-4">Tipo</th>
                            <th class="py-3 px-4 text-center">Lecturas</th>
                            <th class="py-3 px-4 text-center">Estado</th>
                            <th class="py-3 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($books as $book)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4 flex items-center gap-3">
                                    <div class="w-8 h-11 bg-slate-100 rounded overflow-hidden flex-shrink-0 border border-slate-200">
                                        @if($book->cover_url)
                                            <img src="{{ $book->cover_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <i class="bi bi-book"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900">{{ $book->title }}</h4>
                                        <p class="text-[11px] text-slate-500">{{ $book->author }}</p>
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-medium text-slate-700">
                                    {{ $book->studyProgram ? $book->studyProgram->name : 'General (Todas)' }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ $book->category }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center font-bold text-slate-800">
                                    {{ number_format($book->views_count) }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $book->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $book->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right space-x-2">
                                    <a href="{{ route('biblioteca.read', $book->slug) }}" target="_blank"
                                        class="p-1.5 text-teal-700 hover:bg-teal-50 rounded-lg inline-block" title="Visualizar">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" @click="openEditModal({{ $book->id }})"
                                        class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('admin.library.modal-book-form')
    @include('admin.library.modal-book-edit')

</div>
@endsection

@push('scripts')
<script>
    function assignedBooksApp() {
        return {
            currentView: '{{ $viewMode }}',
            createSelectedPdfName: '',
            editActionUrl: '',
            editForm: {
                id: null,
                title: '',
                author: '',
                study_program_id: '',
                category: 'Libro',
                description: '',
                publisher: '',
                publication_year: '',
                edition: '',
                pages: '',
                isbn: '',
                language: 'Español',
                external_url: '',
                rating: 5.0,
                is_active: true,
                cover_url: '',
                file_url: '',
                file_size: '',
            },

            handleCareerFilter(careerId) {
                const url = new URL(window.location.href);
                if (careerId === 'all') {
                    url.searchParams.delete('career');
                } else {
                    url.searchParams.set('career', careerId);
                }
                url.searchParams.delete('page');
                window.location.href = url.toString();
            },

            handleSearch(term) {
                const url = new URL(window.location.href);
                if (term) {
                    url.searchParams.set('q', term);
                } else {
                    url.searchParams.delete('q');
                }
                url.searchParams.delete('page');
                window.location.href = url.toString();
            },

            openCreateModal() {
                this.createSelectedPdfName = '';
                this.isSubmitting = false;
                this.showCreateModal = true;
            },

            async openEditModal(bookId) {
                this.isSubmitting = false;
                try {
                    const res = await fetch(`/admin-biblioteca/editar/${bookId}`);
                    const json = await res.json();
                    if (json.success && json.data) {
                        this.editForm = json.data;
                        this.editActionUrl = `/admin-biblioteca/editar/${bookId}`;
                        this.showEditModal = true;
                    }
                } catch (e) {
                    alert('No se pudo cargar la información del libro.');
                    console.error(e);
                }
            }
        };
    }
</script>
@endpush
