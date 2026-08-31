@extends('layouts.app')
@section('title', 'Gestión de Módulos — Panel Administrativo')
@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .animate-fade-in { animation: fadeIn 0.25s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
    x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative" x-data="modulesApp()">

        {{-- ── Header ── --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight leading-none flex items-center gap-2">
                            <i class="bi bi-layers text-purple-600"></i> Módulos Formativos
                        </h1>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">Gestión de módulos y unidades temáticas por curso de certificación</p>
                    </div>
                </div>
                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-book mr-1 text-purple-500"></i> Programas
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Módulos</span>
                </div>
            </div>

            {{-- Navigation Tabs --}}
            <div class="px-4 sm:px-6 border-t border-gray-100 bg-gray-50/60 flex items-center gap-1 overflow-x-auto">
                <a href="{{ route('admin.programs.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap">
                    <i class="bi bi-mortarboard"></i> Programas de Estudio
                </a>
                <a href="{{ route('admin.certificates.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap">
                    <i class="bi bi-patch-check"></i> Certificados
                </a>
                <a href="{{ route('admin.courses.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap">
                    <i class="bi bi-journal-bookmark"></i> Cursos
                </a>
                <a href="{{ route('admin.modules.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-purple-600 text-purple-700 whitespace-nowrap">
                    <i class="bi bi-layers"></i> Módulos
                </a>
                <a href="{{ route('admin.itineraries.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap">
                    <i class="bi bi-diagram-3"></i> Itinerarios
                </a>
            </div>
        </header>

        {{-- ── Main Content ── --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Alert Messages --}}
                @if (session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="text-emerald-500 hover:text-emerald-700" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm flex items-center justify-between animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-exclamation-octagon-fill text-red-600 text-xl"></i>
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                        <button type="button" class="text-red-400 hover:text-red-600" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm animate-fade-in">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg"></i>
                            <p class="text-sm font-bold text-red-800">Por favor corrige los siguientes errores:</p>
                        </div>
                        <ul class="list-disc list-inside text-xs text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ── Stat Cards ── --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-layers-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Módulos</p>
                            <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalModules) }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Módulos Activos</p>
                            <h3 class="text-2xl font-black text-emerald-700">{{ number_format($activeModules) }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Cursos Vinculados</p>
                            <h3 class="text-2xl font-black text-blue-700">{{ number_format($coursesCount) }}</h3>
                        </div>
                    </div>
                </div>

                {{-- ── Filters & Actions ── --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 space-y-4">
                    <form action="{{ route('admin.modules.index') }}" method="GET"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">

                        {{-- Search --}}
                        <div class="lg:col-span-5 relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar módulo, créditos o curso..."
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        {{-- Course Filter --}}
                        <div class="lg:col-span-3">
                            <select name="course_id" onchange="this.form.submit()"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                <option value="">Curso: Todos</option>
                                @foreach ($courses as $c)
                                    <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div class="lg:col-span-2">
                            <select name="status" onchange="this.form.submit()"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                <option value="">Estado: Todos</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activos</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="lg:col-span-2 flex items-center gap-2 justify-end">
                            <button type="submit"
                                class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center justify-center gap-1.5 flex-1">
                                <i class="bi bi-funnel-fill"></i> Filtrar
                            </button>
                            @if (request()->hasAny(['search', 'course_id', 'status']))
                                <a href="{{ route('admin.modules.index') }}"
                                    class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition-all"
                                    title="Limpiar Filtros">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            @endif
                        </div>
                    </form>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
                        <span class="text-xs font-semibold text-gray-500">
                            Mostrando {{ $modules->total() }} módulos registrados
                        </span>
                        <button type="button" @click="openCreateModal()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
                            <i class="bi bi-plus-lg"></i> Nuevo Módulo
                        </button>
                    </div>
                </div>

                {{-- ── Table ── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    @if ($modules->isEmpty())
                        <div class="flex flex-col items-center justify-center py-20 gap-4 text-gray-400">
                            <div class="w-16 h-16 rounded-2xl bg-purple-50 text-purple-400 flex items-center justify-center text-3xl">
                                <i class="bi bi-layers"></i>
                            </div>
                            <div class="text-center">
                                <p class="font-bold text-gray-700">Sin módulos registrados</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    @if (request()->hasAny(['search', 'course_id', 'status']))
                                        No hay módulos con los filtros aplicados.
                                        <a href="{{ route('admin.modules.index') }}" class="text-purple-600 font-semibold hover:underline">Limpiar filtros</a>
                                    @else
                                        Empieza registrando el primer módulo con el botón <strong>Nuevo Módulo</strong>.
                                    @endif
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Curso</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre del Módulo</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Créditos</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Itinerarios</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($modules as $module)
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <i class="bi bi-journal-bookmark text-purple-600"></i>
                                                    <span class="font-semibold text-gray-800 text-xs truncate max-w-[240px]" title="{{ $module->course?->name }}">
                                                        {{ $module->course?->name ?? '—' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="font-bold text-gray-800">{{ $module->name }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                @if ($module->credits)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                                        {{ $module->credits }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 text-xs">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                <a href="{{ route('admin.itineraries.index', ['module_id' => $module->id]) }}"
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-gray-100 hover:bg-purple-100 hover:text-purple-700 text-gray-700 text-xs font-bold transition">
                                                    <i class="bi bi-diagram-3"></i> {{ $module->itineraries_count }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                <form action="{{ route('admin.modules.toggle-status', $module) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold transition {{ $module->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $module->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        {{ $module->is_active ? 'Activo' : 'Inactivo' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <button type="button" @click="openEditModal({{ json_encode($module) }})"
                                                        class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                                        title="Editar Módulo">
                                                        <i class="bi bi-pencil-square text-base"></i>
                                                    </button>
                                                    <form action="{{ route('admin.modules.destroy', $module) }}" method="POST"
                                                        onsubmit="return confirm('¿Está seguro de eliminar el módulo «{{ addslashes($module->name) }}»?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                            title="Eliminar Módulo">
                                                            <i class="bi bi-trash3 text-base"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($modules->hasPages())
                            <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
                                <p class="text-xs text-gray-500">
                                    Mostrando <strong>{{ $modules->firstItem() }}</strong>–<strong>{{ $modules->lastItem() }}</strong> de <strong>{{ $modules->total() }}</strong> módulos
                                </p>
                                {{ $modules->links() }}
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </main>

        {{-- ══ MODAL CREATE / EDIT ═══════════════════════════════════════ --}}
        <div x-show="modalOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">

            <div @click.outside="modalOpen = false"
                @keydown.escape.window="modalOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg overflow-hidden">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                            <i :class="isEdit ? 'bi-pencil-square' : 'bi-plus-circle-fill'"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900" x-text="isEdit ? 'Editar Módulo' : 'Nuevo Módulo'"></h3>
                            <p class="text-xs text-gray-500">Asigne el módulo al curso correspondiente</p>
                        </div>
                    </div>
                    <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form :action="isEdit ? updateUrl : '{{ route('admin.modules.store') }}'" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- Course Select --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Curso <span class="text-red-500">*</span>
                        </label>
                        <select name="course_id" x-model="form.course_id" required
                            class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium">
                            <option value="">-- Seleccione un curso --</option>
                            @foreach ($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Name --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Nombre del Módulo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" x-model="form.name" required maxlength="255"
                            placeholder="Ej: Módulo I: Diagnóstico y Reparación de Hardware"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-medium">
                    </div>

                    {{-- Credits / Hours --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Créditos / Horas
                        </label>
                        <input type="text" name="credits" x-model="form.credits" maxlength="50"
                            placeholder="Ej: 4 créditos / 64 horas"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-medium">
                    </div>

                    {{-- Active Toggle --}}
                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:width-5 after:transition-all peer-checked:bg-purple-600"></div>
                            <span class="ml-3 text-xs font-bold text-gray-700">Módulo Activo</span>
                        </label>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="modalOpen = false"
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-sm font-bold shadow transition flex items-center gap-2">
                            <i class="bi bi-check2"></i>
                            <span x-text="isEdit ? 'Guardar Cambios' : 'Registrar Módulo'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function modulesApp() {
        return {
            modalOpen: false,
            isEdit: false,
            updateUrl: '',
            form: {
                id: null,
                course_id: '{{ request('course_id', '') }}',
                name: '',
                credits: '',
                is_active: true,
            },

            openCreateModal() {
                this.isEdit = false;
                this.updateUrl = '';
                this.form = {
                    id: null,
                    course_id: '{{ request('course_id', '') }}',
                    name: '',
                    credits: '',
                    is_active: true,
                };
                this.modalOpen = true;
            },

            openEditModal(module) {
                this.isEdit = true;
                this.updateUrl = `{{ url('admin-modulos') }}/${module.id}`;
                this.form = {
                    id: module.id,
                    course_id: module.course_id || '',
                    name: module.name || '',
                    credits: module.credits || '',
                    is_active: Boolean(module.is_active),
                };
                this.modalOpen = true;
            }
        }
    }
</script>
@endpush
