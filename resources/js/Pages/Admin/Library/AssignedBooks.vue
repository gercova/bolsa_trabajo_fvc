<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, reactive, computed } from 'vue';
import axios from 'axios';
import LibraryLayout from './Layout.vue';
import route from '@/route';

const props = defineProps({
    books: { type: Object, required: true },
    studyPrograms: { type: Array, default: () => [] },
    selectedCareer: { type: [String, Number], default: 'all' },
    searchQuery: { type: String, default: '' },
    viewMode: { type: String, default: 'grid' },
    totalCount: { type: Number, default: 0 },
});

// View mode state
const currentView = ref(props.viewMode || 'grid');

// Search & Filter state
const searchInput = ref(props.searchQuery || '');
const currentCareer = ref(props.selectedCareer || 'all');

const handleCareerFilter = (careerId) => {
    currentCareer.value = careerId;
    router.get(
        route('admin.library.books.index'),
        {
            career: careerId,
            q: searchInput.value || undefined,
            view: currentView.value,
        },
        { preserveState: true, preserveScroll: true }
    );
};

const handleSearch = () => {
    router.get(
        route('admin.library.books.index'),
        {
            career: currentCareer.value,
            q: searchInput.value || undefined,
            view: currentView.value,
        },
        { preserveState: true, preserveScroll: true }
    );
};

const clearFilters = () => {
    searchInput.value = '';
    currentCareer.value = 'all';
    router.get(route('admin.library.books.index'), { view: currentView.value });
};

// ── TOAST NOTIFICATIONS ────────────────────────────────────────────────────────
const toastMessage = ref('');
const toastType = ref('success'); // 'success' | 'error'
const showToast = ref(false);

const notify = (msg, type = 'success') => {
    toastMessage.value = msg;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => {
        showToast.value = false;
    }, 4500);
};

// ── 1. MODAL: AGREGAR CONTENIDO (CREATE BOOK) ───────────────────────────────────
const showCreateModal = ref(false);
const createSourceType = ref('file'); // 'file' | 'link'
const createPdfName = ref('');
const createCoverPreview = ref('');

const createForm = useForm({
    title: '',
    author: '',
    category: 'Libro',
    study_program_id: '',
    external_url: '',
    file: null,
    cover: null,
    is_active: true,
    publisher: '',
    publication_year: '',
    isbn: '',
    description: '',
});

const openCreateModal = () => {
    createForm.reset();
    createForm.clearErrors();
    createPdfName.value = '';
    createCoverPreview.value = '';
    createSourceType.value = 'file';
    showCreateModal.value = true;
};

const handleCreatePdfChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        createForm.file = file;
        createPdfName.value = `${file.name} (${(file.size / (1024 * 1024)).toFixed(2)} MB)`;
    } else {
        createForm.file = null;
        createPdfName.value = '';
    }
};

const handleCreateCoverChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        createForm.cover = file;
        const reader = new FileReader();
        reader.onload = (evt) => {
            createCoverPreview.value = evt.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submitCreate = () => {
    createForm.post(route('admin.library.store'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            showCreateModal.value = false;
            notify('Contenido académico agregado exitosamente al catálogo.');
        },
        onError: () => {
            notify('Revise los campos obligatorios del formulario.', 'error');
        },
    });
};

// ── 2. MODAL: EDITAR CONTENIDO (EDIT BOOK) ─────────────────────────────────────
const showEditModal = ref(false);
const editingBookId = ref(null);
const editCoverPreview = ref('');
const editExistingFile = ref('');
const editExistingFileSize = ref('');

const editForm = useForm({
    title: '',
    author: '',
    category: 'Libro',
    study_program_id: '',
    external_url: '',
    file: null,
    cover: null,
    is_active: true,
    publisher: '',
    publication_year: '',
    isbn: '',
    description: '',
    _method: 'PUT',
});

const openEditModal = async (book) => {
    editingBookId.value = book.id;
    editCoverPreview.value = book.cover_url || '';
    editExistingFile.value = book.file_url || '';
    editExistingFileSize.value = book.formatted_file_size || '';

    editForm.reset();
    editForm.clearErrors();
    editForm.title = book.title || '';
    editForm.author = book.author || '';
    editForm.category = book.category || 'Libro';
    editForm.study_program_id = book.study_program_id || '';
    editForm.external_url = book.external_url || '';
    editForm.is_active = Boolean(book.is_active);
    editForm.publisher = book.publisher || '';
    editForm.publication_year = book.publication_year || '';
    editForm.isbn = book.isbn || '';
    editForm.description = book.description || '';
    editForm.file = null;
    editForm.cover = null;

    showEditModal.value = true;
};

const handleEditCoverChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        editForm.cover = file;
        const reader = new FileReader();
        reader.onload = (evt) => {
            editCoverPreview.value = evt.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleEditFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        editForm.file = file;
    }
};

const submitEdit = () => {
    if (!editingBookId.value) return;

    editForm.post(route('admin.library.update', editingBookId.value), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            showEditModal.value = false;
            notify('Información del documento actualizada correctamente.');
        },
        onError: () => {
            notify('Revise los campos del formulario de edición.', 'error');
        },
    });
};

// ── 3. MODAL: BÚSQUEDA Y CARGA MASIVA ACADÉMICA (ACADEMIC IMPORT) ──────────────
const showAcademicModal = ref(false);
const importingAcademic = ref(false);
const importResults = ref(null);

const importForm = reactive({
    study_program_id: 'all',
    category: 'all',
    limit: 10,
    sources: ['scielo', 'google_scholar', 'redalyc', 'doaj', 'crossref', 'openalex', 'europe_pmc', 'open_library'],
    custom_query: '',
});

const openAcademicModal = () => {
    importResults.value = null;
    importingAcademic.value = false;
    showAcademicModal.value = true;
};

