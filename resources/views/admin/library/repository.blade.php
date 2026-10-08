@extends('admin.library.layout')

@section('title', 'Repositorio de Contenidos - Biblioteca')

@section('library_content')
<div class="p-5 sm:p-7 space-y-6" x-data="repositoryApp()">

    {{-- Header --}}
    <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="bi bi-folder2-open text-teal-800"></i> Repositorio de Contenidos
                </h1>
                <p class="text-xs text-slate-500">Gestión integral, actualización y mantenimiento del acervo bibliográfico institucional.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" @click="openAcademicModal()"
                    class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-2">
                    <i class="bi bi-journal-arrow-down"></i>
                    <span>Búsqueda Masiva de Revistas / Papers</span>
                </button>
                <button type="button" @click="openCreateModal()"
                    class="px-4 py-2 bg-[#0b4d57] hover:bg-teal-900 text-white font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-2">
                    <i class="bi bi-cloud-arrow-up-fill"></i>
                    <span>Subir Nuevo Contenido</span>
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3 border-t border-slate-100">
            {{-- Career --}}
            <div>
                <select @change="filter('career', $event.target.value)"
                    class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-700">
                    <option value="all" {{ $selectedCareer === 'all' ? 'selected' : '' }}>Todas las carreras</option>
                    @foreach($studyPrograms as $prog)
                        <option value="{{ $prog->id }}" {{ (string)$selectedCareer === (string)$prog->id ? 'selected' : '' }}>
                            {{ $prog->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Category --}}
            <div>
                <select @change="filter('category', $event.target.value)"
                    class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-700">
                    <option value="all" {{ $selectedCategory === 'all' ? 'selected' : '' }}>Todos los tipos (Libros, Papers...)</option>
                    <option value="Libro" {{ $selectedCategory === 'Libro' ? 'selected' : '' }}>Libros</option>
                    <option value="Revista" {{ $selectedCategory === 'Revista' ? 'selected' : '' }}>Revistas</option>
                    <option value="Paper" {{ $selectedCategory === 'Paper' ? 'selected' : '' }}>Papers / Artículos</option>
                    <option value="Tesis" {{ $selectedCategory === 'Tesis' ? 'selected' : '' }}>Tesis</option>
                    <option value="Documento" {{ $selectedCategory === 'Documento' ? 'selected' : '' }}>Documentos</option>
                </select>
            </div>

            {{-- Search --}}
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" placeholder="Buscar título o autor..." value="{{ $searchQuery }}"
                    @keydown.enter="filter('q', $event.target.value)"
                    class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
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

    {{-- Repository Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Material</th>
                        <th class="py-3.5 px-4">Carrera Asignada</th>
                        <th class="py-3.5 px-4">Formato / Origen</th>
                        <th class="py-3.5 px-4 text-center">Lecturas</th>
                        <th class="py-3.5 px-4 text-center">Estado</th>
                        <th class="py-3.5 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($books as $book)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 flex items-center gap-3">
                                <div class="w-10 h-14 bg-slate-100 rounded-md overflow-hidden flex-shrink-0 border border-slate-200">
                                    @if($book->cover_url)
                                        <img src="{{ $book->cover_url }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <i class="bi bi-book text-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 truncate" title="{{ $book->title }}">{{ $book->title }}</h4>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $book->author }}</p>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-0.5">
                                        <span class="px-1.5 py-0.2 bg-slate-100 rounded text-slate-600 font-semibold">{{ $book->category }}</span>
                                        @if($book->publication_year)
                                            <span>{{ $book->publication_year }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-3 px-4 font-semibold text-slate-700">
                                {{ $book->studyProgram ? $book->studyProgram->name : 'General (Todas)' }}
                            </td>

                            <td class="py-3 px-4">
                                @if($book->is_external)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200" title="{{ $book->external_url }}">
                                        <i class="bi bi-link-45deg"></i> Enlace Web
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                        <i class="bi bi-file-earmark-pdf"></i> {{ $book->formatted_file_size }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-3 px-4 text-center font-bold text-slate-800">
                                <span class="inline-flex items-center gap-1">
                                    <i class="bi bi-eye text-slate-400"></i> {{ number_format($book->views_count) }}
                                </span>
                            </td>

                            <td class="py-3 px-4 text-center">
                                <form action="{{ route('admin.library.toggle-status', $book->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold transition {{ $book->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        {{ $book->is_active ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </form>
                            </td>

                            <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                                {{-- In-site Iframe viewer modal button --}}
                                <button type="button"
                                    @click="openIframePreviewModal({
                                        title: @js($book->title),
                                        author: @js($book->author),
                                        category: @js($book->category),
                                        publisher: @js($book->publisher),
                                        program_name: @js($book->studyProgram ? $book->studyProgram->name : 'General'),
                                        url: @js($book->is_external && $book->external_url ? $book->external_url : route('biblioteca.read', $book->slug))
                                    })"
                                    class="p-1.5 text-teal-700 hover:bg-teal-50 rounded-lg inline-block"
                                    title="Visor Integrado (Iframe)">
                                    <i class="bi bi-window-fullscreen"></i>
                                </button>
                                <a href="{{ route('biblioteca.read', $book->slug) }}" target="_blank"
                                    class="p-1.5 text-slate-500 hover:bg-slate-100 rounded-lg inline-block" title="Visualizar en Lector Web">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                                <button type="button" @click="openEditModal({{ $book->id }})"
                                    class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.library.destroy', $book->id) }}" method="POST"
                                    onsubmit="return confirm('¿Está seguro de eliminar este contenido?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">
                                No se encontraron documentos registrados en el repositorio.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $books->links() }}
        </div>
    </div>

    {{-- Modals --}}
    @include('admin.library.modal-book-form')
    @include('admin.library.modal-book-edit')
    @include('admin.library.modal-academic-import')
    @include('admin.library.modal-iframe-preview')

