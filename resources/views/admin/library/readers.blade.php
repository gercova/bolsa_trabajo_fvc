@extends('admin.library.layout')

@section('title', 'Dashboard de Lectores - Monitoreo de Lecturas')

@section('library_content')
<div class="p-5 sm:p-7 space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <i class="bi bi-person-video2 text-teal-800"></i> Monitoreo de Lectores
            </h1>
            <p class="text-xs text-slate-500">Panel de seguimiento de docentes y estudiantes que acceden, leen y consultan la biblioteca virtual.</p>
        </div>
        <div class="text-xs text-slate-500 font-semibold flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            Registro de lecturas en tiempo real
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- KPI METRIC CARDS                                                   --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Total Unique Readers --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-800 flex items-center justify-center text-2xl border border-teal-100 flex-shrink-0">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Lectores Totales</span>
                <span class="text-2xl font-black text-slate-900">{{ number_format($totalUniqueReaders) }}</span>
                <span class="text-[11px] text-slate-500 block">Docentes y alumnos</span>
            </div>
        </div>

        {{-- Total Sessions / Views --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-2xl border border-indigo-100 flex-shrink-0">
                <i class="bi bi-book-half"></i>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Consultas / Lecturas</span>
                <span class="text-2xl font-black text-slate-900">{{ number_format($totalAccessSessions) }}</span>
                <span class="text-[11px] text-slate-500 block">Visualizaciones registradas</span>
            </div>
        </div>

        {{-- Active Teachers --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-2xl border border-amber-100 flex-shrink-0">
                <i class="bi bi-person-workspace"></i>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Docentes Lectores</span>
                <span class="text-2xl font-black text-slate-900">{{ number_format($activeTeachersCount) }}</span>
                <span class="text-[11px] text-amber-700 font-semibold block">Actividad docente</span>
            </div>
        </div>

        {{-- Active Students --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl border border-emerald-100 flex-shrink-0">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Estudiantes Lectores</span>
                <span class="text-2xl font-black text-slate-900">{{ number_format($activeStudentsCount) }}</span>
                <span class="text-[11px] text-emerald-700 font-semibold block">Actividad estudiantil</span>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- SECTION: Reads by Career & Top Read Content                        --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Distribution by Study Program --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i class="bi bi-bar-chart-fill text-teal-700"></i> Distribución de Lecturas por Carrera
                </h3>
                <span class="text-[11px] text-slate-400 font-semibold">Consolidado general</span>
            </div>

            <div class="space-y-3">
                @php $maxReads = $readsByCareer->max('total_reads') ?: 1; @endphp
                @forelse($readsByCareer as $item)
                    @php $pct = round(($item->total_reads / $maxReads) * 100); @endphp
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700">{{ $item->program_name }}</span>
                            <span class="text-slate-900 font-bold">{{ number_format($item->total_reads) }} lecturas</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-[#0b4d57] rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">No se han registrado lecturas en el sistema.</p>
                @endforelse
            </div>
        </div>

        {{-- Top Most Read Content --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i class="bi bi-trophy-fill text-amber-500"></i> Materiales Más Consultados
                </h3>
                <span class="text-[11px] text-slate-400 font-semibold">Top lecturas</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($mostReadBooks as $idx => $b)
                    <div class="py-2.5 flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 font-black text-xs flex items-center justify-center flex-shrink-0">
                                {{ $idx + 1 }}
                            </span>
                            <div class="truncate">
                                <h4 class="font-bold text-slate-900 truncate">{{ $b->title }}</h4>
                                <p class="text-[11px] text-slate-400 truncate">{{ $b->author }} · {{ $b->studyProgram ? $b->studyProgram->name : 'General' }}</p>
                            </div>
                        </div>
                        <span class="font-extrabold text-teal-800 text-xs px-2.5 py-1 bg-teal-50 rounded-lg flex-shrink-0">
                            {{ number_format($b->access_logs_count) }} visitas
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">Aún no hay lecturas registradas.</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TABLE: READERS LIST & FILTER                                       --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs space-y-4 p-5">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Directorio de Lectores Activos</h3>
                <p class="text-xs text-slate-500">Lista de usuarios que han interactuado con los libros y documentos del catálogo.</p>
            </div>

            {{-- Filter by Role --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.library.readers', ['role' => 'all']) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $roleFilter === 'all' ? 'bg-[#0b4d57] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Todos
                </a>
                <a href="{{ route('admin.library.readers', ['role' => 'Docente']) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $roleFilter === 'Docente' ? 'bg-[#0b4d57] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Docentes
                </a>
                <a href="{{ route('admin.library.readers', ['role' => 'Estudiante']) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $roleFilter === 'Estudiante' ? 'bg-[#0b4d57] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Estudiantes
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Lector</th>
                        <th class="py-3 px-4">DNI</th>
                        <th class="py-3 px-4">Rol Institucional</th>
                        <th class="py-3 px-4 text-center">Consultas / Lecturas</th>
                        <th class="py-3 px-4 text-right">Contacto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($readers as $reader)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 font-black text-xs flex items-center justify-center">
                                    {{ strtoupper(substr($reader->names, 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">{{ $reader->names }}</h4>
                                    <p class="text-[11px] text-slate-400">{{ $reader->email }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600">
                                {{ $reader->dni ?? '—' }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $reader->role === 'Docente' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-blue-50 text-blue-800 border border-blue-200' }}">
                                    {{ $reader->role }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-slate-800">
                                <span class="px-2.5 py-1 bg-teal-50 text-teal-800 rounded-lg">
                                    {{ number_format($reader->library_access_logs_count) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-slate-500">
                                <a href="mailto:{{ $reader->email }}" class="text-teal-700 hover:underline">
                                    {{ $reader->email }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                No se encontraron registros de lectores para el filtro seleccionado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-2">
            {{ $readers->links() }}
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- ACTIVITY STREAM: RECENT READINGS                                   --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i class="bi bi-clock-history text-teal-700"></i> Historial Reciente de Lecturas
            </h3>
            <span class="text-[11px] text-slate-400">Últimas 15 actividades</span>
        </div>

        <div class="divide-y divide-slate-100 text-xs">
            @forelse($recentLogs as $log)
                <div class="py-2.5 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="truncate">
                            <span class="font-bold text-slate-800">{{ $log->user ? $log->user->names : 'Usuario Anónimo' }}</span>
                            <span class="text-slate-400">consultó</span>
                            <span class="font-semibold text-slate-900">{{ $log->book ? $log->book->title : 'Documento' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-slate-400 text-[11px] flex-shrink-0">
                        <span>{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-4">No hay lecturas registradas recientemente.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