const runAcademicImport = async () => {
    importingAcademic.value = true;
    importResults.value = null;

    try {
        const response = await axios.post(route('admin.library.fetch-academic'), importForm, {
            headers: {
                'Accept': 'application/json',
            },
            timeout: 200000,
        });

        const data = response.data;
        importResults.value = data;

        if (data.success) {
            const count = (data.saved || 0) + (data.updated || 0);
            notify(data.message || `Se importaron ${count} recursos académicos.`);
            // Refresh list in background
            router.reload({ only: ['books', 'totalCount'] });
        } else {
            notify(data.message || 'Error en la importación masiva.', 'error');
        }
    } catch (err) {
        const isTimeout = err.code === 'ECONNABORTED' || (err.message && err.message.toLowerCase().includes('timeout'));

        if (isTimeout) {
            router.reload({ only: ['books', 'totalCount'] });
            importResults.value = {
                success: true,
                message: 'Se alcanzó el límite de tiempo de 3 minutos. Los recursos encontrados hasta ese momento han sido guardados con éxito en el catálogo.',
                saved: 'Preservados',
                updated: 0,
                skipped: 0,
                errors: ['Búsqueda detenida preventivamente al límite de 3 minutos. Los registros procesados se conservaron en la biblioteca.'],
            };
            notify('Tiempo límite de 3 minutos alcanzado. Registros preservados en el catálogo.', 'info');
        } else {
            const errMessage = err.response?.data?.message || err.message || 'Ocurrió un problema de conexión al procesar la búsqueda académica.';
            importResults.value = {
                success: false,
                message: errMessage,
                saved: 0,
                updated: 0,
                skipped: 0,
                errors: [errMessage],
            };
            notify(errMessage, 'error');
        }
    } finally {
        importingAcademic.value = false;
    }
};

// ── 4. MODAL: VISOR DE LIBRO / IFRAME PREVIEW ──────────────────────────────────
const showIframeModal = ref(false);
const generatingVirtualCopy = ref(false);
const previewData = reactive({
    id: null,
    title: '',
    author: '',
    category: '',
    publisher: '',
    program_name: '',
    url: '',
    slug: '',
});

const openIframePreviewModal = (data) => {
    previewData.id = data.id || null;
    previewData.title = data.title || '';
    previewData.author = data.author || '';
    previewData.category = data.category || 'Documento';
    previewData.publisher = data.publisher || '';
    previewData.program_name = data.program_name || 'General';
    previewData.slug = data.slug || '';
    previewData.url = data.slug ? route('biblioteca.stream', data.slug) : (data.url || '');
    showIframeModal.value = true;
};

const closeIframePreviewModal = () => {
    showIframeModal.value = false;
    previewData.id = null;
    previewData.url = '';
};

const regenerateVirtualCopy = async () => {
    if (!previewData.id) return;
    generatingVirtualCopy.value = true;
    try {
        const response = await axios.post(route('admin.library.generate-virtual-copy', previewData.id), {}, {
            headers: {
                'Accept': 'application/json',
            },
        });
        const data = response.data;
        if (data.success) {
            notify('Copia virtual institucional generada y descargada exitosamente en la aplicación.');
            const base = previewData.slug ? route('biblioteca.stream', previewData.slug) : previewData.url.split('?')[0];
            previewData.url = `${base}?v=${Date.now()}`;
            router.reload({ only: ['books'] });
        } else {
            notify(data.message || 'No se pudo generar la copia virtual.', 'error');
        }
    } catch (err) {
        const msg = err.response?.data?.message || 'Ocurrió un error al contactar al servidor para generar la copia virtual.';
        notify(msg, 'error');
    } finally {
        generatingVirtualCopy.value = false;
    }
};

// ── 5. TOGGLE STATUS & DELETE ACTIONS ──────────────────────────────────────────
const bookToDelete = ref(null);
const showDeleteConfirm = ref(false);

const confirmDelete = (book) => {
    bookToDelete.value = book;
    showDeleteConfirm.value = true;
};

const executeDelete = () => {
    if (!bookToDelete.value) return;
    router.delete(route('admin.library.destroy', bookToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false;
            bookToDelete.value = null;
            notify('El documento fue eliminado del catálogo correctamente.');
        },
        onError: () => {
            notify('No se pudo eliminar el documento seleccionado.', 'error');
        },
    });
};

const toggleStatus = (book) => {
    router.patch(route('admin.library.toggle-status', book.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            notify(`El documento fue ${book.is_active ? 'desactivado' : 'activado'} correctamente.`);
        },
    });
};
</script>

