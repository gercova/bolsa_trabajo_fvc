@extends('layouts.app')

@section('title', 'Leyendo: ' . $book->title . ' - Biblioteca Virtual')

@section('content')
<div class="h-[calc(100vh-64px)] flex flex-col bg-slate-900 font-sans" x-data="readerViewerApp()">

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- READER TOP TOOLBAR                                                 --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <header class="bg-slate-950/90 backdrop-blur-md text-white border-b border-slate-800 px-4 sm:px-6 py-3 flex items-center justify-between z-20 flex-shrink-0">
        
        {{-- Left: Back & Title --}}
        <div class="flex items-center gap-3.5 min-w-0">
            <a href="{{ route('biblioteca.index') }}"
                class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition flex items-center gap-1.5 text-xs font-bold"
                title="Volver a la Biblioteca">
                <i class="bi bi-arrow-left text-base"></i>
                <span class="hidden sm:inline">Biblioteca</span>
            </a>

            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-blue-500/20 text-blue-300 border border-blue-500/30">
                        {{ $book->category }}
                    </span>
                    <h1 class="text-xs sm:text-sm font-bold text-white truncate max-w-sm sm:max-w-xl">
                        {{ $book->title }}
                    </h1>
                </div>
                <p class="text-[11px] text-slate-400 truncate">
                    {{ $book->author }} @if($book->studyProgram) · {{ $book->studyProgram->name }} @endif
                </p>
            </div>
        </div>

        {{-- Right: Actions (Favorite Toggle, Download, Fullscreen) --}}
        <div class="flex items-center gap-2 flex-shrink-0">
            {{-- Favorite Button --}}
            <button type="button" @click="toggleFavorite()"
                :class="isFavorited ? 'bg-rose-500/20 border-rose-500/40 text-rose-400' : 'bg-white/10 border-white/10 text-slate-300 hover:text-white'"
                class="px-3 py-1.5 rounded-xl border text-xs font-semibold transition flex items-center gap-1.5"
                title="Guardar en Mi Biblioteca">
                <i class="bi" :class="isFavorited ? 'bi-heart-fill text-rose-500' : 'bi-heart'"></i>
                <span class="hidden sm:inline" x-text="isFavorited ? 'En Mi Biblioteca' : 'Guardar'"></span>
            </button>

            {{-- Direct Download / Open --}}
            <a href="{{ route('biblioteca.stream', $book->slug) }}" download target="_blank"
                class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs font-semibold transition flex items-center gap-1.5"
                title="Descargar documento">
                <i class="bi bi-download"></i>
                <span class="hidden sm:inline">Descargar</span>
            </a>
        </div>

    </header>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- PDF VIEWER BODY (STREAMED RESPONSIBLY)                              --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <main class="flex-1 w-full bg-slate-900 relative overflow-hidden">
        <iframe src="{{ route('biblioteca.stream', $book->slug) }}#toolbar=1&navpanes=0&scrollbar=1" 
            class="w-full h-full border-0"
            type="application/pdf"
            title="{{ $book->title }}">
            <div class="h-full flex flex-col items-center justify-center p-8 text-center text-slate-400 space-y-4">
                <i class="bi bi-file-earmark-pdf text-5xl text-slate-500"></i>
                <p class="text-sm">Tu navegador no soporta la visualización integrada de archivos PDF.</p>
                <a href="{{ route('biblioteca.stream', $book->slug) }}" target="_blank"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition inline-flex items-center gap-2">
                    <i class="bi bi-box-arrow-up-right"></i> Abrir o Descargar PDF
                </a>
            </div>
        </iframe>
    </main>

</div>
@endsection

@push('scripts')
<script>
    function readerViewerApp() {
        return {
            isFavorited: {{ $isFavorited ? 'true' : 'false' }},

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
