@extends('layouts.app')
@section('title', 'Mis Certificados Oficiales — Portal del Estudiante | IESTP Francisco Vigo Caballero')
@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .cert-card {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .cert-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 30px -10px rgba(99, 102, 241, 0.15);
    }
</style>
@endpush
@section('content')
<div id="dashboard-container" class="flex w-full bg-slate-50 font-sans text-slate-900 min-h-[calc(100vh-64px)]"
    x-data="studentCertificatesApp()">
    @include('admin.components.aside')
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50/50 relative">
        {{-- ── Encabezado Sticky ── --}}
        <header class="bg-white/95 border-b border-slate-200 sticky top-[64px] lg:top-0 z-[30] shadow-xs backdrop-blur-md">
            <div class="px-4 sm:px-6 py-3.5 sm:py-4 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button @click="toggleSidebar()" class="text-slate-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-xl transition-colors lg:hidden" aria-label="Abrir menú">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight font-display flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-base shadow-sm">
                                    <i class="bi bi-patch-check-fill"></i>
                                </span>
                                Mis Certificados Oficiales
                            </h1>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                <i class="bi bi-person-fill text-xs text-purple-500"></i> Estudiante
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 hidden sm:block mt-0.5">
                            Consulta, descarga e impresión oficial de tus certificaciones y constancias académicas.
                        </p>
                    </div>
                </div>
                {{-- Breadcrumb --}}
                <div class="hidden sm:flex items-center text-xs font-medium text-slate-500 gap-2">
                    <a href="{{ route('biblioteca.index') }}" class="hover:text-purple-600 transition flex items-center gap-1">
                        <i class="bi bi-mortarboard"></i> Portal Estudiante
                    </a>
                    <i class="bi bi-chevron-right text-[10px] text-slate-400"></i>
                    <span class="text-purple-700 font-bold bg-purple-50 px-2.5 py-1 rounded-lg border border-purple-100">
                        Mis Certificados
                    </span>
                </div>
            </div>
        </header>
        {{-- ── Main Body ── --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden space-y-6 max-w-7xl mx-auto w-full">

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                {{-- Total --}}
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-2xl flex-shrink-0">
                        <i class="bi bi-collection-fill"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Certificados</p>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ number_format($totalCertificates) }}</h3>
                    </div>
                </div>

                {{-- Activos / Validados --}}
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-2xl flex-shrink-0">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Válidos Oficiales</p>
                        <h3 class="text-xl sm:text-2xl font-black text-emerald-700 mt-0.5">{{ number_format($activeCertificates) }}</h3>
                    </div>
                </div>

                {{-- Cursos Acreditados --}}
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-2xl flex-shrink-0">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Cursos / Módulos</p>
                        <h3 class="text-xl sm:text-2xl font-black text-indigo-700 mt-0.5">{{ number_format($issuedCoursesCount) }}</h3>
                    </div>
                </div>

                {{-- Modalidad Presencial / Virtual --}}
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-2xl flex-shrink-0">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Descargas / Vistas</p>
                        <h3 class="text-xl sm:text-2xl font-black text-amber-700 mt-0.5">{{ number_format($totalDownloads) }}</h3>
                    </div>
                </div>
            </div>

            {{-- ── Search & Filter Bar ── --}}
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-xs">
                <form action="{{ route('admin.certificates.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-12 gap-3 sm:gap-4 items-center">
                    {{-- Search Input --}}
                    <div class="lg:col-span-5 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-search"></i>
                        </div>
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Buscar por código, curso o descripción..."
                            class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-slate-800 placeholder-slate-400">
                    </div>

                    {{-- Course filter --}}
                    @if ($courses->isNotEmpty())
                    <div class="lg:col-span-4">
                        <select name="course_id" onchange="this.form.submit()"
                            class="w-full py-2.5 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-slate-700 font-medium">
                            <option value="">Todos los Cursos</option>
                            @foreach ($courses as $c)
                                <option value="{{ $c->id }}" {{ (string)$courseId === (string)$c->id ? 'selected' : '' }}>
                                    {{ Str::limit($c->name, 45) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="lg:col-span-4 hidden lg:block"></div>
                    @endif

                    {{-- Modality filter --}}
                    <div class="lg:col-span-2">
                        <select name="modality" onchange="this.form.submit()"
                            class="w-full py-2.5 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-slate-700 font-medium">
                            <option value="">Modalidad (Todas)</option>
                            <option value="Presencial" {{ $modality === 'Presencial' ? 'selected' : '' }}>Presencial</option>
                            <option value="Virtual" {{ $modality === 'Virtual' ? 'selected' : '' }}>Virtual</option>
                            <option value="Semipresencial" {{ $modality === 'Semipresencial' ? 'selected' : '' }}>Semipresencial</option>
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="lg:col-span-1 flex items-center gap-2">
                        <button type="submit" class="w-full py-2.5 px-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs sm:text-sm font-bold transition-colors flex items-center justify-center gap-1 shadow-sm">
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                    </div>
                </form>

                @if($search || $courseId || $modality || $status !== null)
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500">Filtros activos aplicados</span>
                        <a href="{{ route('admin.certificates.index') }}" class="text-purple-600 font-bold hover:underline flex items-center gap-1">
                            <i class="bi bi-x-circle"></i> Limpiar filtros
                        </a>
                    </div>
                @endif
            </div>

            {{-- Certificates List / Grid --}}
            @if ($certificates->isEmpty())
                <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 text-center shadow-xs">
                    <div class="w-16 h-16 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-3xl mx-auto mb-4">
                        <i class="bi bi-patch-question"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800">No se encontraron certificados registrados</h3>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mt-2 leading-relaxed">
                        @if($search || $courseId || $modality)
                            No hay certificados que coincidan con los filtros aplicados. Intenta restablecer los filtros de búsqueda.
                        @else
                            Tus certificados y constancias oficiales se emitirán y visualizarán aquí una vez aprobados y registrados por la secretaría académica del instituto.
                        @endif
                    </p>
                    @if($search || $courseId || $modality)
                        <a href="{{ route('admin.certificates.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 mt-5 rounded-xl bg-purple-600 text-white text-xs font-bold hover:bg-purple-700 transition">
                            <i class="bi bi-arrow-clockwise"></i> Ver todos mis certificados
                        </a>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach ($certificates as $cert)
                        @php
                            $certCode = $cert->code ?: $cert->certificate_code;
                            $hasModules = $cert->details && $cert->details->isNotEmpty();
                        @endphp
                        <div class="cert-card bg-white rounded-3xl border border-slate-200 p-6 flex flex-col justify-between shadow-xs">
                            <div class="space-y-4">
                                {{-- Card Top: Type badge & Status --}}
                                <div class="flex items-center justify-between gap-3">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        <i class="bi bi-award-fill text-purple-500"></i>
                                        {{ ucfirst($cert->certificate_type ?? 'Certificación') }}
                                    </span>

                                    @if ($cert->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="bi bi-check-circle-fill text-[8px] text-emerald-500"></i> Vigente / Válido
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                            Archivado
                                        </span>
                                    @endif
                                </div>

                                {{-- Course Title --}}
                                <div>
                                    <h3 class="text-base sm:text-lg font-black text-slate-900 leading-snug tracking-tight">
                                        {{ $cert->course->name ?? ($cert->description ?: 'Certificado de Capacitación') }}
                                    </h3>
                                    @if ($cert->course?->studyProgram)
                                        <p class="text-xs text-slate-500 font-medium mt-1 flex items-center gap-1.5">
                                            <i class="bi bi-mortarboard text-purple-600"></i>
                                            {{ $cert->course->studyProgram->name }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Meta Information Grid --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-100 text-xs">
                                    <div>
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Código Oficial</span>
                                        <span class="font-bold text-purple-900 font-mono text-xs select-all">{{ $certCode }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Fecha Emisión</span>
                                        <span class="font-semibold text-slate-700">
                                            {{ $cert->issue_date ? \Carbon\Carbon::parse($cert->issue_date)->format('d/m/Y') : 'Registrado' }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Modalidad / Horas</span>
                                        <span class="font-semibold text-slate-700">
                                            {{ $cert->modality ?: 'Presencial' }} · {{ $cert->duration ?: ($cert->course?->hours ? $cert->course->hours.' hrs' : '—') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Modular Details Accordion (if modular) --}}
                                @if ($hasModules)
                                    <div x-data="{ open: false }" class="border border-slate-200/80 rounded-2xl overflow-hidden">
                                        <button type="button" @click="open = !open"
                                            class="w-full py-2 px-3.5 bg-slate-50 hover:bg-slate-100/80 text-xs font-bold text-slate-700 flex items-center justify-between transition">
                                            <span class="flex items-center gap-1.5">
                                                <i class="bi bi-list-check text-purple-600"></i> Módulos y Calificaciones ({{ $cert->details->count() }})
                                            </span>
                                            <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                        </button>
                                        <div x-show="open" x-collapse x-cloak class="p-3 bg-white space-y-2 text-xs">
                                            @foreach ($cert->details as $detail)
                                                <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100">
                                                    <span class="font-medium text-slate-800">{{ $detail->module?->name ?? 'Módulo Académico' }}</span>
                                                    <span class="font-extrabold text-purple-700 px-2 py-0.5 rounded-lg bg-purple-50 border border-purple-200">
                                                        {{ $detail->score ?? 'Aprobado' }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Actions Footer --}}
                            <div class="mt-6 pt-4 border-t border-slate-100 flex flex-wrap items-center gap-2.5">
                                {{-- Print / View official certificate --}}
                                <a href="{{ route('admin.certificates.print', $cert) }}" target="_blank"
                                    class="flex-1 py-2.5 px-4 bg-slate-900 hover:bg-purple-600 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-sm">
                                    <i class="bi bi-printer-fill"></i>
                                    <span>Ver e Imprimir</span>
                                </a>

                                {{-- Public QR / Verify Link --}}
                                <a href="{{ route('validar-certificado', ['certificate_code' => $certCode]) }}" target="_blank"
                                    class="py-2.5 px-4 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5"
                                    title="Verificar validez pública mediante QR">
                                    <i class="bi bi-qr-code text-sm"></i>
                                    <span class="hidden sm:inline">Verificar QR</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($certificates->hasPages())
                    <div class="pt-4">
                        {{ $certificates->links() }}
                    </div>
                @endif
            @endif

        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('studentCertificatesApp', () => ({
            sidebarOpen: window.innerWidth >= 1024,
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
            },
            init() {
                window.addEventListener('resize', () => {
                    this.sidebarOpen = window.innerWidth >= 1024;
                });
            }
        }));
    });
</script>
@endpush
