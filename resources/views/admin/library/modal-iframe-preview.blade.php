{{-- ═════════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: VISOR INTEGRADO CON IFRAME PARA LIBROS, REVISTAS Y PAPERS         --}}
{{-- ═════════════════════════════════════════════════════════════════════════ --}}
<div x-show="showIframeModal" x-transition.opacity
    class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex flex-col items-center justify-center p-2 sm:p-4"
    style="display: none;" @keydown.escape.window="closeIframePreviewModal()">
    
    <div class="bg-slate-900 rounded-3xl w-full max-w-6xl h-[94vh] flex flex-col shadow-2xl border border-slate-700/80 overflow-hidden relative">
        
        {{-- Iframe Modal Header Toolbar --}}
        <header class="bg-slate-950 px-4 sm:px-6 py-3 border-b border-slate-800 flex items-center justify-between text-white flex-shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-xl bg-teal-500/20 text-teal-300 border border-teal-500/30 flex items-center justify-center text-lg flex-shrink-0">
                    <i class="bi bi-window-fullscreen"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-teal-500/20 text-teal-300 border border-teal-500/30"
                            x-text="previewData.category || 'Documento'"></span>
                        <span class="text-[11px] text-slate-400 font-semibold truncate"
                            x-text="previewData.program_name || 'General'"></span>
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-white truncate max-w-md sm:max-w-xl"
                        x-text="previewData.title" :title="previewData.title"></h3>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                {{-- Open in new window fallback --}}
                <a :href="previewData.url" target="_blank" rel="noopener noreferrer"
                    class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white text-xs font-semibold transition inline-flex items-center gap-1.5"
                    title="Abrir en pestaña nueva">
                    <i class="bi bi-box-arrow-up-right text-xs"></i>
                    <span class="hidden sm:inline">Pestaña Nueva</span>
                </a>

                {{-- Fullscreen Toggle --}}
                <button type="button" @click="toggleModalFullscreen()"
                    class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition text-xs"
                    title="Pantalla Completa">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>

                {{-- Close Button --}}
                <button type="button" @click="closeIframePreviewModal()"
                    class="p-2 rounded-xl bg-rose-500/20 hover:bg-rose-500 text-rose-300 hover:text-white transition text-xs"
                    title="Cerrar Visor">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>
        </header>

        {{-- Sub-bar info --}}
        <div class="bg-slate-800/80 px-4 py-1.5 border-b border-slate-700/60 text-[11px] text-slate-300 flex items-center justify-between gap-3 flex-shrink-0">
            <span class="truncate">
                <strong>Autor:</strong> <span x-text="previewData.author || 'No especificado'"></span>
                <span x-show="previewData.publisher"> · <strong>Fuente:</strong> <span x-text="previewData.publisher"></span></span>
            </span>
            <span class="text-[10px] text-slate-400 flex-shrink-0">Visor Integrado en la Plataforma</span>
        </div>

        {{-- Iframe Container --}}
        <main class="flex-1 w-full h-full bg-white relative overflow-hidden" id="modalIframeContainer">
            <iframe :src="previewData.url"
                id="adminModalIframe"
                class="w-full h-full border-0 bg-white"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
                loading="lazy"
                :title="previewData.title">
                <div class="h-full flex flex-col items-center justify-center p-8 text-center text-slate-500 space-y-3">
                    <i class="bi bi-file-earmark-text text-4xl text-slate-400"></i>
                    <p class="text-xs">El contenido no puede desplegarse en este marco.</p>
                    <a :href="previewData.url" target="_blank"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold inline-flex items-center gap-1.5">
                        <i class="bi bi-box-arrow-up-right"></i> Abrir en Ventana Auxiliar
                    </a>
                </div>
            </iframe>
        </main>

    </div>
</div>
