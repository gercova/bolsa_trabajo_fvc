<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import LibraryLayout from './Layout.vue';
import route from '@/route';

const props = defineProps({
    books: { type: Object, required: true },
    studyPrograms: { type: Array, default: () => [] },
    selectedCareer: { type: [String, Number], default: 'all' },
    selectedCategory: { type: String, default: 'all' },
    searchQuery: { type: String, default: '' },
});

// Search & Filter state
const searchInput = ref(props.searchQuery || '');
const currentCareer = ref(props.selectedCareer || 'all');
const currentCategory = ref(props.selectedCategory || 'all');

const applyFilters = () => {
    router.get(
        route('admin.library.repository'),
        {
            career: currentCareer.value !== 'all' ? currentCareer.value : undefined,
            category: currentCategory.value !== 'all' ? currentCategory.value : undefined,
            q: searchInput.value || undefined,
        },
        { preserveState: true, preserveScroll: true }
    );
};

const clearFilters = () => {
    searchInput.value = '';
    currentCareer.value = 'all';
    currentCategory.value = 'all';
    router.get(route('admin.library.repository'));
};

// ── TOAST NOTIFICATIONS ────────────────────────────────────────────────────────
const toastMessage = ref('');
const toastType = ref('success');
const showToast = ref(false);

const notify = (msg, type = 'success') => {
    toastMessage.value = msg;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => {
        showToast.value = false;
    }, 4500);
};

// ── 1. MODAL: SUBIR CONTENIDO (CREATE BOOK) ────────────────────────────────────
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
        createPdfName.value = file.name;
        createForm.file = file;
    }
};

const handleCreateCoverChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        createForm.cover = file;
        createCoverPreview.value = URL.createObjectURL(file);
    }
};

const submitCreateBook = () => {
    createForm.post(route('admin.library.store'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset();
            notify('Contenido agregado al repositorio exitosamente.');
        },
        onError: () => {
            notify('Por favor revise los campos del formulario.', 'error');
        },
    });
};

// ── 2. MODAL: EDITAR CONTENIDO (EDIT BOOK) ─────────────────────────────────────
const showEditModal = ref(false);
const editingBookId = ref(null);
const editSourceType = ref('file');
const editExistingFile = ref('');
const editExistingCover = ref('');
const editPdfName = ref('');
const editCoverPreview = ref('');
const isLoadingBookDetails = ref(false);

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
    edition: '',
    pages: '',
    isbn: '',
    description: '',
    rating: 5,
});

const openEditModal = async (bookId) => {
    editingBookId.value = bookId;
    isLoadingBookDetails.value = true;
    showEditModal.value = true;
    editForm.reset();
    editForm.clearErrors();
    editPdfName.value = '';
    editCoverPreview.value = '';

    try {
        const response = await fetch(route('admin.library.edit', bookId), {
            headers: { Accept: 'application/json' },
        });
        const json = await response.json();
        if (json.success && json.data) {
            const b = json.data;
            editForm.title = b.title || '';
            editForm.author = b.author || '';
            editForm.category = b.category || 'Libro';
            editForm.study_program_id = b.study_program_id || '';
            editForm.external_url = b.external_url || '';
            editForm.is_active = Boolean(b.is_active);
            editForm.publisher = b.publisher || '';
            editForm.publication_year = b.publication_year || '';
            editForm.edition = b.edition || '';
            editForm.pages = b.pages || '';
            editForm.isbn = b.isbn || '';
            editForm.description = b.description || '';
            editForm.rating = b.rating || 5;

            editExistingCover.value = b.cover_url || '';
            editExistingFile.value = b.file_url ? `${b.file_size || 'Archivo PDF actual'}` : '';
            editSourceType.value = b.external_url ? 'link' : 'file';
        }
    } catch {
        notify('No se pudo cargar la información del documento.', 'error');
    } finally {
        isLoadingBookDetails.value = false;
    }
};

const handleEditPdfChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        editPdfName.value = file.name;
        editForm.file = file;
    }
};

const handleEditCoverChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        editForm.cover = file;
        editCoverPreview.value = URL.createObjectURL(file);
    }
};

const submitEditBook = () => {
    router.post(
        route('admin.library.update', editingBookId.value),
        {
            _method: 'PUT',
            ...editForm.data(),
        },
        {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                showEditModal.value = false;
                notify('Contenido actualizado correctamente.');
            },
            onError: (errors) => {
                editForm.setError(errors);
                notify('Revise los errores en el formulario de edición.', 'error');
            },
        }
    );
};