</div>
@endsection

@push('scripts')
<script>
    function repositoryApp() {
        return {
            showCreateModal: false,
            showEditModal: false,
            showAcademicModal: false,
            showIframeModal: false,
            importingAcademic: false,
            importResults: null,
            createSelectedPdfName: '',
            editActionUrl: '',
            isSubmitting: false,

            importForm: {
                study_program_id: 'all',
                category: 'all',
                limit: 10,
                sources: ['scielo', 'redalyc', 'doaj', 'crossref'],
                custom_query: ''
            },

            previewData: {
                title: '',
                author: '',
                category: '',
                publisher: '',
                program_name: '',
                url: ''
            },

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

            openCreateModal() {
                this.createSelectedPdfName = '';
                this.isSubmitting = false;
                this.showCreateModal = true;
            },

            openAcademicModal() {
                this.importResults = null;
                this.importingAcademic = false;
                this.showAcademicModal = true;
            },

            resetAcademicForm() {
                this.importResults = null;
                this.importingAcademic = false;
            },

            async runAcademicImport() {
                if (this.importingAcademic) return;
                this.importingAcademic = true;
                this.importResults = null;

                try {
                    const response = await fetch("{{ route('admin.library.fetch-academic') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.importForm)
                    });

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        alert(data.message || 'Error al buscar e importar publicaciones académicas.');
                        this.importingAcademic = false;
                        return;
                    }

                    this.importResults = data;
                } catch (error) {
                    console.error('Error importing academic resources:', error);
                    alert('Ocurrió un error en la conexión al buscar o importar recursos.');
                } finally {
                    this.importingAcademic = false;
                }
            },

            openIframePreviewModal(book) {
                this.previewData = {
                    title: book.title || 'Documento',
                    author: book.author || 'Autor no especificado',
                    category: book.category || 'Paper',
                    publisher: book.publisher || '',
                    program_name: book.program_name || 'General',
                    url: book.external_url || book.url || ''
                };
                this.showIframeModal = true;
            },

            closeIframePreviewModal() {
                this.showIframeModal = false;
                const iframe = document.getElementById('adminModalIframe');
                if (iframe) {
                    iframe.src = 'about:blank';
                }
            },

            toggleModalFullscreen() {
                const elem = document.getElementById('modalIframeContainer') || document.documentElement;
                if (!document.fullscreenElement) {
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.webkitRequestFullscreen) {
                        elem.webkitRequestFullscreen();
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
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
                }
            }
        };
    }
</script>
@endpush
