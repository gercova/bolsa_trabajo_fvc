@extends('layouts.app')
@section('title', 'Editar Área Institucional - Panel Administrativo')

@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="dashboardApp()">
        @include('admin.components.aside')

        <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">
            {{-- Header --}}
            <header
                class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                            <i class="bi bi-list text-xl sm:text-2xl"></i>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                            <i class="bi bi-pencil-square text-purple-600"></i> Editar Área Institucional
                        </h1>
                    </div>

                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <a href="{{ route('admin.areas.index') }}"
                            class="hover:text-purple-600 transition-colors flex items-center gap-1">
                            <i class="bi bi-diagram-3"></i> Áreas
                        </a>
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600 font-semibold">Editar</span>
                    </div>
                </div>
            </header>

            {{-- Form Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
                <div class="max-w-4xl mx-auto space-y-6">

                    {{-- Back Link --}}
                    <div>
                        <a href="{{ route('admin.areas.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-purple-600 transition-colors">
                            <i class="bi bi-arrow-left"></i> Volver a la lista de áreas
                        </a>
                    </div>

                    {{-- Card Container --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 sm:p-8 border-b border-gray-100 bg-gray-50/40 flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                    <i class="bi bi-pencil-square text-purple-600"></i> Modificar Área: {{ $area->name }}
                                </h2>
                                <p class="text-xs text-gray-500 mt-1">Actualice la información y asignaciones del área
                                    institucional.</p>
                            </div>
                            <span
                                class="text-xs px-3 py-1 bg-purple-50 text-purple-700 font-semibold rounded-full border border-purple-200">
                                ID: #{{ $area->id }}
                            </span>
                        </div>

                        <form action="{{ route('admin.areas.update', $area) }}" method="POST" class="p-6 sm:p-8 space-y-6">
                            @csrf
                            @method('PUT')

                            {{-- Name Field --}}
                            <div class="space-y-1.5">
                                <label for="name" class="block text-sm font-semibold text-gray-700">
                                    Nombre del Área o Dependencia <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $area->name) }}"
                                    required maxlength="100"
                                    placeholder="Ej. Secretaría Académica, Jefatura de Unidad Académica, etc."
                                    class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all @error('name') border-red-500 bg-red-50/30 @else border-gray-300 @enderror">
                                @error('name')
                                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- 2 Columns: Program & Searchable Responsible User --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Program Field --}}
                                <div class="space-y-1.5">
                                    <label for="program_id" class="block text-sm font-semibold text-gray-700">
                                        Programa de Estudio Asociado
                                    </label>
                                    <select name="program_id" id="program_id"
                                        class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white @error('program_id') border-red-500 bg-red-50/30 @else border-gray-300 @enderror">
                                        <option value="">Institucional General (Sin programa específico)</option>
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->id }}"
                                                {{ old('program_id', $area->program_id) == $program->id ? 'selected' : '' }}>
                                                {{ $program->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-[11px] text-gray-400">Seleccione si el área pertenece exclusivamente a un
                                        programa de estudio.</p>
                                    @error('program_id')
                                        <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Searchable User Field (Select2-like without npm / external dependencies) --}}
                                <div class="space-y-1.5"
                                    x-data="searchableSelectUser({
                                        users: {{ $users->map(fn($u) => [
                                            'id' => (string) $u->id,
                                            'name' => $u->names . ($u->last_name1 ? ' ' . $u->last_name1 : '') . ($u->last_name2 ? ' ' . $u->last_name2 : ''),
                                            'email' => $u->email,
                                            'role' => $u->job_position ?? $u->role ?? 'Personal',
                                        ])->values()->toJson() }},
                                        selectedId: '{{ old('user_id', (string) ($area->user_id ?? '')) }}'
                                    })"
                                    @click.outside="open = false">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Responsable / Encargado
                                    </label>

                                    {{-- Hidden input sent with form submission --}}
                                    <input type="hidden" name="user_id" :value="selectedId">

                                    {{-- Custom Select Trigger --}}
                                    <div class="relative">
                                        <button type="button" @click="toggleDropdown()"
                                            class="w-full px-4 py-2.5 text-left text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white flex items-center justify-between shadow-sm cursor-pointer @error('user_id') border-red-500 bg-red-50/30 @else border-gray-300 @enderror">
                                            
                                            <div class="flex items-center gap-2.5 overflow-hidden truncate">
                                                <template x-if="selectedUser">
                                                    <div class="flex items-center gap-2 truncate">
                                                        <div class="w-6 h-6 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-[10px] font-bold flex-shrink-0"
                                                            x-text="selectedUser.name.charAt(0).toUpperCase()"></div>
                                                        <span class="font-medium text-gray-800 truncate" x-text="selectedUser.name"></span>
                                                        <span class="text-xs text-gray-400 truncate" x-text="'(' + selectedUser.email + ')'"></span>
                                                    </div>
                                                </template>
                                                <template x-if="!selectedUser">
                                                    <span class="text-gray-500 flex items-center gap-1.5 text-sm">
                                                        <i class="bi bi-person-dash text-gray-400"></i>
                                                        <span>Sin responsable asignado</span>
                                                    </span>
                                                </template>
                                            </div>

                                            <div class="flex items-center gap-1 text-gray-400 flex-shrink-0 ml-2">
                                                <template x-if="selectedId">
                                                    <span role="button" @click.stop="clearSelection()"
                                                        class="p-1 hover:text-red-500 hover:bg-red-50 rounded-md transition-colors"
                                                        title="Limpiar selección">
                                                        <i class="bi bi-x-lg text-xs"></i>
                                                    </span>
                                                </template>
                                                <i class="bi bi-chevron-expand text-sm"></i>
                                            </div>
                                        </button>

                                        {{-- Dropdown Container --}}
                                        <div x-show="open" style="display: none;"
                                            x-transition:enter="transition ease-out duration-150"
                                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-100"
                                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                            class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 overflow-hidden">
                                            
                                            {{-- Search Input --}}
                                            <div class="p-2.5 border-b border-gray-100 bg-gray-50/70">
                                                <div class="relative">
                                                    <input type="text" x-ref="searchInput" x-model="search"
                                                        placeholder="Buscar por nombre, correo o cargo..."
                                                        @keydown.escape="open = false"
                                                        class="w-full pl-9 pr-4 py-2 text-xs border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white outline-none">
                                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                                </div>
                                            </div>

                                            {{-- Options List (Max 5 items) --}}
                                            <ul class="max-h-56 overflow-y-auto divide-y divide-gray-50 py-1 text-xs">
                                                {{-- Default "Sin Responsable" option --}}
                                                <li>
                                                    <button type="button" @click="selectUser('')"
                                                        class="w-full px-3.5 py-2.5 text-left flex items-center justify-between hover:bg-purple-50 transition-colors"
                                                        :class="!selectedId ? 'bg-purple-50 font-semibold text-purple-700' : 'text-gray-600'">
                                                        <span class="flex items-center gap-2">
                                                            <i class="bi bi-dash-circle text-gray-400"></i>
                                                            <span>Sin responsable asignado</span>
                                                        </span>
                                                        <template x-if="!selectedId">
                                                            <i class="bi bi-check2 text-purple-600 text-sm font-bold"></i>
                                                        </template>
                                                    </button>
                                                </li>

                                                {{-- User Items (Capped at 5 items) --}}
                                                <template x-for="user in limitedUsers" :key="user.id">
                                                    <li>
                                                        <button type="button" @click="selectUser(user.id)"
                                                            class="w-full px-3.5 py-2.5 text-left flex items-center justify-between hover:bg-purple-50 transition-colors"
                                                            :class="selectedId == user.id ? 'bg-purple-50 font-semibold text-purple-700' : 'text-gray-700'">
                                                            <div class="flex items-center gap-2.5 overflow-hidden">
                                                                <div class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-700 flex items-center justify-center text-[10px] font-bold flex-shrink-0"
                                                                    x-text="user.name.charAt(0).toUpperCase()"></div>
                                                                <div class="leading-tight truncate">
                                                                    <p class="font-medium text-gray-800 truncate" x-text="user.name"></p>
                                                                    <p class="text-[11px] text-gray-400 truncate" x-text="user.email + (user.role ? ' • ' + user.role : '')"></p>
                                                                </div>
                                                            </div>
                                                            <template x-if="selectedId == user.id">
                                                                <i class="bi bi-check2 text-purple-600 text-sm font-bold ml-2"></i>
                                                            </template>
                                                        </button>
                                                    </li>
                                                </template>

                                                {{-- Empty State --}}
                                                <template x-if="filteredUsers.length === 0">
                                                    <li class="px-4 py-6 text-center text-xs text-gray-400">
                                                        <i class="bi bi-search text-base block mb-1"></i>
                                                        No se encontraron usuarios coincidentes
                                                    </li>
                                                </template>
                                            </ul>

                                            {{-- Footer with count information --}}
                                            <div class="px-3.5 py-2 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
                                                <span x-text="filteredUsers.length > 5 ? 'Mostrando 5 de ' + filteredUsers.length + ' resultados' : filteredUsers.length + ' resultados'"></span>
                                                <span class="text-[10px] text-purple-600 font-medium">Docentes / Administrativos</span>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-[11px] text-gray-400">Personal docente o administrativo encargado del área (máx. 5 sugerencias).</p>
                                    @error('user_id')
                                        <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Description Field --}}
                            <div class="space-y-1.5">
                                <label for="description" class="block text-sm font-semibold text-gray-700">
                                    Descripción del Área
                                </label>
                                <textarea name="description" id="description" rows="3" maxlength="1000"
                                    placeholder="Breve resumen del propósito, objetivos o alcance del área..."
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all @error('description') border-red-500 bg-red-50/30 @enderror">{{ old('description', $area->description) }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Details Field --}}
                            <div class="space-y-1.5">
                                <label for="details" class="block text-sm font-semibold text-gray-700">
                                    Funciones, Responsabilidades o Detalles Adicionales
                                </label>
                                <textarea name="details" id="details" rows="4"
                                    placeholder="Detalle de funciones reglamentarias, horarios de atención o información complementaria..."
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all @error('details') border-red-500 bg-red-50/30 @enderror">{{ old('details', $area->details) }}</textarea>
                                @error('details')
                                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Form Actions --}}
                            <div
                                class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                                <a href="{{ route('admin.areas.index') }}"
                                    class="w-full sm:w-auto px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition text-center">
                                    Cancelar
                                </a>
                                <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/25 hover:shadow-purple-600/35 transition-all duration-200 cursor-pointer">
                                    <i class="bi bi-check-lg text-lg"></i>
                                    <span>Actualizar Área</span>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </main>
        </div>
    </div>

    @push('scripts')
        <script>
            function searchableSelectUser(config) {
                return {
                    open: false,
                    search: '',
                    users: config.users || [],
                    selectedId: config.selectedId || '',

                    get selectedUser() {
                        return this.users.find(u => String(u.id) === String(this.selectedId)) || null;
                    },

                    get filteredUsers() {
                        if (!this.search.trim()) {
                            return this.users;
                        }
                        const q = this.search.toLowerCase();
                        return this.users.filter(u =>
                            (u.name && u.name.toLowerCase().includes(q)) ||
                            (u.email && u.email.toLowerCase().includes(q)) ||
                            (u.role && u.role.toLowerCase().includes(q))
                        );
                    },

                    get limitedUsers() {
                        return this.filteredUsers.slice(0, 5);
                    },

                    toggleDropdown() {
                        this.open = !this.open;
                        if (this.open) {
                            this.$nextTick(() => {
                                if (this.$refs.searchInput) {
                                    this.$refs.searchInput.focus();
                                }
                            });
                        }
                    },

                    selectUser(id) {
                        this.selectedId = id;
                        this.open = false;
                        this.search = '';
                    },

                    clearSelection() {
                        this.selectedId = '';
                        this.search = '';
                    }
                };
            }
        </script>
    @endpush
@endsection
