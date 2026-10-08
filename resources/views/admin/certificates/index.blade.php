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

                {{-- Import per-row errors --}}
                @if (session('import_errors'))
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl shadow-sm animate-fade-in">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill text-amber-600 text-lg"></i>
                                <p class="text-sm font-bold text-amber-900">Advertencias durante la importación ({{ count(session('import_errors')) }} fila(s) con problemas):</p>
                            </div>
                            <button type="button" class="text-amber-500 hover:text-amber-700" onclick="this.closest('[class*=bg-amber]').remove()">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                        <ul class="list-disc list-inside text-xs text-amber-800 space-y-1 max-h-40 overflow-y-auto">
                            @foreach (session('import_errors') as $importError)
                                <li>{{ $importError }}</li>
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
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Presencial</p>
                            <h3 class="text-2xl font-black text-blue-700">{{ number_format($presencialCount) }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Virtual / Semipres.</p>
                            <h3 class="text-2xl font-black text-indigo-700">{{ number_format($virtualSemipresCount) }}</h3>
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
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Modality Filter --}}
                        <div class="lg:col-span-2">
                            <select name="modality" onchange="this.form.submit()"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                <option value="">Modalidad: Todas</option>
                                <option value="Presencial" {{ request('modality') === 'Presencial' ? 'selected' : '' }}>Presencial</option>
                                <option value="Semipresencial" {{ request('modality') === 'Semipresencial' ? 'selected' : '' }}>Semipresencial</option>
                                <option value="Virtual" {{ request('modality') === 'Virtual' ? 'selected' : '' }}>Virtual</option>
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div class="lg:col-span-1">
                            <select name="status" onchange="this.form.submit()"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-2 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                <option value="">Estado</option>
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
                            @if (request()->hasAny(['search', 'course_id', 'modality', 'status']))
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
                        <div class="flex items-center gap-2">
                            {{-- Import Button --}}
                            <button type="button" @click="importModalOpen = true"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
                                <i class="bi bi-file-earmark-arrow-up"></i> Importar Excel / CSV
                            </button>
                            {{-- New Certificate Button --}}
                            <button type="button" @click="openCreateModal()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
                                <i class="bi bi-plus-lg"></i> Nuevo Certificado
                            </button>
                        </div>
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
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="font-bold text-gray-800">{{ $cert->user?->names ?? 'Usuario no asignado' }}</span>
                                                    @if (($cert->user?->certificates_count ?? 0) > 1)
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-1.5 py-0.5 rounded-full"
                                                            title="Este estudiante posee {{ $cert->user->certificates_count }} certificados registrados">
                                                            <i class="bi bi-collection"></i> {{ $cert->user->certificates_count }} certs
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500 font-mono">DNI: {{ $cert->user?->dni ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="font-semibold text-gray-800 text-xs">{{ $cert->course?->name ?? '—' }}</div>
                                                <div class="mt-1">
                                                    @if ($cert->modality === 'Virtual')
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                            <i class="bi bi-laptop text-[10px]"></i> Virtual
                                                        </span>
                                                    @elseif ($cert->modality === 'Semipresencial')
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                            <i class="bi bi-arrow-left-right text-[10px]"></i> Semipresencial
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                            <i class="bi bi-building text-[10px]"></i> Presencial
                                                        </span>
                                                    @endif
                                                </div>
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
                                                    <a href="{{ route('admin.certificates.print', $cert) }}" target="_blank"
                                                        class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                                        title="Ver / Imprimir Certificado Oficial">
                                                        <i class="bi bi-printer text-base"></i>
                                                    </a>
                                                    <a href="{{ route('validar-certificado', $cert->certificate_code) }}" target="_blank"
                                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                        title="Validación Pública QR">
                                                        <i class="bi bi-qr-code text-base"></i>
                                                    </a>
                                                    <button type="button" @click="openDetailsModal({{ json_encode($cert) }})"
                                                        class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                                                        title="Ver / Gestionar Calificaciones y Temario">
                                                        <i class="bi bi-card-checklist text-base"></i>
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
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Format & Participation Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Formato Certificado
                            </label>
                            <select name="certificate_type" x-model="form.certificate_type"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium">
                                <option value="capacitacion">Capacitación (Con Temario y QR)</option>
                                <option value="modular">Modular (Con Notas y Créditos)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Calidad / Condición
                            </label>
                            <input type="text" name="participation_type" x-model="form.participation_type"
                                placeholder="Ej: ASISTENTE, PONENTE"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 uppercase font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Marco / Evento
                            </label>
                            <input type="text" name="event_name" x-model="form.event_name"
                                placeholder="Ej: Semana Técnica 2026"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-semibold">
                        </div>
                    </div>

                    {{-- Code, Modality & Duration Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Código <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="certificate_code" x-model="form.certificate_code" required maxlength="100"
                                    placeholder="Ej: CERT-2026-001"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 uppercase font-mono font-bold">
                                <button type="button" @click="generateCode()"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] font-bold text-purple-600 hover:text-purple-800 bg-purple-50 px-1.5 py-0.5 rounded">
                                    Generar
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Modalidad <span class="text-red-500">*</span>
                            </label>
                            <select name="modality" x-model="form.modality" required
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium">
                                <option value="Presencial">Presencial</option>
                                <option value="Semipresencial">Semipresencial</option>
                                <option value="Virtual">Virtual</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Duración / Horas
                            </label>
                            <input type="text" name="duration" x-model="form.duration" maxlength="100"
                                placeholder="Ej: 120 Horas"
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
                    <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-xl flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">Certificado Válido / Activo</h4>
                            <p class="text-xs text-gray-500">Los certificados activos son visibles y verificables en el portal institucional.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="cert_is_active" name="is_active" value="1" x-model="form.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
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

        {{-- ══ MODAL IMPORTAR EXCEL / CSV ════════════════════════════════ --}}
        <div x-show="importModalOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">

            <div @click.outside="importModalOpen = false"
                @keydown.escape.window="importModalOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-4xl overflow-hidden flex flex-col max-h-[92vh]">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-700 text-white shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur-md text-white flex items-center justify-center text-2xl shadow-inner border border-white/20">
                            <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-extrabold tracking-tight">Importación Masiva de Certificados</h3>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-emerald-400/30 text-emerald-100 border border-emerald-300/30">
                                    XLSX • XLS • CSV
                                </span>
                            </div>
                            <p class="text-xs text-emerald-100/90">Carga masiva, registro de notas modulares y control anti-duplicados</p>
                        </div>
                    </div>
                    <button type="button" @click="importModalOpen = false" class="text-white/80 hover:text-white p-1.5 rounded-xl hover:bg-white/10 transition-colors">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- Navigation Tabs --}}
                <div class="flex items-center gap-2 px-6 pt-3 border-b border-gray-200 bg-gray-50/80 shrink-0">
                    <button type="button" @click="importTab = 'upload'"
                        class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 -mb-px"
                        :class="importTab === 'upload' ? 'border-emerald-600 text-emerald-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100/60'">
                        <i class="bi bi-cloud-arrow-up text-sm"></i>
                        1. Cargar Archivo y Plantillas
                    </button>
                    <button type="button" @click="importTab = 'examples'"
                        class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 -mb-px"
                        :class="importTab === 'examples' ? 'border-emerald-600 text-emerald-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100/60'">
                        <i class="bi bi-table text-sm"></i>
                        2. Ejemplos de Registros
                        <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Condición / Rol
                        </span>
                    </button>
                    <button type="button" @click="importTab = 'guide'"
                        class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 -mb-px"
                        :class="importTab === 'guide' ? 'border-emerald-600 text-emerald-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100/60'">
                        <i class="bi bi-card-list text-sm"></i>
                        3. Estructura de Columnas (A–P)
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-5 overflow-y-auto flex-1 bg-white">

                    {{-- ══ TAB 1: UPLOAD & DOWNLOAD TEMPLATES ════════════════ --}}
                    <div x-show="importTab === 'upload'" class="space-y-5">

                        {{-- Download Template Banner --}}
                        <div class="relative overflow-hidden p-5 bg-gradient-to-br from-indigo-50/90 via-purple-50/50 to-emerald-50/60 border border-indigo-100 rounded-2xl shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-extrabold uppercase tracking-wider text-indigo-700 bg-indigo-100/80 px-2.5 py-0.5 rounded-full border border-indigo-200">
                                            <i class="bi bi-file-earmark-check"></i> Plantilla Guía Oficial
                                        </span>
                                        <span class="text-xs text-gray-500 font-medium">Formatos soportados: <strong>.xlsx</strong>, <strong>.xls</strong>, <strong>.csv</strong></span>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-900">¿Primera vez o necesitas la estructura exacta?</h4>
                                    <p class="text-xs text-gray-600 max-w-xl">
                                        Descarga el documento guía prediseñado. Incluye los 16 campos oficiales (A–P), filas de ejemplo ilustrando la <strong>Condición / Participación</strong> (<code class="bg-indigo-100/70 text-indigo-800 px-1 py-0.5 rounded text-[11px]">participation_type</code>: ASISTENTE, PONENTE, ORGANIZADOR) y una hoja complementaria con instrucciones detalladas.
                                    </p>
                                </div>

                                {{-- Download Action Buttons --}}
                                <div class="flex flex-wrap sm:flex-col gap-2 shrink-0">
                                    <a href="{{ route('admin.certificates.template', ['format' => 'xlsx']) }}"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-bold shadow-sm transition hover:shadow flex-1 sm:flex-none">
                                        <i class="bi bi-file-earmark-excel-fill text-base"></i>
                                        <span>Descargar Excel (.xlsx)</span>
                                        <span class="text-[10px] bg-emerald-500/50 px-1.5 py-0.5 rounded-md font-semibold text-emerald-100">Recomendado</span>
                                    </a>
                                    <a href="{{ route('admin.certificates.template', ['format' => 'csv']) }}"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold shadow-sm transition flex-1 sm:flex-none">
                                        <i class="bi bi-filetype-csv text-base text-gray-500"></i>
                                        <span>Descargar CSV (.csv)</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Upload Form --}}
                        <form id="import-cert-form"
                            action="{{ route('admin.certificates.import') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf

                            {{-- Drag & Drop Zone --}}
                            <div class="relative">
                                <label for="cert-import-file"
                                    class="flex flex-col items-center justify-center w-full border-2 border-dashed rounded-2xl cursor-pointer transition-all p-6"
                                    :class="fileName ? 'border-emerald-500 bg-emerald-50/50' : 'border-gray-300 bg-gray-50/50 hover:border-emerald-400 hover:bg-emerald-50/20'"
                                    @dragover.prevent
                                    @drop.prevent="
                                        const f = $event.dataTransfer.files[0];
                                        if (f) { fileName = f.name; $refs.fileInput.files = $event.dataTransfer.files; }
                                    ">
                                    <div class="text-center space-y-3">
                                        <template x-if="!fileName">
                                            <div class="space-y-2">
                                                <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl shadow-sm">
                                                    <i class="bi bi-cloud-arrow-up"></i>
                                                </div>
                                                <p class="text-sm font-bold text-gray-800">
                                                    Arrastra tu archivo aquí o <span class="text-emerald-600 underline">haz clic para examinar</span>
                                                </p>
                                                <div class="flex items-center justify-center gap-2 pt-1">
                                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">.XLSX</span>
                                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800 border border-teal-200">.XLS</span>
                                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 border border-blue-200">.CSV</span>
                                                    <span class="text-xs text-gray-400">• Máximo 10 MB</span>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-if="fileName">
                                            <div class="space-y-2">
                                                <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-3xl shadow-md">
                                                    <i class="bi bi-file-earmark-check-fill"></i>
                                                </div>
                                                <p class="text-sm font-extrabold text-emerald-900" x-text="fileName"></p>
                                                <div class="flex items-center justify-center gap-2">
                                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-100/70 px-2.5 py-0.5 rounded-full">
                                                        <i class="bi bi-check-circle-fill text-[11px]"></i> Archivo preparado para procesar
                                                    </span>
                                                    <button type="button" @click.stop="fileName = ''; $refs.fileInput.value = ''"
                                                        class="text-xs font-bold text-red-600 hover:text-red-700 underline">
                                                        Cambiar archivo
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </label>
                                <input id="cert-import-file" name="file" type="file"
                                    accept=".xlsx,.xls,.csv"
                                    x-ref="fileInput"
                                    @change="fileName = $event.target.files[0]?.name || ''"
                                    class="sr-only">
                            </div>

                            {{-- Highlight Rules Box --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl">
                                    <div class="font-bold text-gray-800 flex items-center gap-1.5 mb-1">
                                        <i class="bi bi-shield-check text-emerald-600"></i> Control Anti-duplicados
                                    </div>
                                    <p class="text-gray-500 leading-snug">Se valida que no exista un certificado previo para el mismo estudiante, curso/evento y fecha.</p>
                                </div>

                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl">
                                    <div class="font-bold text-gray-800 flex items-center gap-1.5 mb-1">
                                        <i class="bi bi-person-badge text-indigo-600"></i> Condición de Participación
                                    </div>
                                    <p class="text-gray-500 leading-snug">Columna P: <code class="text-indigo-600 font-bold">ASISTENTE</code>, <code class="text-purple-600 font-bold">PONENTE</code>, <code class="text-emerald-600 font-bold">ORGANIZADOR</code> (por defecto ASISTENTE).</p>
                                </div>

                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl">
                                    <div class="font-bold text-gray-800 flex items-center gap-1.5 mb-1">
                                        <i class="bi bi-upc-scan text-purple-600"></i> Auto-código Inteligente
                                    </div>
                                    <p class="text-gray-500 leading-snug">Columna O: Si se deja vacía, se autogenera <code class="font-mono text-purple-700 bg-purple-50 px-1 rounded">CERT-{DNI}-{secuencia}</code>.</p>
                                </div>
                            </div>

                            {{-- Form Actions --}}
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="importTab = 'examples'"
                                        class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                        <i class="bi bi-eye"></i> Ver ejemplos de filas
                                    </button>
                                    <span class="text-gray-300">•</span>
                                    <button type="button" @click="importTab = 'guide'"
                                        class="text-xs font-bold text-gray-600 hover:text-gray-800 flex items-center gap-1">
                                        <i class="bi bi-list-columns"></i> Ver guía de columnas
                                    </button>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button" @click="importModalOpen = false"
                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                        class="px-5 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2"
                                        :disabled="!fileName"
                                        :class="!fileName ? 'opacity-50 cursor-not-allowed' : ''">
                                        <i class="bi bi-upload"></i>
                                        <span>Procesar e Importar</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- ══ TAB 2: EXAMPLES (FEATURING PARTICIPATION_TYPE) ═══ --}}
                    <div x-show="importTab === 'examples'" class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3 bg-indigo-50/70 border border-indigo-100 rounded-xl">
                            <div>
                                <h4 class="text-xs font-extrabold text-indigo-900 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="bi bi-award-fill text-indigo-600"></i> Ejemplos Reales con Condición / Tipo de Participación
                                </h4>
                                <p class="text-xs text-indigo-700 mt-0.5">
                                    Observe cómo el campo <strong>participation_type</strong> (Columna P) permite definir roles institucionales específicos (ASISTENTE, PONENTE, ORGANIZADOR, etc.), mientras que la columna Código (Columna O) puede ingresarse manualmente o dejarse vacía para autogeneración.
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('admin.certificates.template', ['format' => 'xlsx']) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm transition whitespace-nowrap">
                                    <i class="bi bi-download"></i> Descargar Excel (.xlsx)
                                </a>
                            </div>
                        </div>

                        {{-- Table of Examples --}}
                        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-xs divide-y divide-gray-200">
                                    <thead class="bg-gray-50 font-bold text-gray-700 uppercase tracking-wider text-[11px]">
                                        <tr>
                                            <th class="px-3 py-2.5 text-center bg-gray-100/60">Col A (N°)</th>
                                            <th class="px-3 py-2.5 text-left">Col B (DNI)</th>
                                            <th class="px-3 py-2.5 text-left">Col C (Estudiante)</th>
                                            <th class="px-3 py-2.5 text-left">Col D (Curso)</th>
                                            <th class="px-3 py-2.5 text-center">Fechas (E, F, G)</th>
                                            <th class="px-3 py-2.5 text-center">Col H (Horas)</th>
                                            <th class="px-3 py-2.5 text-center">Notas (I, K)</th>
                                            <th class="px-3 py-2.5 text-center">Col N (Modalidad)</th>
                                            <th class="px-3 py-2.5 text-left bg-purple-50/50 text-purple-900 border-l border-r border-purple-100">
                                                Col O (Código)
                                            </th>
                                            <th class="px-3 py-2.5 text-left bg-emerald-50 text-emerald-900 font-extrabold border-l border-emerald-200">
                                                <i class="bi bi-star-fill text-emerald-500 mr-1"></i> Col P (Condición / Rol)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        {{-- Row 1: ASISTENTE --}}
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <td class="px-3 py-2.5 text-center font-mono font-bold text-gray-400 bg-gray-50/40">1</td>
                                            <td class="px-3 py-2.5 font-mono font-bold text-gray-900">74123456</td>
                                            <td class="px-3 py-2.5 font-medium text-gray-800 whitespace-nowrap">García López, María Elena</td>
                                            <td class="px-3 py-2.5 font-semibold text-indigo-700 whitespace-nowrap">Excel Avanzado</td>
                                            <td class="px-3 py-2.5 text-center text-gray-500 font-mono whitespace-nowrap">
                                                <div>2026-01-10 → 2026-03-20</div>
                                                <div class="text-[10px] text-gray-400">Emisión: 2026-03-21</div>
                                            </td>
                                            <td class="px-3 py-2.5 text-center text-gray-600">120 Horas</td>
                                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 font-mono font-bold text-gray-700">M1: 17</span>
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 font-mono font-bold text-gray-700 ml-1">M2: 19</span>
                                            </td>
                                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">Presencial</span>
                                            </td>
                                            <td class="px-3 py-2.5 font-mono text-purple-700 font-bold bg-purple-50/30 whitespace-nowrap border-l border-r border-purple-100">
                                                CERT-74123456-1
                                            </td>
                                            <td class="px-3 py-2.5 bg-emerald-50/40 whitespace-nowrap border-l border-emerald-200">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800 border border-blue-300">
                                                    <i class="bi bi-person-check-fill text-[11px]"></i> ASISTENTE
                                                </span>
                                            </td>
                                        </tr>

                                        {{-- Row 2: PONENTE with empty code (auto-generated) --}}
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <td class="px-3 py-2.5 text-center font-mono font-bold text-gray-400 bg-gray-50/40">2</td>
                                            <td class="px-3 py-2.5 font-mono font-bold text-gray-900">71234568</td>
                                            <td class="px-3 py-2.5 font-medium text-gray-800 whitespace-nowrap">Rodríguez Quispe, Carlos Alberto</td>
                                            <td class="px-3 py-2.5 font-semibold text-indigo-700 whitespace-nowrap">Desarrollo Web Full Stack</td>
                                            <td class="px-3 py-2.5 text-center text-gray-500 font-mono whitespace-nowrap">
                                                <div>2026-02-01 → 2026-04-15</div>
                                                <div class="text-[10px] text-gray-400">Emisión: 2026-04-16</div>
                                            </td>
                                            <td class="px-3 py-2.5 text-center text-gray-600">180 Horas</td>
                                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 font-mono font-bold text-gray-700">M1: 20</span>
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 font-mono font-bold text-gray-700 ml-1">M2: 18</span>
                                            </td>
                                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">Virtual</span>
                                            </td>
                                            <td class="px-3 py-2.5 font-mono text-purple-600 bg-purple-50/30 whitespace-nowrap border-l border-r border-purple-100">
                                                <span class="italic text-gray-400 text-[11px] font-sans">[Vacío → Auto: CERT-71234568-1]</span>
                                            </td>
                                            <td class="px-3 py-2.5 bg-emerald-50/40 whitespace-nowrap border-l border-emerald-200">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-purple-100 text-purple-800 border border-purple-300">
                                                    <i class="bi bi-mic-fill text-[11px]"></i> PONENTE
                                                </span>
                                            </td>
                                        </tr>

                                        {{-- Row 3: ORGANIZADOR --}}
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <td class="px-3 py-2.5 text-center font-mono font-bold text-gray-400 bg-gray-50/40">3</td>
                                            <td class="px-3 py-2.5 font-mono font-bold text-gray-900">70987654</td>
                                            <td class="px-3 py-2.5 font-medium text-gray-800 whitespace-nowrap">Mamani Flores, Ana Lucía</td>
                                            <td class="px-3 py-2.5 font-semibold text-indigo-700 whitespace-nowrap">Inteligencia Artificial Aplicada</td>
                                            <td class="px-3 py-2.5 text-center text-gray-500 font-mono whitespace-nowrap">
                                                <div>2026-03-01 → 2026-05-10</div>
                                                <div class="text-[10px] text-gray-400">Emisión: 2026-05-12</div>
                                            </td>
                                            <td class="px-3 py-2.5 text-center text-gray-600">90 Horas</td>
                                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 font-mono font-bold text-gray-700">M1: 18</span>
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 font-mono font-bold text-gray-700 ml-1">M2: 17</span>
                                            </td>
                                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">Semipresencial</span>
                                            </td>
                                            <td class="px-3 py-2.5 font-mono text-purple-700 font-bold bg-purple-50/30 whitespace-nowrap border-l border-r border-purple-100">
                                                CERT-IA-2026-003
                                            </td>
                                            <td class="px-3 py-2.5 bg-emerald-50/40 whitespace-nowrap border-l border-emerald-200">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                    <i class="bi bi-award text-[11px]"></i> ORGANIZADOR
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Explanatory Note on Participation Type --}}
                        <div class="flex items-start gap-3 p-3.5 bg-emerald-50/70 border border-emerald-200 rounded-xl text-xs text-emerald-900">
                            <div class="w-6 h-6 rounded-lg bg-emerald-200 text-emerald-800 flex items-center justify-center shrink-0 text-sm font-bold">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <div class="space-y-1">
                                <p class="font-bold">Efecto del campo «Condición / Participación» en el Certificado Oficial:</p>
                                <p class="text-emerald-800 leading-relaxed">
                                    El valor ingresado en la <strong>Columna P</strong> se reflejará directamente en la impresión oficial y en la vista de validación pública QR («en su calidad de <strong>PONENTE</strong>», «en calidad de <strong>ASISTENTE</strong>», etc.). Si se omite o se deja en blanco en el archivo, el sistema asignará <strong>ASISTENTE</strong> automáticamente.
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="button" @click="importTab = 'upload'"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center gap-1.5">
                                <i class="bi bi-arrow-left"></i> Volver a la Carga de Archivos
                            </button>
                        </div>
                    </div>

                    {{-- ══ TAB 3: COMPLETE COLUMN GUIDE (A–P) ════════════════ --}}
                    <div x-show="importTab === 'guide'" class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-xl">
                            <div>
                                <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Estructura Detallada de Columnas (16 Columnas: A a P)</h4>
                                <p class="text-xs text-gray-500">Revise la posición, obligatoriedad y función de cada columna en el archivo de importación.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.certificates.template', ['format' => 'xlsx']) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm transition">
                                    <i class="bi bi-file-earmark-excel"></i> Descargar Plantilla (.xlsx)
                                </a>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <div class="overflow-x-auto max-h-[50vh]">
                                <table class="min-w-full text-xs divide-y divide-gray-200">
                                    <thead class="bg-gray-50 sticky top-0 font-bold text-gray-700 uppercase tracking-wider text-[11px] shadow-sm">
                                        <tr>
                                            <th class="px-3 py-2 text-center">Col</th>
                                            <th class="px-3 py-2 text-left">Encabezado en Plantilla</th>
                                            <th class="px-3 py-2 text-center">Estado</th>
                                            <th class="px-3 py-2 text-left">Campo Destino</th>
                                            <th class="px-3 py-2 text-left">Descripción y Reglas de Importación</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        <tr class="bg-gray-50/50">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-gray-400">A</td>
                                            <td class="px-3 py-2 font-semibold text-gray-400">N°</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">IGNORADA</span></td>
                                            <td class="px-3 py-2 text-gray-400 italic">—</td>
                                            <td class="px-3 py-2 text-gray-400">Número correlativo de fila. Solo referencia visual.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">B</td>
                                            <td class="px-3 py-2 font-bold text-gray-900">DNI / N° Documento</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">REQUERIDO</span></td>
                                            <td class="px-3 py-2 font-mono text-indigo-700">user.dni</td>
                                            <td class="px-3 py-2 text-gray-600">DNI del estudiante. Si no existe en el sistema, se crea un usuario automáticamente con contraseña provisional.</td>
                                        </tr>
                                        <tr class="bg-gray-50/50">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-gray-400">C</td>
                                            <td class="px-3 py-2 font-semibold text-gray-500">Apellidos y Nombres</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">REFERENCIA</span></td>
                                            <td class="px-3 py-2 font-mono text-gray-400">user.names</td>
                                            <td class="px-3 py-2 text-gray-500">Nombre de referencia. Si el usuario ya existe se conserva su nombre en base de datos.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">D</td>
                                            <td class="px-3 py-2 font-bold text-gray-900">Curso</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">REQUERIDO</span></td>
                                            <td class="px-3 py-2 font-mono text-indigo-700">course_id</td>
                                            <td class="px-3 py-2 text-gray-600">Nombre exacto del curso registrado en el sistema (búsqueda sin distinción de mayúsculas).</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">E</td>
                                            <td class="px-3 py-2 font-semibold text-gray-800">Fecha de Inicio</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">OPCIONAL</span></td>
                                            <td class="px-3 py-2 font-mono text-gray-600">start_date</td>
                                            <td class="px-3 py-2 text-gray-600">Fecha de inicio del curso en formato <code class="bg-gray-100 px-1 rounded">YYYY-MM-DD</code>.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">F</td>
                                            <td class="px-3 py-2 font-semibold text-gray-800">Fecha de Término</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">OPCIONAL</span></td>
                                            <td class="px-3 py-2 font-mono text-gray-600">end_date</td>
                                            <td class="px-3 py-2 text-gray-600">Fecha de finalización del curso en formato <code class="bg-gray-100 px-1 rounded">YYYY-MM-DD</code>.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">G</td>
                                            <td class="px-3 py-2 font-bold text-gray-900">Fecha de Emisión</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">REQUERIDO</span></td>
                                            <td class="px-3 py-2 font-mono text-indigo-700">issue_date</td>
                                            <td class="px-3 py-2 text-gray-600">Fecha oficial de expedición del certificado en formato <code class="bg-gray-100 px-1 rounded">YYYY-MM-DD</code>.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">H</td>
                                            <td class="px-3 py-2 font-semibold text-gray-800">Horas</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">OPCIONAL</span></td>
                                            <td class="px-3 py-2 font-mono text-gray-600">duration</td>
                                            <td class="px-3 py-2 text-gray-600">Carga horaria o duración. Ejemplo: "120 Horas".</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-indigo-700">I</td>
                                            <td class="px-3 py-2 font-semibold text-indigo-900">Calificación I (numérica)</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700">OPCIONAL</span></td>
                                            <td class="px-3 py-2 font-mono text-indigo-700">score (Módulo 1)</td>
                                            <td class="px-3 py-2 text-gray-600">Nota numérica registrada en el primer módulo del curso según orden.</td>
                                        </tr>
                                        <tr class="bg-gray-50/50">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-gray-400">J</td>
                                            <td class="px-3 py-2 font-semibold text-gray-400">Calificación Letras I</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">IGNORADA</span></td>
                                            <td class="px-3 py-2 text-gray-400 italic">—</td>
                                            <td class="px-3 py-2 text-gray-400">Nota en letras del módulo 1. No se almacena.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-indigo-700">K</td>
                                            <td class="px-3 py-2 font-semibold text-indigo-900">Calificación II (numérica)</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700">OPCIONAL</span></td>
                                            <td class="px-3 py-2 font-mono text-indigo-700">score (Módulo 2)</td>
                                            <td class="px-3 py-2 text-gray-600">Nota numérica registrada en el segundo módulo del curso según orden.</td>
                                        </tr>
                                        <tr class="bg-gray-50/50">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-gray-400">L</td>
                                            <td class="px-3 py-2 font-semibold text-gray-400">Calificación Letras II</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">IGNORADA</span></td>
                                            <td class="px-3 py-2 text-gray-400 italic">—</td>
                                            <td class="px-3 py-2 text-gray-400">Nota en letras del módulo 2. No se almacena.</td>
                                        </tr>
                                        <tr class="bg-gray-50/50">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-gray-400">M</td>
                                            <td class="px-3 py-2 font-semibold text-gray-400">Promedio</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">IGNORADA</span></td>
                                            <td class="px-3 py-2 text-gray-400 italic">—</td>
                                            <td class="px-3 py-2 text-gray-400">Promedio ponderado calculado automáticamente por el sistema.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">N</td>
                                            <td class="px-3 py-2 font-bold text-gray-900">Modalidad</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">REQUERIDO</span></td>
                                            <td class="px-3 py-2 font-mono text-indigo-700">modality</td>
                                            <td class="px-3 py-2 text-gray-600">Acepta exactamente: <code class="font-bold text-gray-700">Presencial</code>, <code class="font-bold text-gray-700">Virtual</code> o <code class="font-bold text-gray-700">Semipresencial</code>.</td>
                                        </tr>
                                        <tr class="hover:bg-purple-50/30">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">O</td>
                                            <td class="px-3 py-2 font-bold text-purple-900">Código</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700">AUTO-CÓDIGO</span></td>
                                            <td class="px-3 py-2 font-mono text-purple-700">code</td>
                                            <td class="px-3 py-2 text-gray-600">Código identificador único. Si está vacío, se auto-genera como <code class="bg-purple-100 text-purple-800 px-1 py-0.5 rounded font-bold font-mono">CERT-{DNI}-{secuencia}</code>. Si se ingresa, se valida unicidad.</td>
                                        </tr>
                                        <tr class="hover:bg-emerald-50/40 bg-emerald-50/20">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-emerald-700">P</td>
                                            <td class="px-3 py-2 font-extrabold text-emerald-950 flex items-center gap-1.5">
                                                <i class="bi bi-star-fill text-emerald-600 text-[10px]"></i> Condición / Participación
                                            </td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">OPCIONAL</span></td>
                                            <td class="px-3 py-2 font-mono text-emerald-800 font-bold">participation_type</td>
                                            <td class="px-3 py-2 text-gray-700">Rol o condición académica: <code class="bg-white border px-1 rounded font-bold text-blue-700">ASISTENTE</code>, <code class="bg-white border px-1 rounded font-bold text-purple-700">PONENTE</code>, <code class="bg-white border px-1 rounded font-bold text-emerald-700">ORGANIZADOR</code>, etc. Si está vacío se asigna <strong>ASISTENTE</strong>.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="button" @click="importTab = 'upload'"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center gap-1.5">
                                <i class="bi bi-arrow-left"></i> Volver a la Carga de Archivos
                            </button>
                        </div>
                    </div>

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
            importModalOpen: false,
            importTab: 'upload',
            fileName: '',
            isEdit: false,
            updateUrl: '',
            activeCert: null,
            availableModules: [],
            form: {
                id: null,
                user_id: '',
                course_id: '',
                certificate_type: 'capacitacion',
                participation_type: 'ASISTENTE',
                event_name: '',
                certificate_code: '',
                description: '',
                start_date: '',
                end_date: '',
                duration: '',
                modality: 'Presencial',
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
                    certificate_type: 'capacitacion',
                    participation_type: 'ASISTENTE',
                    event_name: 'Semana Técnica 2026',
                    certificate_code: this.generateCodeStr(),
                    description: '',
                    start_date: '',
                    end_date: '',
                    duration: '90 horas pedagógicas',
                    modality: 'Presencial',
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
                    certificate_type: cert.certificate_type || cert.course?.certificate_type || 'capacitacion',
                    participation_type: cert.participation_type || 'ASISTENTE',
                    event_name: cert.event_name || cert.course?.event_name || '',
                    certificate_code: cert.certificate_code || '',
                    description: cert.description || '',
                    start_date: cert.start_date ? cert.start_date.substring(0, 10) : '',
                    end_date: cert.end_date ? cert.end_date.substring(0, 10) : '',
                    duration: cert.duration || '',
                    modality: cert.modality || 'Presencial',
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
