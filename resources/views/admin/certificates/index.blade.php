@extends('layouts.app')
@section('title', 'Gestión de Certificados — Panel Administrativo')

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

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative" x-data="certificatesApp()">

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
                            <i class="bi bi-patch-check text-purple-600"></i> Certificados Emitidos
                        </h1>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">Emisión, validación y gestión de notas modulares de certificados</p>
                    </div>
                </div>
                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-book mr-1 text-purple-500"></i> Programas
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Certificados</span>
                </div>
            </div>

            {{-- Navigation Tabs --}}
            <div class="px-4 sm:px-6 border-t border-gray-100 bg-gray-50/60 flex items-center gap-1 overflow-x-auto">
                <a href="{{ route('admin.programs.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap">
                    <i class="bi bi-mortarboard"></i> Programas de Estudio
                </a>
                <a href="{{ route('admin.certificates.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-purple-600 text-purple-700 whitespace-nowrap">
                    <i class="bi bi-patch-check"></i> Certificados
                </a>
                <a href="{{ route('admin.courses.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap">
                    <i class="bi bi-journal-bookmark"></i> Cursos
                </a>
                <a href="{{ route('admin.modules.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap">
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
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Certificados</p>
                            <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalCertificates) }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Certificados Válidos</p>
                            <h3 class="text-2xl font-black text-emerald-700">{{ number_format($activeCertificates) }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-cloud-arrow-down-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Descargas</p>
                            <h3 class="text-2xl font-black text-blue-700">{{ number_format($totalDownloads) }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-journal-check"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Cursos Certificados</p>
                            <h3 class="text-2xl font-black text-indigo-700">{{ number_format($issuedCoursesCount) }}</h3>
                        </div>
                    </div>
                </div>

                {{-- ── Filters & Actions ── --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 space-y-4">
                    <form action="{{ route('admin.certificates.index') }}" method="GET"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">

                        {{-- Search --}}
                        <div class="lg:col-span-4 relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar por código, estudiante o DNI..."
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        {{-- Course Filter --}}
                        <div class="lg:col-span-3">
                            <select name="course_id" onchange="this.form.submit()"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium truncate">
                                <option value="">Curso: Todos</option>
                                @foreach ($courses as $c)
                                    <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>
                                        [{{ $c->code }}] {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div class="lg:col-span-2">
                            <select name="status" onchange="this.form.submit()"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                <option value="">Estado: Todos</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Válidos (Activos)</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="lg:col-span-3 flex items-center gap-2 justify-end">
                            <button type="submit"
                                class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center justify-center gap-1.5 flex-1">
                                <i class="bi bi-funnel-fill"></i> Filtrar
                            </button>
                            @if (request()->hasAny(['search', 'course_id', 'status']))
                                <a href="{{ route('admin.certificates.index') }}"
                                    class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition-all"
                                    title="Limpiar Filtros">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            @endif
                        </div>
                    </form>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
                        <span class="text-xs font-semibold text-gray-500">
                            Mostrando {{ $certificates->total() }} certificados registrados
                        </span>
                        <button type="button" @click="openCreateModal()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
                            <i class="bi bi-plus-lg"></i> Nuevo Certificado
                        </button>
                    </div>
                </div>

                {{-- ── Table ── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    @if ($certificates->isEmpty())
                        <div class="flex flex-col items-center justify-center py-20 gap-4 text-gray-400">
                            <div class="w-16 h-16 rounded-2xl bg-purple-50 text-purple-400 flex items-center justify-center text-3xl">
                                <i class="bi bi-patch-check"></i>
                            </div>
                            <div class="text-center">
                                <p class="font-bold text-gray-700">Sin certificados registrados</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    @if (request()->hasAny(['search', 'course_id', 'status']))
                                        No hay certificados con los filtros aplicados.
                                        <a href="{{ route('admin.certificates.index') }}" class="text-purple-600 font-semibold hover:underline">Limpiar filtros</a>
                                    @else
                                        Emita el primer certificado haciendo clic en <strong>Nuevo Certificado</strong>.
                                    @endif
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Código</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estudiante / DNI</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Curso</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Emisión</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Notas / Módulos</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($certificates as $cert)
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="font-mono font-bold text-purple-700 text-xs bg-purple-50 border border-purple-200 px-2 py-1 rounded-lg">
                                                    {{ $cert->certificate_code }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="font-bold text-gray-800">{{ $cert->user?->names ?? 'Usuario no asignado' }}</div>
                                                <div class="text-xs text-gray-500 font-mono">DNI: {{ $cert->user?->dni ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="font-semibold text-gray-800 text-xs">{{ $cert->course?->name ?? '—' }}</div>
                                                <div class="text-[11px] text-purple-600 font-mono font-bold">[{{ $cert->course?->code ?? 'N/A' }}]</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                                <div class="font-semibold">{{ $cert->issue_date ? \Carbon\Carbon::parse($cert->issue_date)->format('d/m/Y') : '—' }}</div>
                                                @if ($cert->duration)
                                                    <div class="text-[11px] text-gray-400">{{ $cert->duration }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                <button type="button" @click="openDetailsModal({{ json_encode($cert) }})"
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold transition {{ $cert->details->count() > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                    <i class="bi bi-card-checklist"></i> {{ $cert->details->count() }} notas
                                                </button>
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                <form action="{{ route('admin.certificates.toggle-status', $cert) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold transition {{ $cert->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $cert->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        {{ $cert->is_active ? 'Válido' : 'Inactivo' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <button type="button" @click="openDetailsModal({{ json_encode($cert) }})"
                                                        class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                                                        title="Ver / Gestionar Calificaciones">
                                                        <i class="bi bi-eye text-base"></i>
                                                    </button>
                                                    <button type="button" @click="openEditModal({{ json_encode($cert) }})"
                                                        class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                                        title="Editar Certificado">
                                                        <i class="bi bi-pencil-square text-base"></i>
                                                    </button>
                                                    <form action="{{ route('admin.certificates.destroy', $cert) }}" method="POST"
                                                        onsubmit="return confirm('¿Está seguro de eliminar el certificado «{{ addslashes($cert->certificate_code) }}»?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                            title="Eliminar Certificado">
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
                        @if ($certificates->hasPages())
                            <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
                                <p class="text-xs text-gray-500">
                                    Mostrando <strong>{{ $certificates->firstItem() }}</strong>–<strong>{{ $certificates->lastItem() }}</strong> de <strong>{{ $certificates->total() }}</strong> certificados
                                </p>
                                {{ $certificates->links() }}
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </main>

        {{-- ══ MODAL CREATE / EDIT CERTIFICATE ═══════════════════════════ --}}
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
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-2xl overflow-hidden max-h-[90vh] flex flex-col">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                            <i :class="isEdit ? 'bi-pencil-square' : 'bi-patch-check-fill'"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900" x-text="isEdit ? 'Editar Certificado' : 'Nuevo Certificado'"></h3>
                            <p class="text-xs text-gray-500">Emisión de certificado para estudiante con código único</p>
                        </div>
                    </div>
                    <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form :action="isEdit ? updateUrl : '{{ route('admin.certificates.store') }}'" method="POST" class="p-6 space-y-4 overflow-y-auto flex-1">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- Student & Course Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Estudiante / Usuario <span class="text-red-500">*</span>
                            </label>
                            <select name="user_id" x-model="form.user_id" required
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium">
                                <option value="">-- Seleccione estudiante --</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->names }} (DNI: {{ $u->dni ?? 'S/D' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Curso <span class="text-red-500">*</span>
                            </label>
                            <select name="course_id" x-model="form.course_id" required
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium">
                                <option value="">-- Seleccione curso --</option>
                                @foreach ($courses as $c)
                                    <option value="{{ $c->id }}">[{{ $c->code }}] {{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Code & Duration Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Código de Certificado <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="certificate_code" x-model="form.certificate_code" required maxlength="100"
                                    placeholder="Ej: CERT-2026-001"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 uppercase font-mono font-bold">
                                <button type="button" @click="generateCode()"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-bold text-purple-600 hover:text-purple-800 bg-purple-50 px-2 py-0.5 rounded">
                                    Generar
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Duración / Horas
                            </label>
                            <input type="text" name="duration" x-model="form.duration" maxlength="100"
                                placeholder="Ej: 120 Horas Académicas"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-medium">
                        </div>
                    </div>

                    {{-- Dates Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Fecha Inicio
                            </label>
                            <input type="date" name="start_date" x-model="form.start_date"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Fecha Fin
                            </label>
                            <input type="date" name="end_date" x-model="form.end_date"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Fecha Emisión <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="issue_date" x-model="form.issue_date" required
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-bold">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Descripción / Mención
                        </label>
                        <textarea name="description" x-model="form.description" rows="2" maxlength="500"
                            placeholder="Por haber aprobado satisfactoriamente el curso de..."
                            class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"></textarea>
                    </div>

                    {{-- Active Toggle --}}
                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:width-5 after:transition-all peer-checked:bg-purple-600"></div>
                            <span class="ml-3 text-xs font-bold text-gray-700">Certificado Válido / Activo</span>
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
                            <span x-text="isEdit ? 'Guardar Cambios' : 'Emitir Certificado'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══ MODAL VIEW & MANAGE MODULE SCORES (DETAILS) ═════════════ --}}
        <div x-show="detailsModalOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">

            <div @click.outside="detailsModalOpen = false"
                @keydown.escape.window="detailsModalOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-2xl overflow-hidden max-h-[90vh] flex flex-col">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                            <i class="bi bi-card-checklist"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900">Calificaciones por Módulo</h3>
                            <p class="text-xs text-gray-500" x-text="'Certificado: ' + (activeCert?.certificate_code || '')"></p>
                        </div>
                    </div>
                    <button type="button" @click="detailsModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-6 space-y-5 overflow-y-auto flex-1">
                    {{-- Certificate Summary Card --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex flex-wrap items-center justify-between gap-3 text-xs">
                        <div>
                            <span class="text-gray-500 font-semibold block">Estudiante:</span>
                            <span class="font-bold text-gray-800 text-sm" x-text="activeCert?.user?.names || 'N/A'"></span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold block">Curso:</span>
                            <span class="font-bold text-purple-700 text-sm" x-text="activeCert?.course?.name || 'N/A'"></span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold block">Fecha Emisión:</span>
                            <span class="font-bold text-gray-700" x-text="activeCert?.issue_date || 'N/A'"></span>
                        </div>
                    </div>

                    {{-- Add / Update Score Form --}}
                    <form :action="activeCert ? `{{ url('admin-certificados') }}/${activeCert.id}/detalles` : '#'" method="POST"
                        class="bg-purple-50/50 border border-purple-200 rounded-xl p-4 space-y-3">
                        @csrf
                        <div class="text-xs font-bold text-purple-900 uppercase tracking-wider">
                            <i class="bi bi-plus-circle-fill mr-1"></i> Asignar Nota de Módulo
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                            <div class="sm:col-span-7">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Módulo *</label>
                                <select name="module_id" required
                                    class="w-full text-xs border border-gray-300 rounded-lg py-2 px-2.5 bg-white font-medium">
                                    <option value="">-- Seleccionar Módulo --</option>
                                    <template x-for="mod in availableModules" :key="mod.id">
                                        <option :value="mod.id" x-text="mod.name + (mod.credits ? ' (' + mod.credits + ')' : '')"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="sm:col-span-3">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Calificación / Nota</label>
                                <input type="text" name="score" placeholder="Ej: 18, Aprobado" maxlength="50"
                                    class="w-full text-xs border border-gray-300 rounded-lg py-2 px-2.5 font-bold">
                            </div>
                            <div class="sm:col-span-2">
                                <button type="submit"
                                    class="w-full py-2 px-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold shadow transition flex items-center justify-center gap-1">
                                    <i class="bi bi-save"></i> Guardar
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- List of Current Details --}}
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Módulos Registrados</h4>
                        <template x-if="!activeCert?.details || activeCert.details.length === 0">
                            <div class="p-6 text-center text-xs text-gray-400 border-2 border-dashed border-gray-200 rounded-xl">
                                No se han registrado notas modulares para este certificado aún.
                            </div>
                        </template>
                        <template x-if="activeCert?.details && activeCert.details.length > 0">
                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <table class="min-w-full text-xs">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-3 py-2 text-left font-bold text-gray-600 uppercase">Módulo</th>
                                            <th class="px-3 py-2 text-center font-bold text-gray-600 uppercase">Créditos</th>
                                            <th class="px-3 py-2 text-center font-bold text-gray-600 uppercase">Calificación</th>
                                            <th class="px-3 py-2 text-center font-bold text-gray-600 uppercase">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <template x-for="d in activeCert.details" :key="d.id">
                                            <tr class="hover:bg-gray-50/80">
                                                <td class="px-3 py-2 font-semibold text-gray-800" x-text="d.module?.name || 'Módulo #' + d.module_id"></td>
                                                <td class="px-3 py-2 text-center text-gray-500" x-text="d.module?.credits || '—'"></td>
                                                <td class="px-3 py-2 text-center">
                                                    <span class="px-2 py-0.5 rounded-full font-bold bg-purple-100 text-purple-800" x-text="d.score || 'Aprobado'"></span>
                                                </td>
                                                <td class="px-3 py-2 text-center">
                                                    <form :action="`{{ url('admin-certificados/detalles') }}/${d.id}`" method="POST"
                                                        onsubmit="return confirm('¿Eliminar esta calificación?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex justify-end shrink-0">
                    <button type="button" @click="detailsModalOpen = false"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl text-xs font-bold transition">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function certificatesApp() {
        return {
            modalOpen: false,
            detailsModalOpen: false,
            isEdit: false,
            updateUrl: '',
            activeCert: null,
            availableModules: [],
            form: {
                id: null,
                user_id: '',
                course_id: '',
                certificate_code: '',
                description: '',
                start_date: '',
                end_date: '',
                duration: '',
                issue_date: '{{ date('Y-m-d') }}',
                is_active: true,
            },

            openCreateModal() {
                this.isEdit = false;
                this.updateUrl = '';
                this.form = {
                    id: null,
                    user_id: '',
                    course_id: '',
                    certificate_code: this.generateCodeStr(),
                    description: '',
                    start_date: '',
                    end_date: '',
                    duration: '120 Horas',
                    issue_date: '{{ date('Y-m-d') }}',
                    is_active: true,
                };
                this.modalOpen = true;
            },

            openEditModal(cert) {
                this.isEdit = true;
                this.updateUrl = `{{ url('admin-certificados') }}/${cert.id}`;
                this.form = {
                    id: cert.id,
                    user_id: cert.user_id || '',
                    course_id: cert.course_id || '',
                    certificate_code: cert.certificate_code || '',
                    description: cert.description || '',
                    start_date: cert.start_date ? cert.start_date.substring(0, 10) : '',
                    end_date: cert.end_date ? cert.end_date.substring(0, 10) : '',
                    duration: cert.duration || '',
                    issue_date: cert.issue_date ? cert.issue_date.substring(0, 10) : '{{ date('Y-m-d') }}',
                    is_active: Boolean(cert.is_active),
                };
                this.modalOpen = true;
            },

            openDetailsModal(cert) {
                this.activeCert = cert;
                this.availableModules = cert.course?.modules || [];
                this.detailsModalOpen = true;
            },

            generateCodeStr() {
                const year = new Date().getFullYear();
                const rand = Math.floor(1000 + Math.random() * 9000);
                return `CERT-FVC-${year}-${rand}`;
            },

            generateCode() {
                this.form.certificate_code = this.generateCodeStr();
            }
        }
    }
</script>
@endpush
