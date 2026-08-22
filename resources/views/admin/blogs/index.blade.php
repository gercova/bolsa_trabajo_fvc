@extends('layouts.app')
@section('title', 'Gestión de Blogs y Noticias - Panel Administrativo')

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="bi bi-newspaper text-purple-600"></i> Gestión de Blogs y Noticias
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Panel
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Blogs y Noticias</span>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="text-emerald-500 hover:text-emerald-700" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-rose-600 text-xl"></i>
                            <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                        </div>
                        <button type="button" class="text-rose-500 hover:text-rose-700" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif

                {{-- Summary Stat Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl font-bold">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $totalBlogs ?? 0 }}</p>
                            <p class="text-xs text-gray-500 font-semibold uppercase">Total Entradas</p>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-bold">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $publishedBlogs ?? 0 }}</p>
                            <p class="text-xs text-gray-500 font-semibold uppercase">Publicadas</p>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-bold">
                            <i class="bi bi-file-earmark-lock-fill"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $draftBlogs ?? 0 }}</p>
                            <p class="text-xs text-gray-500 font-semibold uppercase">Borradores</p>
                        </div>
                    </div>
                </div>

                {{-- Filter and Actions Bar --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    <form action="{{ route('admin.blogs.index') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
                        {{-- Search Input --}}
                        <div class="relative w-full sm:max-w-md">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Buscar por título, contenido o detalles..." 
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        {{-- Status Filter --}}
                        <div class="w-full sm:w-auto">
                            <select name="status" onchange="this.form.submit()" 
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                <option value="">Todos los Estados</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicados</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Borradores</option>
                            </select>
                        </div>

                        {{-- Submit / Clear Buttons --}}
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button type="submit" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5">
                                <i class="bi bi-funnel"></i> Filtrar
                            </button>
                            @if(request('search') || request('status') !== null)
                                <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-semibold rounded-xl transition-colors">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </form>

                    {{-- Create Button --}}
                    <a href="{{ route('admin.blogs.create') }}" 
                       class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-md shadow-purple-500/20 flex items-center justify-center gap-2">
                        <i class="bi bi-plus-circle-fill text-lg"></i>
                        <span>Nueva Publicación</span>
                    </a>
                </div>

                {{-- Table Container --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[700px]">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                    <th class="p-4 w-20 text-center">Portada</th>
                                    <th class="p-4">Título / Extracto</th>
                                    <th class="p-4 w-40">Fecha</th>
                                    <th class="p-4 text-center w-28">Estado</th>
                                    <th class="p-4 text-center w-36">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-sm">
                                @forelse($blogs as $blog)
                                    @php
                                        $coverImg = $blog->images->first()?->url ?? null;
                                    @endphp
                                    <tr class="hover:bg-purple-50/30 transition-colors">
                                        {{-- Thumbnail --}}
                                        <td class="p-4 text-center">
                                            @if($coverImg)
                                                <img src="{{ $coverImg }}" alt="{{ $blog->title }}" class="w-14 h-14 object-cover rounded-xl border border-gray-200 shadow-sm mx-auto">
                                            @else
                                                <div class="w-14 h-14 mx-auto rounded-xl bg-purple-100/80 border border-purple-200 text-purple-600 flex items-center justify-center text-xl shadow-sm">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Title & Excerpt --}}
                                        <td class="p-4">
                                            <div class="text-base font-extrabold text-blue-950 leading-snug line-clamp-1 mb-1">
                                                {{ $blog->title }}
                                            </div>
                                            <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                                                {{ $blog->excerpt(120) }}
                                            </p>
                                            <span class="inline-block mt-1 text-[10px] text-gray-400 font-mono">
                                                Slug: /blog/{{ $blog->slug }}
                                            </span>
                                        </td>

                                        {{-- Date --}}
                                        <td class="p-4 whitespace-nowrap text-xs text-gray-600">
                                            <div class="font-medium text-gray-800 flex items-center gap-1">
                                                <i class="bi bi-calendar3 text-purple-600"></i>
                                                {{ $blog->created_at ? $blog->created_at->format('d/m/Y') : '-' }}
                                            </div>
                                            <span class="text-[11px] text-gray-400">{{ $blog->created_at ? $blog->created_at->format('H:i a') : '' }}</span>
                                        </td>

                                        {{-- Status Toggle --}}
                                        <td class="p-4 text-center whitespace-nowrap">
                                            <form action="{{ route('admin.blogs.toggle-status', $blog) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-sm {{ $blog->is_published ? 'bg-emerald-100 text-emerald-700 border border-emerald-300 hover:bg-emerald-200' : 'bg-amber-100 text-amber-700 border border-amber-300 hover:bg-amber-200' }}">
                                                    <span class="w-2 h-2 rounded-full {{ $blog->is_published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                                    {{ $blog->is_published ? 'Publicado' : 'Borrador' }}
                                                </button>
                                            </form>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="p-4 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.blogs.edit', $blog) }}" 
                                                   class="p-2 text-purple-600 hover:bg-purple-100 rounded-lg transition-colors" 
                                                   title="Editar publicación">
                                                    <i class="bi bi-pencil-square text-base"></i>
                                                </a>

                                                <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" 
                                                      onsubmit="return confirm('¿Está seguro de eliminar la entrada «{{ $blog->title }}»?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="p-2 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors" 
                                                        title="Eliminar publicación">
                                                        <i class="bi bi-trash text-base"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-12 text-center text-gray-500">
                                            <div class="max-w-sm mx-auto space-y-3">
                                                <i class="bi bi-newspaper text-4xl text-gray-300"></i>
                                                <p class="text-base font-bold text-gray-700">No se encontraron artículos de blog</p>
                                                <p class="text-xs text-gray-500">No hay entradas publicadas o ninguna coincide con los filtros de búsqueda.</p>
                                                <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-bold text-xs rounded-xl hover:bg-purple-700 transition">
                                                    Redactar Primer Artículo
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($blogs->hasPages())
                        <div class="p-4 bg-gray-50 border-t border-gray-200">
                            {{ $blogs->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