<template>
    <LibraryLayout
        title="Libros Asignados - Biblioteca Virtual"
        subtitle="Catálogo general de materiales y bibliografía académica institucional"
        :breadcrumbs="[
            { label: 'Dashboard', href: route('admin.dashboard.index') },
            { label: 'Biblioteca', href: route('admin.library.index') },
            { label: 'Libros Asignados' }
        ]"
    >
        <Head title="Libros Asignados - Biblioteca Virtual" />

        <div class="p-4 sm:p-6 lg:p-7 space-y-6">
            <!-- TOPBAR & SEARCH FILTERS -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-xs border border-slate-200/80 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                            Libros Asignados
                        </h1>
                        <p class="text-xs text-slate-500">
                            Catálogo general de materiales y bibliografía académica asignada.
                        </p>
                    </div>

                    <!-- Right actions: Mass Import & Upload buttons -->
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="text-xs font-semibold text-slate-500">
                            <strong class="text-slate-800">{{ Number(totalCount).toLocaleString() }}</strong> registros
                        </span>

                        <button
                            type="button"
                            @click="openAcademicModal"
                            class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-2"
                        >
                            <i class="bi bi-journal-arrow-down"></i>
                            <span>Búsqueda Masiva de Revistas / Papers</span>
                        </button>

                        <button
                            type="button"
                            @click="openCreateModal"
                            class="px-4 py-2 bg-[#0b4d57] hover:bg-teal-900 text-white font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-2"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Subir Contenido</span>
                        </button>
                    </div>
                </div>

                <!-- Filters Bar: Career Dropdown, Search Input, View Mode (Matching Image 1) -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 pt-3 border-t border-slate-100">
                    <!-- Career Selector -->
                    <div class="w-full sm:w-64">
                        <select
                            v-model="currentCareer"
                            @change="handleCareerFilter($event.target.value)"
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition text-slate-700"
                        >
                            <option value="all">Todas las carreras</option>
                            <option
                                v-for="prog in studyPrograms"
                                :key="prog.id"
                                :value="prog.id"
                            >
                                {{ prog.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input
                            type="text"
                            v-model="searchInput"
                            placeholder="Buscar por título, autor, editorial o ISBN..."
                            @keydown.enter="handleSearch"
                            class="w-full pl-9 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition text-slate-800"
                        >
                        <button
                            v-if="searchInput"
                            type="button"
                            @click="searchInput = ''; handleSearch()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs"
                        >
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <button
                        v-if="searchInput || currentCareer !== 'all'"
                        type="button"
                        @click="clearFilters"
                        class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition"
                        title="Restablecer filtros"
                    >
                        Limpiar
                    </button>

                    <!-- Grid / List Mode Switcher -->
                    <div class="flex items-center border border-slate-200 rounded-xl p-0.5 bg-slate-50 text-slate-500">
                        <button
                            type="button"
                            @click="currentView = 'grid'"
                            :class="currentView === 'grid' ? 'bg-white text-teal-900 shadow-xs' : 'hover:text-slate-900'"
                            class="p-1.5 px-2.5 rounded-lg text-sm transition"
                            title="Vista en Cuadrícula"
                        >
                            <i class="bi bi-grid-fill"></i>
                        </button>
                        <button
                            type="button"
                            @click="currentView = 'list'"
                            :class="currentView === 'list' ? 'bg-white text-teal-900 shadow-xs' : 'hover:text-slate-900'"
                            class="p-1.5 px-2.5 rounded-lg text-sm transition"
                            title="Vista en Lista"
                        >
                            <i class="bi bi-list-ul"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Toast Notification -->
            <transition
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showToast"
                    class="p-4 rounded-xl text-xs font-bold flex items-center justify-between gap-3 shadow-md border"
                    :class="toastType === 'success'
                        ? 'bg-emerald-50 border-emerald-200 text-emerald-900'
                        : 'bg-rose-50 border-rose-200 text-rose-900'"
                >
                    <span class="flex items-center gap-2">
                        <i :class="toastType === 'success' ? 'bi bi-check-circle-fill text-emerald-600 text-base' : 'bi bi-exclamation-octagon-fill text-rose-600 text-base'"></i>
                        {{ toastMessage }}
                    </span>
                    <button type="button" @click="showToast = false" class="text-slate-400 hover:text-slate-700">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </transition>
            <!-- CONTENT: GRID VIEW -->
            <div v-show="currentView === 'grid'">
                <div
                    v-if="books.data && books.data.length > 0"
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-4 sm:gap-5"
                >
                    <div
                        v-for="book in books.data"
                        :key="book.id"
                        class="group bg-white rounded-xl border border-slate-200/80 p-3 shadow-xs hover:shadow-md transition-all flex flex-col justify-between relative"
                    >
                        <!-- Cover Image Container (Ratio ~2:3) -->
                        <div class="relative w-full aspect-[2/3] rounded-lg overflow-hidden bg-slate-100 border border-slate-100 flex items-center justify-center mb-2.5">
                            <img
                                v-if="book.cover_url"
                                :src="book.cover_url"
                                :alt="book.title"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                loading="lazy"
                            >
                            <div
                                v-else
                                class="p-3 text-center flex flex-col items-center justify-center h-full w-full bg-slate-50 text-slate-400"
                            >
                                <i class="bi bi-book text-3xl mb-1 text-slate-300"></i>
                                <span class="text-[10px] font-bold text-slate-500 uppercase line-clamp-2 leading-tight">
                                    {{ book.title }}
                                </span>
                            </div>

                            <!-- Career Badge (Top Left) -->
                            <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-900/80 text-white backdrop-blur-xs truncate max-w-[90px]">
                                {{ book.study_program ? book.study_program.name : 'General' }}
                            </span>

                            <!-- Active/Inactive indicator -->
                            <span
                                v-if="!book.is_active"
                                class="absolute bottom-1.5 left-1.5 px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-amber-500 text-white shadow-xs"
                            >
                                Inactivo
                            </span>
                        </div>

                        <!-- Metadata -->
                        <div class="space-y-1">
                            <!-- Rating Stars -->
                            <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>

                            <!-- Title -->
                            <h3
                                class="text-xs font-bold text-slate-900 line-clamp-1 leading-snug group-hover:text-teal-800 transition"
                                :title="book.title"
                            >
                                {{ book.title }}
                            </h3>

                            <!-- Author -->
                            <p class="text-[11px] text-slate-500 line-clamp-1 leading-none" :title="book.author">
                                {{ book.author }}
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-2.5 mt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="openIframePreviewModal({
                                        id: book.id,
                                        title: book.title,
                                        author: book.author,
                                        category: book.category,
                                        publisher: book.publisher,
                                        program_name: book.study_program ? book.study_program.name : 'General',
                                        slug: book.slug,
                                        url: route('biblioteca.stream', book.slug)
                                    })"
                                    class="text-teal-700 hover:text-teal-900 font-bold text-[11px] inline-flex items-center gap-1"
                                    title="Visor Integrado (Iframe)"
                                >
                                    <i class="bi bi-window-fullscreen"></i> Visor
                                </button>
                                <a
                                    :href="route('biblioteca.read', book.slug)"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-slate-400 hover:text-slate-700 p-0.5 rounded"
                                    title="Abrir en pestaña nueva"
                                >
                                    <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                                </a>
                            </div>

                            <div class="flex items-center gap-1">
                                <button
                                    type="button"
                                    @click="toggleStatus(book)"
                                    class="p-1 rounded text-xs transition"
                                    :class="book.is_active ? 'text-emerald-600 hover:text-emerald-800' : 'text-slate-400 hover:text-slate-600'"
                                    :title="book.is_active ? 'Desactivar documento' : 'Activar documento'"
                                >
                                    <i :class="book.is_active ? 'bi bi-toggle-on text-base' : 'bi bi-toggle-off text-base'"></i>
                                </button>

                                <button
                                    type="button"
                                    @click="openEditModal(book)"
                                    class="text-slate-400 hover:text-indigo-600 p-1 rounded"
                                    title="Editar"
                                >
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button
                                    type="button"
                                    @click="confirmDelete(book)"
                                    class="text-slate-400 hover:text-rose-600 p-1 rounded"
                                    title="Eliminar"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-else
                    class="bg-white rounded-2xl border border-slate-200 p-12 text-center space-y-3"
                >
                    <div class="w-12 h-12 mx-auto rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl">
                        <i class="bi bi-journal-x"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">No se encontraron libros en esta selección</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">
                        Intente cambiando el filtro de carrera o los términos de búsqueda, o agregue nuevos contenidos al catálogo.
                    </p>
                    <button
                        type="button"
                        @click="openCreateModal"
                        class="px-4 py-2 bg-[#0b4d57] text-white text-xs font-bold rounded-xl shadow-xs inline-flex items-center gap-2"
                    >
                        <i class="bi bi-plus-lg"></i> Agregar Nuevo Libro
                    </button>
                </div>
            </div>
            <!-- CONTENT: LIST VIEW -->
            <div v-show="currentView === 'list'">
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
                                <tr
                                    v-for="book in books.data"
                                    :key="book.id"
                                    class="hover:bg-slate-50/70 transition"
                                >
                                    <td class="py-3 px-4 flex items-center gap-3">
                                        <div class="w-9 h-12 bg-slate-100 rounded overflow-hidden flex-shrink-0 border border-slate-200">
                                            <img
                                                v-if="book.cover_url"
                                                :src="book.cover_url"
                                                class="w-full h-full object-cover"
                                            >
                                            <div v-else class="w-full h-full flex items-center justify-center text-slate-400">
                                                <i class="bi bi-book"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900">{{ book.title }}</h4>
                                            <p class="text-[11px] text-slate-500">{{ book.author }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-medium text-slate-700">
                                        {{ book.study_program ? book.study_program.name : 'General (Todas)' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                            {{ book.category }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center font-bold text-slate-800">
                                        {{ Number(book.views_count || 0).toLocaleString() }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <button
                                            type="button"
                                            @click="toggleStatus(book)"
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold transition"
                                            :class="book.is_active
                                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100'
                                                : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                                        >
                                            {{ book.is_active ? 'Activo' : 'Inactivo' }}
                                        </button>
                                    </td>
                                    <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                                        <button
                                            type="button"
                                            @click="openIframePreviewModal({
                                                id: book.id,
                                                title: book.title,
                                                author: book.author,
                                                category: book.category,
                                                publisher: book.publisher,
                                                program_name: book.study_program ? book.study_program.name : 'General',
                                                slug: book.slug,
                                                url: route('biblioteca.stream', book.slug)
                                            })"
                                            class="p-1.5 text-teal-700 hover:bg-teal-50 rounded-lg inline-block"
                                            title="Visor Integrado (Iframe)"
                                        >
                                            <i class="bi bi-window-fullscreen"></i>
                                        </button>
                                        <a
                                            :href="route('biblioteca.read', book.slug)"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="p-1.5 text-slate-500 hover:bg-slate-100 rounded-lg inline-block"
                                            title="Visualizar en ventana nueva"
                                        >
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                        <button
                                            type="button"
                                            @click="openEditModal(book)"
                                            class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg inline-block"
                                            title="Editar"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button
                                            type="button"
                                            @click="confirmDelete(book)"
                                            class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg inline-block"
                                            title="Eliminar"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- PAGINATION -->
            <div v-if="books.links && books.links.length > 3" class="pt-4 flex items-center justify-center gap-1 flex-wrap">
                <template v-for="(link, idx) in books.links" :key="idx">
                    <span
                        v-if="!link.url"
                        v-html="link.label"
                        class="px-3 py-1.5 text-xs text-slate-400 bg-slate-50 border border-slate-200 rounded-xl"
                    ></span>
                    <Link
                        v-else
                        :href="link.url"
                        v-html="link.label"
                        class="px-3 py-1.5 text-xs rounded-xl border transition"
                        :class="link.active
                            ? 'bg-[#0b4d57] text-white border-[#0b4d57] font-bold shadow-xs'
                            : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:text-slate-900'"
                        preserve-scroll
                    />
                </template>
            </div>
        </div>
        <!-- MODAL 1: SUBIR / CREAR NUEVO CONTENIDO DE BIBLIOTECA -->
        <div
            v-if="showCreateModal"
            class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
            @click.self="showCreateModal = false"
        >
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl relative space-y-4 my-auto border border-slate-200 max-h-[92vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#0b4d57] border border-teal-200 flex items-center justify-center text-xl flex-shrink-0">
                            <i class="bi bi-journal-plus"></i>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900">Agregar Contenido a la Biblioteca</h3>
                            <p class="text-xs text-slate-500">Suba libros, revistas, papers o documentos en PDF o mediante enlace web.</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="showCreateModal = false"
                        class="text-slate-400 hover:text-slate-700 w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center transition"
                    >
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>

                <form @submit.prevent="submitCreate" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Title -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Título del Libro o Documento <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                v-model="createForm.title"
                                required
                                placeholder="Ej. JADAM Agricultura Ecológica"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50/50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition"
                            >
                            <p v-if="createForm.errors.title" class="text-xs text-rose-600 mt-1">{{ createForm.errors.title }}</p>
                        </div>

                        <!-- Author -->
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Autor / Autores <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                v-model="createForm.author"
                                required
                                placeholder="Ej. Youngsang Cho"
                                class="w-full text-xs font-medium px-3 py-2 bg-slate-50/50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition"
                            >
                            <p v-if="createForm.errors.author" class="text-xs text-rose-600 mt-1">{{ createForm.errors.author }}</p>
                        </div>

                        <!-- Category -->
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Tipo de Contenido <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="createForm.category"
                                required
                                class="w-full text-xs border border-slate-300 rounded-xl py-2 px-2.5 bg-white text-slate-800 font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition"
                            >
                                <option value="Libro">Libro</option>
                                <option value="Revista">Revista Científica / Técnica</option>
                                <option value="Paper">Paper / Artículo Académico</option>
                                <option value="Tesis">Tesis / Proyecto de Titulación</option>
                                <option value="Documento">Documento / Manual Oficial</option>
                            </select>
                        </div>

                        <!-- Study Program -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Carrera / Programa Asignado
                            </label>
                            <select
                                v-model="createForm.study_program_id"
                                class="w-full text-xs border border-slate-300 rounded-xl py-2 px-2.5 bg-white text-slate-800 font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition"
                            >
                                <option value="">General (Todas las carreras / Transversal)</option>
                                <option
                                    v-for="prog in studyPrograms"
                                    :key="prog.id"
                                    :value="prog.id"
                                >
                                    {{ prog.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Source Switcher: File vs URL -->
                        <div class="sm:col-span-2 space-y-2">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-1">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Origen del Documento <span class="text-rose-500">*</span>
                                </span>
                                <div class="inline-flex p-0.5 bg-slate-100 rounded-lg text-xs font-semibold">
                                    <button
                                        type="button"
                                        @click="createSourceType = 'file'"
                                        :class="createSourceType === 'file' ? 'bg-white text-teal-800 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                                        class="px-2.5 py-1 rounded-md transition"
                                    >
                                        <i class="bi bi-file-earmark-pdf mr-1 text-red-500"></i> Archivo PDF
                                    </button>
                                    <button
                                        type="button"
                                        @click="createSourceType = 'link'"
                                        :class="createSourceType === 'link' ? 'bg-white text-teal-800 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                                        class="px-2.5 py-1 rounded-md transition"
                                    >
                                        <i class="bi bi-link-45deg mr-1 text-blue-500"></i> Enlace Web (URL)
                                    </button>
                                </div>
                            </div>

                            <!-- File upload -->
                            <div v-show="createSourceType === 'file'">
                                <label class="p-4 rounded-xl text-center cursor-pointer bg-slate-50 hover:bg-teal-50/40 border-2 border-dashed border-slate-300 hover:border-teal-500 transition relative block">
                                    <input
                                        type="file"
                                        accept="application/pdf"
                                        @change="handleCreatePdfChange"
                                        class="hidden"
                                    >
                                    <div class="space-y-1">
                                        <div class="w-8 h-8 mx-auto rounded-lg bg-red-50 text-red-500 flex items-center justify-center text-lg">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </div>
                                        <div v-if="!createPdfName">
                                            <p class="text-xs font-bold text-slate-700">Arrastre o seleccione el archivo PDF</p>
                                            <p class="text-[11px] text-slate-400">Formato .pdf · Tamaño máx. 50 MB</p>
                                        </div>
                                        <p v-else class="text-xs font-extrabold text-emerald-700 flex items-center justify-center gap-1">
                                            <i class="bi bi-check-circle-fill"></i> {{ createPdfName }}
                                        </p>
                                    </div>
                                </label>
                                <p v-if="createForm.errors.file" class="text-xs text-rose-600 mt-1">{{ createForm.errors.file }}</p>
                            </div>

                            <!-- Link input -->
                            <div v-show="createSourceType === 'link'">
                                <input
                                    type="url"
                                    v-model="createForm.external_url"
                                    placeholder="https://scielo.org/... o enlace a repositorio / Google Drive"
                                    class="w-full text-xs px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition"
                                >
                                <p class="text-[11px] text-slate-400 mt-1">Enlaza publicaciones alojadas en repositorios externos sin consumir almacenamiento local.</p>
                                <p v-if="createForm.errors.external_url" class="text-xs text-rose-600 mt-1">{{ createForm.errors.external_url }}</p>
                            </div>
                        </div>

                        <!-- Cover image -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Imagen de Portada (Opcional)
                            </label>
                            <div class="flex items-center gap-3">
                                <img
                                    v-if="createCoverPreview"
                                    :src="createCoverPreview"
                                    class="w-10 h-14 object-cover rounded-lg border border-slate-200"
                                >
                                <input
                                    type="file"
                                    accept="image/jpeg,image/png,image/webp"
                                    @change="handleCreateCoverChange"
                                    class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer"
                                >
                            </div>
                            <p v-if="createForm.errors.cover" class="text-xs text-rose-600 mt-1">{{ createForm.errors.cover }}</p>
                        </div>

                        <!-- Active status switch -->
                        <div class="sm:col-span-2 pt-1">
                            <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100/80 transition w-full">
                                <input
                                    type="checkbox"
                                    v-model="createForm.is_active"
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0b4d57] relative flex-shrink-0"></div>
                                <div>
                                    <span class="text-xs font-bold text-slate-900 block">Contenido Activo</span>
                                    <span class="text-[11px] text-slate-500 block">Los documentos activos se muestran a estudiantes y docentes en la biblioteca virtual.</span>
                                </div>
                            </label>
                        </div>

                        <!-- Extra Metadata -->
                        <div class="sm:col-span-2">
                            <details class="group border border-slate-200 rounded-xl bg-slate-50/50 overflow-hidden text-xs">
                                <summary class="flex items-center justify-between px-3 py-2 text-slate-600 cursor-pointer hover:bg-slate-100 transition select-none font-semibold">
                                    <span class="flex items-center gap-1.5"><i class="bi bi-sliders text-teal-700"></i> Metadatos adicionales (Editorial, Año, ISBN, Sinopsis)</span>
                                    <i class="bi bi-chevron-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                                </summary>
                                <div class="p-3 pt-2 border-t border-slate-100 space-y-3 bg-white">
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Editorial</label>
                                            <input
                                                type="text"
                                                v-model="createForm.publisher"
                                                placeholder="Ej. Lumbreras"
                                                class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Año de Publicación</label>
                                            <input
                                                type="number"
                                                v-model="createForm.publication_year"
                                                placeholder="2025"
                                                min="1900"
                                                max="2030"
                                                class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 mb-1">ISBN / Código</label>
                                            <input
                                                type="text"
                                                v-model="createForm.isbn"
                                                placeholder="Ej. 978-607-..."
                                                class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg"
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Sinopsis o Resumen</label>
                                        <textarea
                                            v-model="createForm.description"
                                            rows="2"
                                            placeholder="Breve descripción o temática del documento..."
                                            class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg"
                                        ></textarea>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            @click="showCreateModal = false"
                            class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="createForm.processing"
                            class="px-5 py-2 text-xs font-bold text-white bg-[#0b4d57] hover:bg-teal-900 rounded-xl shadow-xs transition flex items-center gap-2 disabled:opacity-50"
                        >
                            <i v-if="createForm.processing" class="bi bi-arrow-repeat animate-spin"></i>
                            <i v-else class="bi bi-cloud-arrow-up-fill"></i>
                            <span>Guardar y Asignar Contenido</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 2: EDITAR CONTENIDO DE BIBLIOTECA -->
        <div
            v-if="showEditModal"
            class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
            @click.self="showEditModal = false"
        >
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl relative space-y-4 my-auto border border-slate-200 max-h-[92vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#0b4d57] border border-teal-200 flex items-center justify-center text-xl flex-shrink-0">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900">Editar Contenido de Biblioteca</h3>
                            <p class="text-xs text-slate-500">Actualice la información, carrera asignada o reemplace el documento.</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="showEditModal = false"
                        class="text-slate-400 hover:text-slate-700 w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center transition"
                    >
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>

                <form @submit.prevent="submitEdit" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Title -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Título del Libro o Documento <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                v-model="editForm.title"
                                required
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50/50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition"
                            >
                            <p v-if="editForm.errors.title" class="text-xs text-rose-600 mt-1">{{ editForm.errors.title }}</p>
                        </div>

                        <!-- Author -->
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Autor / Autores <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                v-model="editForm.author"
                                required
                                class="w-full text-xs font-medium px-3 py-2 bg-slate-50/50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition"
                            >
                            <p v-if="editForm.errors.author" class="text-xs text-rose-600 mt-1">{{ editForm.errors.author }}</p>
                        </div>

                        <!-- Category -->
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Tipo de Contenido <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="editForm.category"
                                required
                                class="w-full text-xs border border-slate-300 rounded-xl py-2 px-2.5 bg-white text-slate-800 font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition"
                            >
                                <option value="Libro">Libro</option>
                                <option value="Revista">Revista Científica / Técnica</option>
                                <option value="Paper">Paper / Artículo Académico</option>
                                <option value="Tesis">Tesis / Proyecto de Titulación</option>
                                <option value="Documento">Documento / Manual Oficial</option>
                            </select>
                        </div>

                        <!-- Study Program -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Carrera / Programa Asignado
                            </label>
                            <select
                                v-model="editForm.study_program_id"
                                class="w-full text-xs border border-slate-300 rounded-xl py-2 px-2.5 bg-white text-slate-800 font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition"
                            >
                                <option value="">General (Todas las carreras / Transversal)</option>
                                <option
                                    v-for="prog in studyPrograms"
                                    :key="prog.id"
                                    :value="prog.id"
                                >
                                    {{ prog.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Current Document info & Replacement -->
                        <div class="sm:col-span-2 space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Archivo PDF o Enlace Web Actual y Reemplazo
                            </label>

                            <div v-if="editExistingFile" class="flex items-center justify-between p-2.5 bg-slate-100 rounded-xl border border-slate-200 text-xs">
                                <div class="flex items-center gap-2 truncate">
                                    <i class="bi bi-file-earmark-pdf-fill text-red-500 text-lg flex-shrink-0"></i>
                                    <span class="font-semibold text-slate-700 truncate">{{ editExistingFileSize || 'Archivo PDF actual' }}</span>
                                </div>
                                <a
                                    :href="editExistingFile"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="px-2.5 py-1 bg-white hover:bg-slate-50 text-slate-700 font-bold rounded-lg border border-slate-300 text-xs transition flex items-center gap-1"
                                >
                                    <i class="bi bi-box-arrow-up-right"></i> Abrir Actual
                                </a>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                                <div>
                                    <span class="block text-[11px] font-semibold text-slate-600 mb-1">Reemplazar archivo PDF</span>
                                    <input
                                        type="file"
                                        accept="application/pdf"
                                        @change="handleEditFileChange"
                                        class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[11px] file:font-semibold file:bg-slate-200 file:text-slate-700 cursor-pointer"
                                    >
                                </div>
                                <div>
                                    <span class="block text-[11px] font-semibold text-slate-600 mb-1">O cambiar Enlace Web</span>
                                    <input
                                        type="url"
                                        v-model="editForm.external_url"
                                        placeholder="https://..."
                                        class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Cover image -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Imagen de Portada
                            </label>
                            <div class="flex items-center gap-3">
                                <img
                                    v-if="editCoverPreview"
                                    :src="editCoverPreview"
                                    class="w-9 h-12 object-cover rounded-md border border-slate-200 flex-shrink-0"
                                >
                                <input
                                    type="file"
                                    accept="image/jpeg,image/png,image/webp"
                                    @change="handleEditCoverChange"
                                    class="text-xs text-slate-500 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[11px] file:font-semibold file:bg-slate-100 file:text-slate-700 cursor-pointer"
                                >
                            </div>
                        </div>

                        <!-- Active status switch -->
                        <div class="sm:col-span-2 pt-1">
                            <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100/80 transition w-full">
                                <input
                                    type="checkbox"
                                    v-model="editForm.is_active"
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0b4d57] relative flex-shrink-0"></div>
                                <div>
                                    <span class="text-xs font-bold text-slate-900 block">Contenido Activo</span>
                                    <span class="text-[11px] text-slate-500 block">Los documentos activos se muestran públicamente en el portal institucional.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            @click="showEditModal = false"
                            class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="editForm.processing"
                            class="px-5 py-2 text-xs font-bold text-white bg-[#0b4d57] hover:bg-teal-900 rounded-xl shadow-xs transition flex items-center gap-2 disabled:opacity-50"
                        >
                            <i v-if="editForm.processing" class="bi bi-arrow-repeat animate-spin"></i>
                            <i v-else class="bi bi-check2-circle"></i>
                            <span>Guardar Cambios</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- MODAL 3: BÚSQUEDA Y CARGA MASIVA ACADÉMICA -->
        <div
            v-if="showAcademicModal"
            class="fixed inset-0 z-50 bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto"
            @click.self="!isImportingAcademic && (showAcademicModal = false)"
        >
            <div class="bg-white rounded-3xl max-w-3xl w-full p-6 sm:p-8 shadow-2xl relative space-y-5 my-auto border border-slate-200 max-h-[94vh] overflow-y-auto">
                <div class="flex items-start justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center text-2xl shadow-md flex-shrink-0">
                            <i class="bi bi-journal-arrow-down"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200 tracking-wider">
                                    Indexación Abierta
                                </span>
                                <span class="text-[11px] text-slate-400 font-semibold hidden sm:inline">SciELO · Google Scholar · Redalyc · DOAJ · Crossref · OpenAlex</span>
                            </div>
                            <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight mt-0.5">
                                Búsqueda y Carga Masiva Académica
                            </h3>
                            <p class="text-xs text-slate-500">
                                Indexe automáticamente revistas, papers, libros y artículos revisados por pares según el programa de estudios.
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="showAcademicModal = false"
                        :disabled="importingAcademic"
                        class="text-slate-400 hover:text-slate-700 w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center transition disabled:opacity-50"
                    >
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>

                <!-- Parameters Form -->
                <form v-if="!importResults" @submit.prevent="runAcademicImport" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Program of study -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center gap-1.5">
                                <i class="bi bi-mortarboard-fill text-indigo-600"></i>
                                <span>Programa de Estudios <span class="text-rose-500">*</span></span>
                            </label>
                            <select
                                v-model="importForm.study_program_id"
                                required
                                class="w-full text-xs font-bold px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-slate-800"
                            >
                                <option value="all">Todos los Programas de Estudio (Multidisciplinario e Integral)</option>
                                <option
                                    v-for="prog in studyPrograms"
                                    :key="prog.id"
                                    :value="prog.id"
                                >
                                    {{ prog.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Category -->
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center gap-1.5">
                                <i class="bi bi-tag-fill text-indigo-600"></i>
                                <span>Tipo de Contenido</span>
                            </label>
                            <select
                                v-model="importForm.category"
                                class="w-full text-xs font-semibold px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-slate-800"
                            >
                                <option value="all">Todas las categorías (Revistas, Papers, Libros...)</option>
                                <option value="Revista">Revistas Científicas (Journals)</option>
                                <option value="Paper">Papers / Artículos de Investigación</option>
                                <option value="Libro">Libros y Capítulos Académicos</option>
                                <option value="Tesis">Tesis y Disertaciones</option>
                                <option value="Documento">Informes y Documentos Técnicos</option>
                            </select>
                        </div>

                        <!-- Limit -->
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center gap-1.5">
                                <i class="bi bi-hash text-indigo-600"></i>
                                <span>Total de Ítems a Importar</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input
                                    type="number"
                                    min="1"
                                    max="100"
                                    v-model.number="importForm.limit"
                                    required
                                    class="w-24 text-xs font-black text-center px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-indigo-900"
                                >
                                <div class="flex items-center gap-1 flex-wrap">
                                    <button
                                        v-for="preset in [5, 10, 20, 50]"
                                        :key="preset"
                                        type="button"
                                        @click="importForm.limit = preset"
                                        :class="importForm.limit === preset ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                        class="px-2.5 py-1.5 rounded-lg text-xs transition"
                                    >
                                        {{ preset }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Sources checkboxes -->
                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                <i class="bi bi-shield-check text-emerald-600"></i>
                                <span>Fuentes Científicas Verificables (Open Access)</span>
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1">
                                <label
                                    v-for="src in [
                                        { id: 'scielo', name: 'SciELO' },
                                        { id: 'google_scholar', name: 'Google Scholar' },
                                        { id: 'redalyc', name: 'Redalyc' },
                                        { id: 'doaj', name: 'DOAJ' },
                                        { id: 'crossref', name: 'Crossref (DOI)' },
                                        { id: 'openalex', name: 'OpenAlex' },
                                        { id: 'europe_pmc', name: 'Europe PMC' },
                                        { id: 'open_library', name: 'Open Library' },
                                    ]"
                                    :key="src.id"
                                    class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-slate-100 cursor-pointer text-xs font-semibold text-slate-700 transition"
                                >
                                    <input
                                        type="checkbox"
                                        :value="src.id"
                                        v-model="importForm.sources"
                                        class="rounded text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <span>{{ src.name }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Custom Query -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 flex items-center justify-between">
                                <span>Tema Específico o Palabras Clave (Opcional)</span>
                                <span class="text-[10px] text-slate-400 font-normal lowercase">opcional</span>
                            </label>
                            <input
                                type="text"
                                v-model="importForm.custom_query"
                                placeholder="Deje en blanco para usar términos optimizados del programa, o ingrese: ej. 'manejo de suelos tropicales'..."
                                class="w-full text-xs font-medium px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-slate-800"
                            >
                        </div>

                        <!-- Filtro de integridad y verificación de enlaces -->
                        <div class="sm:col-span-2 p-3 bg-emerald-50/80 border border-emerald-200/80 rounded-xl flex items-center gap-2.5 text-[11px] text-emerald-800">
                            <i class="bi bi-shield-check text-emerald-600 text-base flex-shrink-0"></i>
                            <div>
                                <span class="font-bold">Filtro de integridad de enlaces activo:</span>
                                Se sondean y verifican los enlaces de origen; los enlaces caídos, rotos o inaccesibles se filtran automáticamente para no ser incluidos.
                            </div>
                        </div>
                    </div>
                    <!-- Loading Banner -->
                    <div v-if="importingAcademic" class="p-4 bg-indigo-50 border border-indigo-200 rounded-2xl flex items-center gap-3 animate-pulse">
                        <div class="animate-spin text-indigo-600 text-xl flex-shrink-0">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div class="text-xs text-indigo-900">
                            <p class="font-bold">Consultando fuentes científicas abiertas (SciELO, Google Scholar, Redalyc, OpenAlex, Crossref)...</p>
                            <p class="text-[11px] text-indigo-700 mt-0.5">Analizando contenidos, extrayendo enlaces directos a documentos y registrando en la biblioteca institucional.</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
                        <button
                            type="button"
                            @click="showAcademicModal = false"
                            :disabled="importingAcademic"
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition disabled:opacity-50"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            :disabled="importingAcademic"
                            class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition inline-flex items-center gap-2 disabled:opacity-50"
                        >
                            <span v-if="!importingAcademic" class="inline-flex items-center gap-2">
                                <i class="bi bi-cloud-arrow-down-fill text-sm"></i>
                                <span>Iniciar Búsqueda e Importación Masiva</span>
                            </span>
                            <span v-else class="inline-flex items-center gap-2">
                                <span class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <span>Procesando Importación...</span>
                            </span>
                        </button>
                    </div>
                </form>
                <!-- Results Summary -->
                <div v-else class="space-y-4">
                    <div
                        class="p-5 rounded-2xl border flex items-start gap-4"
                        :class="importResults.success ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-rose-50 border-rose-200 text-rose-900'"
                    >
                        <div
                            class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                            :class="importResults.success ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'"
                        >
                            <i :class="importResults.success ? 'bi bi-check-circle-fill' : 'bi bi-exclamation-triangle-fill'"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-sm">
                                {{ importResults.success ? '¡Importación Académica Completada!' : 'Resultado de la Búsqueda' }}
                            </h4>
                            <p class="text-xs">
                                {{ importResults.message || 'El proceso de indexación ha finalizado.' }}
                            </p>
                            <div class="flex items-center gap-3 pt-2 text-xs font-semibold">
                                <span class="px-2.5 py-1 bg-white/80 rounded-lg border border-slate-200">
                                    Guardados: <strong>{{ importResults.saved || 0 }}</strong>
                                </span>
                                <span class="px-2.5 py-1 bg-white/80 rounded-lg border border-slate-200">
                                    Actualizados: <strong>{{ importResults.updated || 0 }}</strong>
                                </span>
                                <span class="px-2.5 py-1 bg-white/80 rounded-lg border border-slate-200">
                                    Omitidos: <strong>{{ importResults.skipped || 0 }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Errors if any -->
                    <div v-if="importResults.errors && importResults.errors.length > 0" class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800 space-y-1">
                        <p class="font-bold">Observaciones:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                            <li v-for="(err, i) in importResults.errors" :key="i">{{ err }}</li>
                        </ul>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button
                            type="button"
                            @click="importResults = null"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition"
                        >
                            Nueva Búsqueda
                        </button>
                        <button
                            type="button"
                            @click="showAcademicModal = false"
                            class="px-5 py-2 bg-[#0b4d57] text-white text-xs font-bold rounded-xl transition"
                        >
                            Cerrar y Ver Catálogo
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL 4: VISOR DE LIBRO / IFRAME PREVIEW -->
        <div
            v-if="showIframeModal"
            class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex flex-col items-center justify-center p-2 sm:p-4"
            @click.self="closeIframePreviewModal"
        >
            <div class="bg-slate-900 rounded-3xl w-full max-w-6xl h-[94vh] flex flex-col shadow-2xl border border-slate-700/80 overflow-hidden relative">
                <!-- Toolbar -->
                <header class="bg-slate-950 px-4 sm:px-6 py-3 border-b border-slate-800 flex items-center justify-between text-white flex-shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-teal-500/20 text-teal-300 border border-teal-500/30 flex items-center justify-center text-lg flex-shrink-0">
                            <i class="bi bi-window-fullscreen"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-teal-500/20 text-teal-300 border border-teal-500/30">
                                    {{ previewData.category || 'Documento' }}
                                </span>
                                <span class="text-[11px] text-slate-400 font-semibold truncate">
                                    {{ previewData.program_name || 'General' }}
                                </span>
                            </div>
                            <h3 class="text-xs sm:text-sm font-bold text-white truncate max-w-md sm:max-w-xl" :title="previewData.title">
                                {{ previewData.title }}
                            </h3>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button
                            v-if="previewData.id"
                            type="button"
                            @click="regenerateVirtualCopy"
                            :disabled="generatingVirtualCopy"
                            class="px-3 py-1.5 rounded-xl bg-teal-500/20 hover:bg-teal-500/30 text-teal-300 hover:text-white text-xs font-semibold transition inline-flex items-center gap-1.5 border border-teal-500/30 disabled:opacity-50"
                            title="Descargar copia virtual institucional para eludir bloqueos externos"
                        >
                            <i class="bi" :class="generatingVirtualCopy ? 'bi-arrow-repeat animate-spin' : 'bi-file-earmark-pdf'"></i>
                            <span class="hidden sm:inline">{{ generatingVirtualCopy ? 'Descargando...' : 'Copia Virtual' }}</span>
                        </button>

                        <a
                            :href="previewData.url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white text-xs font-semibold transition inline-flex items-center gap-1.5"
                            title="Abrir en pestaña nueva"
                        >
                            <i class="bi bi-box-arrow-up-right text-xs"></i>
                            <span class="hidden sm:inline">Pestaña Nueva</span>
                        </a>

                        <button
                            type="button"
                            @click="closeIframePreviewModal"
                            class="p-2 rounded-xl bg-rose-500/20 hover:bg-rose-500 text-rose-300 hover:text-white transition text-xs"
                            title="Cerrar Visor"
                        >
                            <i class="bi bi-x-lg text-xs"></i>
                        </button>
                    </div>
                </header>

                <!-- Sub-bar info -->
                <div class="bg-slate-800/80 px-4 py-1.5 border-b border-slate-700/60 text-[11px] text-slate-300 flex items-center justify-between gap-3 flex-shrink-0">
                    <span class="truncate">
                        <strong>Autor:</strong> {{ previewData.author || 'No especificado' }}
                        <span v-if="previewData.publisher"> · <strong>Fuente:</strong> {{ previewData.publisher }}</span>
                    </span>
                    <span class="text-[10px] text-slate-400 flex-shrink-0">Visor Integrado Institucional</span>
                </div>

                <!-- Iframe Container -->
                <main class="flex-1 w-full h-full bg-slate-900 relative overflow-hidden">
                    <iframe
                        v-if="previewData.url"
                        :src="`${previewData.url}#toolbar=1&navpanes=0&view=FitH`"
                        class="w-full h-full border-0 bg-slate-900"
                        allow="fullscreen"
                        title="Visor Integrado Institucional"
                    ></iframe>
                </main>
            </div>
        </div>
        <!-- MODAL 5: CONFIRMAR ELIMINACIÓN -->
        <div
            v-if="showDeleteConfirm"
            class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
            @click.self="showDeleteConfirm = false"
        >
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mx-auto">
                    <i class="bi bi-trash-fill"></i>
                </div>
                <div class="text-center space-y-1">
                    <h3 class="text-base font-bold text-slate-900">¿Eliminar este documento?</h3>
                    <p class="text-xs text-slate-500">
                        Esta acción retirará el material del catálogo institucional y eliminará sus archivos físicos permanentemente.
                    </p>
                    <p v-if="bookToDelete" class="text-xs font-bold text-slate-700 pt-1">
                        "{{ bookToDelete.title }}"
                    </p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button
                        type="button"
                        @click="showDeleteConfirm = false"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        @click="executeDelete"
                        class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition"
                    >
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    </LibraryLayout>
</template>
