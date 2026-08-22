@extends('layouts.app')
@section('title', 'Consejo Estudiantil — Panel Administrativo')

@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        .pagination {
            display: flex;
            gap: 4px;
        }

        .pagination .page-item .page-link {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .pagination .page-item.active .page-link {
            background-color: #9333ea;
            color: white;
        }

        .pagination .page-item .page-link:hover {
            background-color: #f3f4f6;
        }

        .pagination .page-item.active .page-link:hover {
            background-color: #7e22ce;
        }
    </style>
@endpush

@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="enterpriseApp()">
        @include('admin.components.aside')

        <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative" x-data="studentCouncilApp()">

            {{-- ── Page Header ── --}}
            <header
                class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button @click="toggleSidebar()"
                            class="text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                            <i class="bi bi-list text-xl sm:text-2xl"></i>
                        </button>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight leading-none">
                                Consejo Estudiantil
                            </h1>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">Gestión de representantes y secretarías del
                                alumnado</p>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <a href="{{ route('admin.dashboard.index') }}" class="hover:text-purple-600 transition-colors">
                            <i class="bi bi-house-door mr-1"></i> Dashboard
                        </a>
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600 font-semibold">Consejo Estudiantil</span>
                    </div>
                </div>
            </header>

            {{-- ── Main Content ── --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
                <div class="max-w-7xl mx-auto space-y-6">

                    {{-- Flash Messages --}}
                    @if (session('success'))
                        <div
                            class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-green-500 text-xl"></i>
                                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-fade-in flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-exclamation-circle-fill text-red-500 text-xl"></i>
                                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif

                    {{-- Validation Errors Banner --}}
                    @if (isset($errors) && $errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-fade-in">
                            <div class="flex items-center gap-2 mb-2 text-red-700 font-bold text-sm">
                                <i class="bi bi-exclamation-triangle-fill text-red-500"></i>
                                Por favor corrige los siguientes errores:
                            </div>
                            <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ── Metric Cards ── --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <p class="text-2xl font-black text-purple-700">{{ $councils->total() }}</p>
                            <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-wider">Miembros Registrados
                            </p>
                        </div>
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <p class="text-2xl font-black text-blue-700">
                                {{ \App\Models\StudentCouncil::where('position', 'LIKE', '%presidente%')->count() }}
                            </p>
                            <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-wider">Directiva Principal</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <p class="text-2xl font-black text-emerald-700">
                                {{ \App\Models\StudentCouncil::where('is_active', true)->count() }}
                            </p>
                            <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-wider">Miembros Activos</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <p class="text-2xl font-black text-amber-600">
                                {{ $periods->count() }}
                            </p>
                            <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-wider">Períodos Lectivos</p>
                        </div>
                    </div>

                    {{-- ── Search & Filters Bar ── --}}
                    <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border border-gray-200 space-y-4">
                        <form action="{{ route('admin.student-council.index') }}" method="GET" class="w-full">
                            <div class="flex flex-col sm:flex-row gap-3">
                                {{-- Text Search --}}
                                <div class="flex-1 relative">
                                    <input type="text" name="search" value="{{ $search }}"
                                        placeholder="Buscar por nombre, cargo, DNI o período..."
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>

                                {{-- Academic Period Filter --}}
                                <div class="relative">
                                    <select name="period"
                                        class="appearance-none w-full sm:w-48 pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm bg-white"
                                        onchange="this.form.submit()">
                                        <option value="">Todos los Períodos</option>
                                        @foreach ($periods as $p)
                                            <option value="{{ $p }}" {{ $period == $p ? 'selected' : '' }}>
                                                Gestión {{ $p }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i
                                        class="bi bi-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                </div>

                                {{-- Program Filter --}}
                                <div class="relative">
                                    <select name="program_id"
                                        class="appearance-none w-full sm:w-52 pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm bg-white"
                                        onchange="this.form.submit()">
                                        <option value="">Todos los programas</option>
                                        @foreach ($programs as $prog)
                                            <option value="{{ $prog->id }}"
                                                {{ $programId == $prog->id ? 'selected' : '' }}>
                                                {{ $prog->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i
                                        class="bi bi-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                </div>

                                {{-- Filter Submit Button --}}
                                <button type="submit"
                                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm transition">
                                    <i class="bi bi-funnel mr-1"></i> Filtrar
                                </button>
                            </div>
                        </form>

                        <div class="flex items-center justify-between gap-3 pt-1 flex-wrap">
                            <p class="text-sm text-gray-500">
                                <span class="font-bold text-gray-900">{{ $councils->total() }}</span> registros encontrados
                                @if ($search || $period || $programId)
                                    — <a href="{{ route('admin.student-council.index') }}"
                                        class="text-purple-600 hover:underline">Limpiar filtros</a>
                                @endif
                            </p>

                            <button @click="openCreateModal()"
                                class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg transition shadow-sm font-semibold text-sm">
                                <i class="bi bi-plus-lg"></i> Agregar Representante
                            </button>
                        </div>
                    </div>

                    {{-- ── Data Table ── --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse min-w-[850px]">
                                <thead>
                                    <tr
                                        class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                        <th class="p-4">Integrante / Usuario</th>
                                        <th class="p-4">Cargo o Posición</th>
                                        <th class="p-4">Programa de Estudios</th>
                                        <th class="p-4 text-center">Gestión</th>
                                        <th class="p-4 text-center">Estado</th>
                                        <th class="p-4 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($councils as $item)
                                        @php
                                            $user = $item->user;
                                            $prog = $item->studyProgram;
                                            $displayName = $user->names ?? $item->name;
                                            $photo = $user?->photo_profile ? Storage::url($user->photo_profile) : null;
                                            $isBoard = str_contains(
                                                mb_strtolower($item->position, 'UTF-8'),
                                                'presidente',
                                            );
                                        @endphp
                                        <tr class="hover:bg-gray-50/60 transition-colors group">
                                            {{-- Member details --}}
                                            <td class="p-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-sm shrink-0 overflow-hidden border border-purple-200">
                                                        @if ($photo)
                                                            <img src="{{ $photo }}" alt="{{ $displayName }}"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            {{ strtoupper(substr($displayName, 0, 2)) }}
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold text-gray-900 leading-tight">
                                                            {{ $displayName }}</p>
                                                        <p class="text-xs text-gray-400 font-medium">
                                                            DNI: {{ $user->dni ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Position --}}
                                            <td class="p-4">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-extrabold border {{ $isBoard ? 'bg-purple-100 text-purple-800 border-purple-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                                    <i
                                                        class="bi {{ $isBoard ? 'bi-award-fill text-purple-600' : 'bi-shield-check text-slate-500' }}"></i>
                                                    {{ $item->position }}
                                                </span>
                                            </td>

                                            {{-- Program --}}
                                            <td class="p-4">
                                                @if ($prog)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 text-xs font-bold border border-blue-100">
                                                        <i class="bi {{ $prog->icon ?? 'bi-mortarboard' }}"></i>
                                                        {{ $prog->name }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">General /
                                                        Institucional</span>
                                                @endif
                                            </td>

                                            {{-- Academic Period --}}
                                            <td class="p-4 text-center">
                                                <span
                                                    class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-800 border border-amber-200 text-xs font-extrabold">
                                                    {{ $item->academic_period }}
                                                </span>
                                            </td>

                                            {{-- Status --}}
                                            <td class="p-4 text-center">
                                                <button @click="toggleStatus({{ $item->id }})"
                                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                                    :class="activeStates[{{ $item->id }}] ? 'bg-emerald-500' :
                                                        'bg-gray-200'">
                                                    <span
                                                        class="inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                        :class="activeStates[{{ $item->id }}] ? 'translate-x-5' :
                                                            'translate-x-0'"></span>
                                                </button>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="p-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button @click="openEditModal({{ json_encode($item) }})"
                                                        class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                                                        title="Editar registro">
                                                        <i class="bi bi-pencil-square text-base"></i>
                                                    </button>
                                                    <button
                                                        @click="confirmDelete({{ $item->id }}, '{{ addslashes($displayName) }}')"
                                                        class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                        title="Eliminar registro">
                                                        <i class="bi bi-trash text-base"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-12 text-center text-gray-400">
                                                <i class="bi bi-people text-4xl block mb-2 opacity-50"></i>
                                                <p class="text-sm font-medium">No se encontraron miembros en el Consejo de
                                                    Estudiantes.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($councils->hasPages())
                            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                                {{ $councils->links() }}
                            </div>
                        @endif
                    </div>

                </div>
            </main>

            {{-- ═══ CREATE / EDIT MODAL ════════════════════════════════════════════ --}}
            <div x-show="showModal" x-cloak
                class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-gray-100 overflow-hidden transform transition-all"
                    @click.away="closeModal()">

                    {{-- Modal Header --}}
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-purple-700 to-indigo-800 text-white flex items-center justify-between">
                        <h3 class="text-lg font-extrabold flex items-center gap-2"
                            x-text="isEdit ? 'Editar Representante' : 'Nuevo Representante del Consejo'"></h3>
                        <button @click="closeModal()" class="text-white/80 hover:text-white p-1 rounded-lg">
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                    </div>

                    {{-- Modal Form --}}
                    <form
                        :action="isEdit ? '{{ url('admin-consejo-estudiantil') }}/' + form.id :
                            '{{ route('admin.student-council.store') }}'"
                        method="POST" class="p-6 space-y-4">
                        @csrf
                        <template x-if="isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        {{-- User Select --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                Integrante / Usuario <span class="text-red-500">*</span>
                            </label>
                            <select name="user_id" x-model="form.user_id" required
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                <option value="">-- Seleccionar Usuario --</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}">
                                        {{ $u->names }} (DNI: {{ $u->dni }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Position / Cargo --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                Cargo o Posición <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="position" x-model="form.position" required
                                placeholder="Ej. Presidente, Secretaria de Organización..."
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>

                        {{-- Academic Period & Study Program --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                    Período Académico <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="academic_period" x-model="form.academic_period" required
                                    placeholder="Ej. 2026-2027"
                                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                    Programa de Estudios
                                </label>
                                <select name="study_program_id" x-model="form.study_program_id"
                                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    <option value="">-- Opcional / General --</option>
                                    @foreach ($programs as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Optional Custom Name --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                Nombre Personalizado <span class="text-gray-400 font-normal">(Opcional)</span>
                            </label>
                            <input type="text" name="name" x-model="form.name"
                                placeholder="Se autocompleta con el nombre del usuario si se deja vacío"
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>

                        {{-- Status Toggle --}}
                        <div class="flex items-center gap-2 pt-2">
                            <input type="checkbox" id="is_active_input" name="is_active" value="1"
                                x-model="form.is_active"
                                class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <label for="is_active_input" class="text-sm font-semibold text-gray-700">
                                Marcar integrante como activo en la plataforma
                            </label>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                            <button type="button" @click="closeModal()"
                                class="px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-5 py-2 text-sm font-extrabold text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition shadow-md">
                                <span x-text="isEdit ? 'Guardar Cambios' : 'Registrar Miembro'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function studentCouncilApp() {
                return {
                    showModal: false,
                    isEdit: false,
                    activeStates: {
                        @foreach ($councils as $item)
                            {{ $item->id }}: {{ $item->is_active ? 'true' : 'false' }},
                        @endforeach
                    },
                    form: {
                        id: null,
                        user_id: '',
                        study_program_id: '',
                        name: '',
                        position: '',
                        academic_period: '2026-2027',
                        is_active: true
                    },

                    openCreateModal() {
                        this.isEdit = false;
                        this.form = {
                            id: null,
                            user_id: '',
                            study_program_id: '',
                            name: '',
                            position: '',
                            academic_period: '2026-2027',
                            is_active: true
                        };
                        this.showModal = true;
                    },

                    openEditModal(data) {
                        this.isEdit = true;
                        this.form = {
                            id: data.id,
                            user_id: data.user_id || '',
                            study_program_id: data.study_program_id || '',
                            name: data.name || '',
                            position: data.position || '',
                            academic_period: data.academic_period || '2026-2027',
                            is_active: Boolean(data.is_active)
                        };
                        this.showModal = true;
                    },

                    closeModal() {
                        this.showModal = false;
                    },

                    toggleStatus(id) {
                        fetch(`{{ url('admin-consejo-estudiantil/estado') }}/${id}`, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    this.activeStates[id] = data.status;
                                }
                            })
                            .catch(err => console.error(err));
                    },

                    confirmDelete(id, name) {
                        if (confirm(`¿Estás seguro de eliminar a "${name}" del Consejo de Estudiantes?`)) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `{{ url('admin-consejo-estudiantil') }}/${id}`;
                            form.innerHTML = `
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                };
            }

            document.addEventListener('alpine:init', () => {
                if (!Alpine.data('enterpriseApp')) {
                    Alpine.data('enterpriseApp', () => ({
                        sidebarOpen: window.innerWidth >= 1024,
                        toggleSidebar() {
                            this.sidebarOpen = !this.sidebarOpen;
                        }
                    }));
                }
                Alpine.data('admissionApp', () => ({}));
            });
        </script>
    @endpush
@endsection
