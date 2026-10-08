@extends('layouts.app')

@section('title', 'Leyendo: ' . $book->title . ' - Biblioteca Virtual')

@php
    $viewerUrl = $book->is_external && $book->external_url
        ? $book->external_url
        : route('biblioteca.stream', $book->slug) . '#toolbar=1&navpanes=0&scrollbar=1';
@endphp

@section('content')
<div class="h-[calc(100vh-64px)] flex flex-col bg-slate-900 font-sans" x-data="readerViewerApp()">

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- READER TOP TOOLBAR                                                 --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <header class="bg-slate-950/95 backdrop-blur-md text-white border-b border-slate-800 px-4 sm:px-6 py-2.5 flex items-center justify-between z-20 flex-shrink-0">
        
        {{-- Left: Back & Title --}}
        <div class="flex items-center gap-3.5 min-w-0">
            <a href="{{ route('biblioteca.index') }}"
                class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition flex items-center gap-1.5 text-xs font-bold"
                title="Volver a la Biblioteca">
                <i class="bi bi-arrow-left text-base"></i>
                <span class="hidden sm:inline">Biblioteca</span>
            </a>

            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-blue-500/20 text-blue-300 border border-blue-500/30">
                        {{ $book->category }}
                    </span>
                    @if($book->is_external)
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30 inline-flex items-center gap-1">
                            <i class="bi bi-globe2 text-[9px]"></i>
                            <span>{{ Str::limit($book->publisher ?: 'Enlace Académico', 25) }}</span>
                        </span>
                    @endif
                    <h1 class="text-xs sm:text-sm font-bold text-white truncate max-w-sm sm:max-w-xl" title="{{ $book->title }}">
                        {{ $book->title }}
                    </h1>
                </div>
                <p class="text-[11px] text-slate-400 truncate mt-0.5">
                    {{ $book->author }} @if($book->studyProgram) · <span class="text-teal-300 font-medium">{{ $book->studyProgram->name }}</span> @endif
                </p>
            </div>
        </div>

        {{-- Right: Actions (Favorite Toggle, Download, Reload, Fullscreen) --}}
        <div class="flex items-center gap-2 flex-shrink-0">
            {{-- Favorite Button --}}
            <button type="button" @click="toggleFavorite()"
                :class="isFavorited ? 'bg-rose-500/20 border-rose-500/40 text-rose-400' : 'bg-white/10 border-white/10 text-slate-300 hover:text-white'"
                class="px-3 py-1.5 rounded-xl border text-xs font-semibold transition flex items-center gap-1.5"
                title="Guardar en Mi Biblioteca">
                <i class="bi" :class="isFavorited ? 'bi-heart-fill text-rose-500' : 'bi-heart'"></i>
                <span class="hidden sm:inline" x-text="isFavorited ? 'En Mi Biblioteca' : 'Favorito'"></span>
            </button>

            {{-- Reload Iframe --}}
            <button type="button" @click="reloadIframe()"
                class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition text-xs"
                title="Recargar Visor">
                <i class="bi bi-arrow-clockwise text-sm"></i>
            </button>

            {{-- Fullscreen Toggle --}}
            <button type="button" @click="toggleFullscreen()"
                class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition text-xs"
                title="Pantalla Completa">
                <i class="bi" :class="isFullscreen ? 'bi-fullscreen-exit' : 'bi-arrows-fullscreen'"></i>
            </button>

            {{-- Direct Download / Open Tab --}}
            @if($book->is_external)
                <a href="{{ $book->external_url }}" target="_blank" rel="noopener noreferrer"
                    class="px-3 py-1.5 rounded-xl bg-teal-600/90 hover:bg-teal-500 text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-xs"
                    title="Abrir fuente original en pestaña nueva">
                    <i class="bi bi-box-arrow-up-right text-xs"></i>
                    <span class="hidden md:inline">Fuente Externa</span>
                </a>
            @else
                <a href="{{ route('biblioteca.stream', $book->slug) }}" download target="_blank"
                    class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs font-semibold transition flex items-center gap-1.5"
                    title="Descargar documento PDF">
                    <i class="bi bi-download"></i>
                    <span class="hidden sm:inline">Descargar</span>
                </a>
            @endif
        </div>

    </header>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- IFRAME VIEWER BODY (INTEGRATED IN-SITE READER)                       --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <main class="flex-1 w-full bg-slate-900 relative overflow-hidden flex flex-col" id="readerContainer">
        @if($book->is_external)
            {{-- Institutional In-Site Notification Bar --}}
            <div class="bg-slate-800/90 border-b border-slate-700/80 px-4 py-1.5 text-[11px] text-slate-300 flex items-center justify-between gap-3 flex-shrink-0">
                <div class="flex items-center gap-2 truncate">
                    <i class="bi bi-shield-check text-emerald-400"></i>
                    <span class="truncate">
                        Visualizador integrado: <strong>{{ $book->publisher ?: 'Publicación Académica Verificada' }}</strong>
                        @if($book->publication_year) ({{ $book->publication_year }}) @endif
                    </span>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0 text-[10px]">
                    <span class="text-slate-400">Permaneciendo en el sitio institucional</span>
                </div>
            </div>
        @endif

        <div class="flex-1 w-full h-full relative bg-slate-900">
            <iframe src="{{ $viewerUrl }}" 
                id="libraryViewerIframe"
                class="w-full h-full border-0 bg-white"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
                loading="lazy"
                title="{{ $book->title }}">
                <div class="h-full flex flex-col items-center justify-center p-8 text-center text-slate-400 space-y-4">
                    <i class="bi bi-journal-text text-5xl text-slate-500"></i>
                    <p class="text-sm">Tu navegador no soporta la visualización integrada de este marco.</p>
                    <a href="{{ $viewerUrl }}" target="_blank"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition inline-flex items-center gap-2">
                        <i class="bi bi-box-arrow-up-right"></i> Abrir Contenido en Ventana Auxiliar
                    </a>
                </div>
            </iframe>
        </div>
    </main>

</div>
@endsection

@push('scripts')
<script>
    function readerViewerApp() {
        return {
            isFavorited: {{ $isFavorited ? 'true' : 'false' }},
            isFullscreen: false,

            reloadIframe() {
                const iframe = document.getElementById('libraryViewerIframe');
                if (iframe) {
                    const currentSrc = iframe.src;
                    iframe.src = '';
                    setTimeout(() => { iframe.src = currentSrc; }, 50);
                }
            },

            toggleFullscreen() {
                const elem = document.getElementById('readerContainer') || document.documentElement;
                if (!document.fullscreenElement) {
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.webkitRequestFullscreen) {
                        elem.webkitRequestFullscreen();
                    }
                    this.isFullscreen = true;
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    }
                    this.isFullscreen = false;
                }
            },

            async toggleFavorite() {
                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch(`/biblioteca/favorito/{{ $book->id }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        }
                    });
                    const json = await res.json();
                    if (json.success) {
                        this.isFavorited = json.favorited;
                    }
                } catch (e) {
                    console.error('Error toggling favorite:', e);
                }
            }
        };
    }
</script>
@endpush
