@extends('admin.library.layout')

@section('title', 'Reportes - Biblioteca')

@section('library_content')
<div class="p-5 sm:p-7 space-y-6">

    <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <i class="bi bi-flag-fill text-teal-800"></i> Reportes y Métricas del Acervo Bibliográfico
            </h1>
            <p class="text-xs text-slate-500">Resumen cuantitativo de títulos, colecciones, lecturas y favoritos en la institución.</p>
        </div>
    </div>

    {{-- Metrics Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Títulos Totales</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ number_format($totalBooks) }}</span>
            <span class="text-[11px] text-slate-500">Libros y documentos registrados</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Lecturas Acumuladas</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ number_format($totalViews) }}</span>
            <span class="text-[11px] text-slate-500">Visualizaciones totales</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total en "Mi Biblioteca"</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ number_format($totalFavorites) }}</span>
            <span class="text-[11px] text-slate-500">Libros guardados como favoritos</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Categorías</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ number_format($totalCategories) }}</span>
            <span class="text-[11px] text-slate-500">Formatos disponibles</span>
        </div>
    </div>

    {{-- Detailed Breakdowns --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- By Category --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-3">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="bi bi-pie-chart-fill text-teal-700"></i> Distribución por Tipo de Contenido
            </h3>
            <div class="divide-y divide-slate-100 text-xs">
                @foreach($booksByCategory as $cat)
                    <div class="py-2.5 flex items-center justify-between">
                        <span class="font-bold text-slate-700">{{ $cat->category }}</span>
                        <span class="px-2.5 py-1 bg-slate-100 rounded-lg font-bold text-slate-900">{{ number_format($cat->total) }} títulos</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- By Program --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-3">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="bi bi-grid-3x3-gap-fill text-indigo-700"></i> Títulos Asignados por Carrera
            </h3>
            <div class="divide-y divide-slate-100 text-xs">
                @foreach($booksByProgram as $prog)
                    <div class="py-2.5 flex items-center justify-between">
                        <span class="font-bold text-slate-700">{{ $prog->program_name }}</span>
                        <span class="px-2.5 py-1 bg-slate-100 rounded-lg font-bold text-slate-900">{{ number_format($prog->total) }} títulos</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