// ── 3. MODAL: IMPORTACIÓN MASIVA ACADÉMICA ─────────────────────────────────────
const showAcademicModal = ref(false);
const academicEngine = ref('all');
const academicProgramId = ref('');
const academicCustomQuery = ref('');
const academicLimit = ref(10);
const isImportingAcademic = ref(false);
const academicResult = ref(null);

const openAcademicModal = () => {
    academicResult.value = null;
    academicCustomQuery.value = '';
    academicLimit.value = 10;
    academicEngine.value = 'all';
    academicProgramId.value = '';
    showAcademicModal.value = true;
};

const submitAcademicImport = async () => {
    isImportingAcademic.value = true;
    academicResult.value = null;

    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const response = await fetch(route('admin.library.fetch-academic'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                Accept: 'application/json',
            },
            body: JSON.stringify({
                engine: academicEngine.value,
                study_program_id: academicProgramId.value || null,
                custom_query: academicCustomQuery.value || null,
                limit: academicLimit.value,
            }),
        });

        const data = await response.json();
        academicResult.value = data;

        if (data.success) {
            notify(`Importación completada: ${data.saved} nuevos, ${data.updated} actualizados.`);
            router.reload({ only: ['books'] });
        } else {
            notify(data.message || 'Error en la importación académica.', 'error');
        }
    } catch (e) {
        notify('Ocurrió un error de red al consultar los repositorios académicos.', 'error');
        academicResult.value = {
            success: false,
            message: e.message || 'Error de conexión',
            saved: 0,
            updated: 0,
            skipped: 0,
            total: 0,
            errors: [e.message],
        };
    } finally {
        isImportingAcademic.value = false;
    }
};

// ── 4. MODAL: VISOR INTEGRADO (IFRAME PREVIEW) ─────────────────────────────────
const showIframeModal = ref(false);
const iframeBook = ref(null);

const openIframePreview = (book) => {
    iframeBook.value = {
        title: book.title,
        author: book.author,
        category: book.category,
        publisher: book.publisher || 'Repositorio Institucional',
        program_name: book.study_program ? book.study_program.name : 'General (Todas)',
        url: book.is_external && book.external_url ? book.external_url : route('biblioteca.read', book.slug),
    };
    showIframeModal.value = true;
};

// ── 5. TOGGLE STATUS & DELETE ACTIONS ──────────────────────────────────────────
const toggleBookStatus = (book) => {
    router.patch(
        route('admin.library.toggle-status', book.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                notify(`El documento fue ${book.is_active ? 'desactivado' : 'activado'} correctamente.`);
            },
            onError: () => {
                notify('No se pudo cambiar el estado del documento.', 'error');
            },
        }
    );
};

const showDeleteConfirm = ref(false);
const bookToDelete = ref(null);

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
            notify('El documento y sus archivos asociados fueron eliminados del repositorio.');
        },
        onError: () => {
            notify('No se pudo eliminar el documento.', 'error');
        },
    });
};
</script>

