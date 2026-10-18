@extends('layouts.app')

@section('title', 'Leyendo: ' . $book->title . ' - Biblioteca Virtual')

@php
    // Siempre utilizamos el proxy de streaming institucional ($streamUrl) para renderizar
    // únicamente el documento PDF limpio dentro del visor sin menús externos ni bloqueos X-Frame-Options.
    $baseStreamUrl = $streamUrl ?? route('biblioteca.stream', $book->slug);
    $viewerUrl =$baseStreamUrl . '#toolbar=1&navpanes=0&scrollbar=1&view=FitH';
    $downloadStreamUrl = $baseStreamUrl . '?download=1';$externalDirectUrl = $directPdfUrl ?? $book->external_url;
@endphp

@section('content')
<div class="h-[calc(100vh-64px)] flex flex-col bg-slate-900 font-sans" x-data="readerViewerApp()">
    {{-- READER TOP TOOLBAR --}}
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
                    @if($book->is_external || $book->external_url)
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30 inline-flex items-center gap-1">
                            <i class="bi bi-file-earmark-pdf-fill text-[9px]"></i>
                            <span>{{ Str::limit($book->publisher ?: 'PDF Académico Directo', 28) }}</span>
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

        {{-- Right: Actions (Favorite Toggle, Download PDF, External PDF, Reload, Fullscreen) --}}
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
                title="Recargar Documento PDF">
                <i class="bi bi-arrow-clockwise text-sm"></i>
            </button>

            {{-- Fullscreen Toggle --}}
            <button type="button" @click="toggleFullscreen()"
                class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition text-xs"
                title="Pantalla Completa">
                <i class="bi" :class="isFullscreen ? 'bi-fullscreen-exit' : 'bi-arrows-fullscreen'"></i>
            </button>

            {{-- Download Direct PDF --}}
            <a href="{{ $downloadStreamUrl }}"
                class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs font-semibold transition flex items-center gap-1.5"
                title="Descargar archivo PDF">
                <i class="bi bi-download"></i>
                <span class="hidden sm:inline">Descargar PDF</span>
            </a>

            {{-- Open Direct PDF in New Tab if External --}}
            @if($externalDirectUrl)
                <a href="{{ $externalDirectUrl }}" target="_blank" rel="noopener noreferrer"
                    class="px-3 py-1.5 rounded-xl bg-teal-600/90 hover:bg-teal-500 text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-xs"
                    title="Abrir PDF directo en pestaña nueva">
                    <i class="bi bi-box-arrow-up-right text-xs"></i>
                    <span class="hidden md:inline">Abrir PDF</span>
                </a>
            @endif
        </div>
    </header>
    {{-- IFRAME VIEWER BODY (INTEGRATED DIRECT PDF READER) --}}
    <main class="flex-1 w-full bg-slate-900 relative overflow-hidden flex flex-col" id="readerContainer">
        <div class="bg-slate-800/90 border-b border-slate-700/80 px-4 py-1.5 text-[11px] text-slate-300 flex items-center justify-between gap-3 flex-shrink-0">
            <div class="flex items-center gap-2 truncate">
                <i class="bi bi-shield-check text-emerald-400"></i>
                <span class="truncate">
                    Lector de Documento Directo (PDF): <strong>{{ $book->publisher ?: 'Repositorio Académico Verificado' }}</strong>
                    @if($book->publication_year) ({{$book->publication_year }}) @endif
                </span>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0 text-[10px]">
                <span class="text-slate-400 hidden sm:inline">Visualización limpia sin páginas externas</span>
            </div>
        </div>

        <div class="flex-1 w-full h-full relative bg-slate-900">
            {{-- Loading Overlay --}}
            <div x-show="isLoading" x-transition.opacity
                class="absolute inset-0 bg-slate-900/80 backdrop-blur-xs z-10 flex flex-col items-center justify-center text-slate-300 space-y-3">
                <div class="w-8 h-8 border-2 border-teal-400 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-xs font-semibold tracking-wide">Cargando documento PDF directo...</p>
            </div>

            <iframe src="{{ $viewerUrl }}" 
                id="libraryViewerIframe"
                @load="isLoading = false"
                class="w-full h-full border-0 bg-slate-800"
                allow="fullscreen; clipboard-write"
                allowfullscreen
                title="{{ $book->title }}">
            </iframe>
        </div>
    </main>
</div>
@endsection
@push('scripts')
<script>
    function readerViewerApp() {
        return {
            isFavorited: {{ $isFavorite ? 'true' : 'false' }},
            isFullscreen: false,
            isLoading: true,

            reloadIframe() {
                const iframe = document.getElementById('libraryViewerIframe');
                if (iframe) {
                    this.isLoading = true;
                    const currentSrc = iframe.src;
                    iframe.src = '';
                    setTimeout(() => { iframe.src = currentSrc; }, 60);
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
                    const res = await fetch(`{{ route('biblioteca.favorite.toggle', $book->id) }}`, {
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