<template>
    <Head title="Repositorio de Contenidos - Biblioteca" />

    <LibraryLayout>
        <div class="p-5 sm:p-7 space-y-6">

            <!-- Toast Alert -->
            <transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="transform -translate-y-4 opacity-0"
                enter-to-class="transform translate-y-0 opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="transform translate-y-0 opacity-100"
                leave-to-class="transform -translate-y-4 opacity-0"
            >
                <div
                    v-if="showToast"
                    class="fixed top-20 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-xs font-bold border backdrop-blur-md"
                    :class="toastType === 'success' ? 'bg-emerald-500/95 text-white border-emerald-400' : 'bg-rose-500/95 text-white border-rose-400'"
                >
                    <i :class="toastType === 'success' ? 'bi bi-check-circle-fill text-base' : 'bi bi-exclamation-triangle-fill text-base'"></i>
                    <span>{{ toastMessage }}</span>
                    <button type="button" @click="showToast = false" class="ml-2 hover:opacity-75">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </transition>

            <!-- Header & Filter Toolbar -->
            <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                            <i class="bi bi-folder2-open text-teal-800"></i> Repositorio de Contenidos
                        </h1>
                        <p class="text-xs text-slate-500">Gestión integral, actualización y mantenimiento del acervo bibliográfico institucional.</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <button
                            type="button"
                            @click="openAcademicModal"
                            class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-2"
                        >
                            <i class="bi bi-journal-arrow-down"></i>
                            <span>Búsqueda Masiva de Papers / Revistas</span>
                        </button>
                        <button
                            type="button"
                            @click="openCreateModal"
                            class="px-4 py-2 bg-[#0b4d57] hover:bg-teal-900 text-white font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-2"
                        >
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            <span>Subir Nuevo Contenido</span>
                        </button>
                    </div>
                </div>

                <!-- Filters Strip -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-3 border-t border-slate-100">
                    <!-- Career -->
                    <div>
                        <select
                            v-model="currentCareer"
                            @change="applyFilters"
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"
                        >
                            <option value="all">Todas las carreras</option>
                            <option v-for="prog in studyPrograms" :key="prog.id" :value="prog.id">
                                {{ prog.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <select
                            v-model="currentCategory"
                            @change="applyFilters"
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"
                        >
                            <option value="all">Todos los tipos (Libros, Papers...)</option>
                            <option value="Libro">Libros</option>
                            <option value="Revista">Revistas</option>
                            <option value="Paper">Papers / Artículos</option>
                            <option value="Tesis">Tesis</option>
                            <option value="Documento">Documentos</option>
                        </select>
                    </div>

                    <!-- Search Input -->
                    <div class="relative sm:col-span-2 flex items-center gap-2">
                        <div class="relative flex-1">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input
                                type="text"
                                v-model="searchInput"
                                placeholder="Buscar por título o autor..."
                                @keydown.enter="applyFilters"
                                class="w-full pl-8 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"
                            />
                            <button
                                v-if="searchInput"
                                type="button"
                                @click="searchInput = ''; applyFilters()"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                            >
                                <i class="bi bi-x-circle text-xs"></i>
                            </button>
                        </div>
                        <button
                            type="button"
                            @click="applyFilters"
                            class="px-3.5 py-2 bg-[#0b4d57] text-white rounded-xl text-xs font-bold hover:bg-teal-900 transition shrink-0"
                        >
                            Buscar
                        </button>
                        <button
                            v-if="searchInput || currentCareer !== 'all' || currentCategory !== 'all'"
                            type="button"
                            @click="clearFilters"
                            class="px-2.5 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition shrink-0"
                            title="Limpiar filtros"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Repository Table -->
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
                            <tr v-for="book in books.data" :key="book.id" class="hover:bg-slate-50/70 transition">
                                <td class="py-3 px-4 flex items-center gap-3">
                                    <div class="w-10 h-14 bg-slate-100 rounded-md overflow-hidden flex-shrink-0 border border-slate-200">
                                        <img
                                            v-if="book.cover_url"
                                            :src="book.cover_url"
                                            :alt="book.title"
                                            class="w-full h-full object-cover"
                                            loading="lazy"
                                        />
                                        <div v-else class="w-full h-full flex items-center justify-center text-slate-300">
                                            <i class="bi bi-book text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="min-w-0 max-w-xs sm:max-w-md">
                                        <h4 class="font-bold text-slate-900 truncate" :title="book.title">{{ book.title }}</h4>
                                        <p class="text-[11px] text-slate-500 truncate">{{ book.author }}</p>
                                        <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-0.5">
                                            <span class="px-1.5 py-0.2 bg-slate-100 rounded text-slate-600 font-semibold">{{ book.category }}</span>
                                            <span v-if="book.publication_year">{{ book.publication_year }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3 px-4 font-semibold text-slate-700">
                                    {{ book.study_program ? book.study_program.name : 'General (Todas)' }}
                                </td>

                                <td class="py-3 px-4">
                                    <span
                                        v-if="book.is_external || book.external_url"
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200"
                                        :title="book.external_url"
                                    >
                                        <i class="bi bi-link-45deg"></i> Enlace Web
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200"
                                    >
                                        <i class="bi bi-file-earmark-pdf"></i> {{ book.formatted_file_size || 'PDF Local' }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-center font-bold text-slate-800">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="bi bi-eye text-slate-400"></i> {{ Number(book.views_count || 0).toLocaleString() }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-center">
                                    <button
                                        type="button"
                                        @click="toggleBookStatus(book)"
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold transition cursor-pointer"
                                        :class="book.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                                    >
                                        {{ book.is_active ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </td>

                                <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                                    <!-- In-site Iframe Viewer -->
                                    <button
                                        type="button"
                                        @click="openIframePreview(book)"
                                        class="p-1.5 text-teal-700 hover:bg-teal-50 rounded-lg inline-block transition"
                                        title="Visor Integrado (Iframe)"
                                    >
                                        <i class="bi bi-window-fullscreen"></i>
                                    </button>

                                    <!-- Reader Tab -->
                                    <a
                                        :href="route('biblioteca.read', book.slug)"
                                        target="_blank"
                                        class="p-1.5 text-slate-500 hover:bg-slate-100 rounded-lg inline-block transition"
                                        title="Visualizar en Lector Web"
                                    >
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>

                                    <!-- Edit -->
                                    <button
                                        type="button"
                                        @click="openEditModal(book.id)"
                                        class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg inline-block transition"
                                        title="Editar"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <!-- Delete -->
                                    <button
                                        type="button"
                                        @click="confirmDelete(book)"
                                        class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg inline-block transition"
                                        title="Eliminar"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="!books.data || books.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="bi bi-folder-x text-3xl block mb-2 text-slate-300"></i>
                                    No se encontraron documentos registrados en el repositorio.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="books.links && books.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                    <span class="text-xs text-slate-500">
                        Mostrando del {{ books.from || 0 }} al {{ books.to || 0 }} de {{ books.total || 0 }} documentos
                    </span>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, idx) in books.links"
                            :key="idx"
                            :href="link.url || '#'"
                            :class="[
                                'px-3 py-1.5 rounded-lg text-xs font-semibold transition',
                                link.active ? 'bg-[#0b4d57] text-white font-bold' : 'text-slate-600 hover:bg-slate-100',
                                !link.url ? 'opacity-40 pointer-events-none' : '',
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>

            <!-- 1. MODAL: CREATE BOOK -->
            <div
                v-if="showCreateModal"
                class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
                @click.self="showCreateModal = false"
            >
                <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-cloud-arrow-up-fill text-teal-800"></i> Subir Contenido al Repositorio
                            </h3>
                            <p class="text-xs text-slate-500">Agregue libros, manuales, revistas o enlaces académicos al acervo institucional.</p>
                        </div>
                        <button type="button" @click="showCreateModal = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form @submit.prevent="submitCreateBook" class="space-y-4 text-xs">
                        <!-- Source Type Switch -->
                        <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-xl border border-slate-200/70">
                            <button
                                type="button"
                                @click="createSourceType = 'file'"
                                :class="createSourceType === 'file' ? 'bg-white shadow-xs text-teal-900 font-bold' : 'text-slate-600'"
                                class="flex-1 py-1.5 rounded-lg text-center transition"
                            >
                                <i class="bi bi-file-earmark-pdf mr-1"></i> Archivo PDF Local
                            </button>
                            <button
                                type="button"
                                @click="createSourceType = 'link'"
                                :class="createSourceType === 'link' ? 'bg-white shadow-xs text-teal-900 font-bold' : 'text-slate-600'"
                                class="flex-1 py-1.5 rounded-lg text-center transition"
                            >
                                <i class="bi bi-link-45deg mr-1"></i> Enlace Web / Externo
                            </button>
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Título del Documento *</label>
                            <input
                                type="text"
                                v-model="createForm.title"
                                required
                                placeholder="Ej: Fundamentos de Redes y Comunicaciones"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"
                                :class="createForm.errors.title ? 'border-rose-400' : 'border-slate-200'"
                            />
                            <p v-if="createForm.errors.title" class="text-rose-500 text-[11px] mt-1">{{ createForm.errors.title }}</p>
                        </div>

                        <!-- Author & Category -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Autor(es) *</label>
                                <input
                                    type="text"
                                    v-model="createForm.author"
                                    required
                                    placeholder="Ej: Andrew S. Tanenbaum"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"
                                    :class="createForm.errors.author ? 'border-rose-400' : 'border-slate-200'"
                                />
                                <p v-if="createForm.errors.author" class="text-rose-500 text-[11px] mt-1">{{ createForm.errors.author }}</p>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Tipo de Contenido *</label>
                                <select
                                    v-model="createForm.category"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"
                                >
                                    <option value="Libro">Libro</option>
                                    <option value="Revista">Revista</option>
                                    <option value="Paper">Paper / Artículo Científico</option>
                                    <option value="Tesis">Tesis</option>
                                    <option value="Documento">Documento Institucional</option>
                                </select>
                            </div>
                        </div>

                        <!-- Study Program & Year -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Carrera / Programa de Estudios</label>
                                <select
                                    v-model="createForm.study_program_id"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"
                                >
                                    <option value="">General (Todas las carreras)</option>
                                    <option v-for="prog in studyPrograms" :key="prog.id" :value="prog.id">
                                        {{ prog.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Año de Publicación</label>
                                <input
                                    type="number"
                                    v-model="createForm.publication_year"
                                    placeholder="Ej: 2024"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"
                                />
                            </div>
                        </div>

                        <!-- Source inputs -->
                        <div v-if="createSourceType === 'file'">
                            <label class="block font-bold text-slate-700 mb-1">Archivo PDF *</label>
                            <input
                                type="file"
                                accept="application/pdf"
                                @change="handleCreatePdfChange"
                                class="w-full text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-teal-50 file:text-teal-800 hover:file:bg-teal-100 transition border border-slate-200 rounded-xl p-1 bg-slate-50 cursor-pointer"
                            />
                            <p v-if="createPdfName" class="text-teal-700 font-semibold mt-1">
                                <i class="bi bi-file-earmark-check"></i> {{ createPdfName }}
                            </p>
                        </div>

                        <div v-else>
                            <label class="block font-bold text-slate-700 mb-1">URL / Enlace Directo *</label>
                            <input
                                type="url"
                                v-model="createForm.external_url"
                                placeholder="https://repositorio.academico.org/documento.pdf"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"
                            />
                        </div>

                        <!-- Cover image -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Portada del Documento (Opcional)</label>
                            <div class="flex items-center gap-3">
                                <div v-if="createCoverPreview" class="w-12 h-16 rounded overflow-hidden border border-slate-200 shrink-0">
                                    <img :src="createCoverPreview" class="w-full h-full object-cover" />
                                </div>
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleCreateCoverChange"
                                    class="w-full text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition border border-slate-200 rounded-xl p-1 bg-slate-50 cursor-pointer"
                                />
                            </div>
                        </div>

                        <!-- Publisher & ISBN -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Editorial / Fuente</label>
                                <input
                                    type="text"
                                    v-model="createForm.publisher"
                                    placeholder="Ej: Pearson / SciELO"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800"
                                />
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">ISBN / Identificador</label>
                                <input
                                    type="text"
                                    v-model="createForm.isbn"
                                    placeholder="Ej: 978-0132126953"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800"
                                />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Sinopsis / Descripción</label>
                            <textarea
                                v-model="createForm.description"
                                rows="3"
                                placeholder="Breve resumen o contenido temático..."
                                class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white"
                            ></textarea>
                        </div>

                        <!-- Active Toggle -->
                        <div class="flex items-center gap-2 pt-2">
                            <input
                                type="checkbox"
                                id="createIsActive"
                                v-model="createForm.is_active"
                                class="rounded text-teal-800 focus:ring-teal-500 w-4 h-4 cursor-pointer"
                            />
                            <label for="createIsActive" class="font-bold text-slate-700 cursor-pointer">
                                Publicar inmediatamente en la biblioteca virtual
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button
                                type="button"
                                @click="showCreateModal = false"
                                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="createForm.processing"
                                class="px-6 py-2.5 bg-[#0b4d57] hover:bg-teal-900 text-white font-bold rounded-xl shadow-xs transition disabled:opacity-50 inline-flex items-center gap-2"
                            >
                                <span v-if="createForm.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                <span>Guardar en Repositorio</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2. MODAL: EDIT BOOK -->
            <div
                v-if="showEditModal"
                class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
                @click.self="showEditModal = false"
            >
                <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-pencil-square text-indigo-700"></i> Editar Contenido
                            </h3>
                            <p class="text-xs text-slate-500">Modifique la información bibliográfica, metadatos o archivos asociados.</p>
                        </div>
                        <button type="button" @click="showEditModal = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div v-if="isLoadingBookDetails" class="py-16 text-center text-slate-400 space-y-3">
                        <div class="w-8 h-8 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin mx-auto"></div>
                        <p class="text-xs font-semibold">Cargando datos del documento...</p>
                    </div>

                    <form v-else @submit.prevent="submitEditBook" class="space-y-4 text-xs">
                        <!-- Title -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Título del Documento *</label>
                            <input
                                type="text"
                                v-model="editForm.title"
                                required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600"
                                :class="editForm.errors.title ? 'border-rose-400' : 'border-slate-200'"
                            />
                        </div>

                        <!-- Author & Category -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Autor(es) *</label>
                                <input
                                    type="text"
                                    v-model="editForm.author"
                                    required
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border rounded-xl text-slate-800 focus:bg-white"
                                />
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Tipo de Contenido *</label>
                                <select
                                    v-model="editForm.category"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white"
                                >
                                    <option value="Libro">Libro</option>
                                    <option value="Revista">Revista</option>
                                    <option value="Paper">Paper / Artículo Científico</option>
                                    <option value="Tesis">Tesis</option>
                                    <option value="Documento">Documento Institucional</option>
                                </select>
                            </div>
                        </div>

                        <!-- Study Program & Year -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Carrera Asignada</label>
                                <select
                                    v-model="editForm.study_program_id"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white"
                                >
                                    <option value="">General (Todas las carreras)</option>
                                    <option v-for="prog in studyPrograms" :key="prog.id" :value="prog.id">
                                        {{ prog.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Año de Publicación</label>
                                <input
                                    type="number"
                                    v-model="editForm.publication_year"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800"
                                />
                            </div>
                        </div>

                        <!-- External URL / File Replacement -->
                        <div v-if="editSourceType === 'link'">
                            <label class="block font-bold text-slate-700 mb-1">URL Enlace Web</label>
                            <input
                                type="url"
                                v-model="editForm.external_url"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800"
                            />
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Reemplazar Archivo PDF (Opcional)</label>
                            <div v-if="editExistingFile" class="mb-1 text-slate-500 text-[11px] flex items-center gap-1">
                                <i class="bi bi-file-earmark-pdf-fill text-rose-500"></i>
                                <span>Actual: {{ editExistingFile }}</span>
                            </div>
                            <input
                                type="file"
                                accept="application/pdf"
                                @change="handleEditPdfChange"
                                class="w-full text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition border border-slate-200 rounded-xl p-1 bg-slate-50 cursor-pointer"
                            />
                        </div>

                        <!-- Cover Replacement -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Reemplazar Portada (Opcional)</label>
                            <div class="flex items-center gap-3">
                                <div v-if="editCoverPreview || editExistingCover" class="w-12 h-16 rounded overflow-hidden border border-slate-200 shrink-0">
                                    <img :src="editCoverPreview || editExistingCover" class="w-full h-full object-cover" />
                                </div>
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleEditCoverChange"
                                    class="w-full text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition border border-slate-200 rounded-xl p-1 bg-slate-50 cursor-pointer"
                                />
                            </div>
                        </div>

                        <!-- Publisher & Description -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Editorial</label>
                                <input type="text" v-model="editForm.publisher" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl" />
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">ISBN</label>
                                <input type="text" v-model="editForm.isbn" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl" />
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Descripción</label>
                            <textarea v-model="editForm.description" rows="3" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl"></textarea>
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <input
                                type="checkbox"
                                id="editIsActive"
                                v-model="editForm.is_active"
                                class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer"
                            />
                            <label for="editIsActive" class="font-bold text-slate-700 cursor-pointer">
                                Estado activo (Visible en el catálogo público)
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button
                                type="button"
                                @click="showEditModal = false"
                                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-xs transition"
                            >
                                Actualizar Contenido
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 3. MODAL: ACADEMIC BULK IMPORT -->
            <div
                v-if="showAcademicModal"
                class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
                @click.self="showAcademicModal = false"
            >
                <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-journal-arrow-down text-purple-600"></i> Importación Masiva de Recursos Académicos
                            </h3>
                            <p class="text-xs text-slate-500">Descarga e indexa automáticamente papers, artículos y revistas indexadas de fuentes abiertas verificadas.</p>
                        </div>
                        <button type="button" @click="showAcademicModal = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Motor / Fuente Académica</label>
                            <select
                                v-model="academicEngine"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white"
                            >
                                <option value="all">Todas las fuentes combinadas (SciELO, Redalyc, OpenAlex, DOAJ, etc.)</option>
                                <option value="scielo">SciELO (Scientific Electronic Library Online)</option>
                                <option value="redalyc">Redalyc (Red de Revistas Científicas)</option>
                                <option value="doaj">DOAJ (Directory of Open Access Journals)</option>
                                <option value="openalex">OpenAlex Academic Engine</option>
                                <option value="europe_pmc">Europe PMC</option>
                                <option value="semanticscholar">Semantic Scholar</option>
                                <option value="crossref">CrossRef</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Programa de Estudios / Carrera</label>
                                <select
                                    v-model="academicProgramId"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white"
                                >
                                    <option value="">Todas las carreras</option>
                                    <option v-for="prog in studyPrograms" :key="prog.id" :value="prog.id">
                                        {{ prog.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Límite de Documentos por Búsqueda</label>
                                <input
                                    type="number"
                                    min="1"
                                    max="50"
                                    v-model.number="academicLimit"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Término de Búsqueda Personalizado (Opcional)</label>
                            <input
                                type="text"
                                v-model="academicCustomQuery"
                                placeholder="Ej: Inteligencia Artificial aplicada a la educación técnica..."
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white"
                            />
                        </div>

                        <!-- Results preview box -->
                        <div v-if="academicResult" class="p-4 rounded-2xl border text-xs space-y-2" :class="academicResult.success ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-rose-50 border-rose-200 text-rose-900'">
                            <div class="flex items-center gap-2 font-bold">
                                <i :class="academicResult.success ? 'bi bi-check-circle-fill text-emerald-600' : 'bi bi-exclamation-octagon-fill text-rose-600'"></i>
                                <span>{{ academicResult.message }}</span>
                            </div>
                            <div v-if="academicResult.success" class="flex items-center gap-4 text-[11px] pt-1">
                                <span><strong>{{ academicResult.saved }}</strong> guardados</span>
                                <span><strong>{{ academicResult.updated }}</strong> actualizados</span>
                                <span><strong>{{ academicResult.skipped }}</strong> omitidos</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button
                                type="button"
                                @click="showAcademicModal = false"
                                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition"
                            >
                                Cerrar
                            </button>
                            <button
                                type="button"
                                @click="submitAcademicImport"
                                :disabled="isImportingAcademic"
                                class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-xs transition disabled:opacity-50 inline-flex items-center gap-2"
                            >
                                <span v-if="isImportingAcademic" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                <span>{{ isImportingAcademic ? 'Buscando e Indexando...' : 'Iniciar Búsqueda e Importación' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. MODAL: IFRAME PREVIEW -->
            <div
                v-if="showIframeModal && iframeBook"
                class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-xs flex items-center justify-center p-3 sm:p-6"
                @click.self="showIframeModal = false"
            >
                <div class="bg-slate-950 rounded-3xl w-full h-[90vh] max-w-5xl flex flex-col overflow-hidden shadow-2xl border border-slate-800 text-white">
                    <div class="px-5 py-3 border-b border-slate-800 flex items-center justify-between gap-3 bg-slate-900/90">
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm truncate">{{ iframeBook.title }}</h3>
                            <p class="text-[11px] text-slate-400 truncate">{{ iframeBook.author }} · {{ iframeBook.program_name }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a
                                :href="iframeBook.url"
                                target="_blank"
                                class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition"
                                title="Abrir en pestaña nueva"
                            >
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <button
                                type="button"
                                @click="showIframeModal = false"
                                class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition"
                            >
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex-1 w-full bg-slate-900 relative">
                        <iframe
                            :src="iframeBook.url"
                            class="w-full h-full border-0 bg-slate-900"
                            allow="fullscreen"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>
            </div>

            <!-- 5. MODAL: DELETE CONFIRMATION -->
            <div
                v-if="showDeleteConfirm && bookToDelete"
                class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
                @click.self="showDeleteConfirm = false"
            >
                <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-100 text-center">
                    <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl mx-auto border border-rose-100">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">¿Eliminar este contenido?</h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Está a punto de eliminar <strong class="text-slate-700">"{{ bookToDelete.title }}"</strong>. Esta acción eliminará el registro y los archivos físicos asociados permanentemente.
                        </p>
                    </div>
                    <div class="flex items-center justify-center gap-3 pt-2">
                        <button
                            type="button"
                            @click="showDeleteConfirm = false"
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition"
                        >
                            Cancelar
                        </button>
                        <button
                            type="button"
                            @click="executeDelete"
                            class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-xs transition"
                        >
                            Sí, Eliminar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </LibraryLayout>
</template>
