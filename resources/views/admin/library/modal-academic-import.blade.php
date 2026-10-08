{{-- ═════════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: BÚSQUEDA Y CARGA MASIVA DE REVISTAS, PAPERS Y ARTÍCULOS ACADÉMICOS --}}
{{-- ═════════════════════════════════════════════════════════════════════════ --}}
<div x-show="showAcademicModal" x-transition.opacity
    class="fixed inset-0 z-50 bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto"
    style="display: none;" @keydown.escape.window="if (!importingAcademic) showAcademicModal = false">
    
    <div class="bg-white rounded-3xl max-w-3xl w-full p-6 sm:p-8 shadow-2xl relative space-y-5 my-auto border border-slate-200 max-h-[94vh] overflow-y-auto"
        @click.away="if (!importingAcademic) showAcademicModal = false">

        {{-- Header --}}
        <div class="flex items-start justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center text-2xl shadow-md flex-shrink-0">
                    <i class="bi bi-journal-arrow-down"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200 tracking-wider">
                            Indexación Abierta
                        </span>
                        <span class="text-[11px] text-slate-400 font-semibold">SciELO · Redalyc · DOAJ · Crossref</span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight mt-0.5">
                        Búsqueda y Carga Masiva Académica
                    </h3>
                    <p class="text-xs text-slate-500">
                        Indexe automáticamente revistas, papers, libros y artículos de investigación revisados por pares según el programa de estudios.
                    </p>
                </div>
            </div>

            <button type="button" @click="showAcademicModal = false" :disabled="importingAcademic"
                class="text-slate-400 hover:text-slate-700 w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center transition disabled:opacity-50">
                <i class="bi bi-x-lg text-xs"></i>
            </button>
        </div>

        {{-- Form / Parameters --}}
        <form @submit.prevent="runAcademicImport()" class="space-y-5" x-show="!importResults">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                {{-- 1. Study Program (Prior Selection) --}}
                <div class="sm:col-span-2">
                    <label for="import_study_program" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center gap-1.5">
                        <i class="bi bi-mortarboard-fill text-indigo-600"></i>
                        <span>Programa de Estudios <span class="text-rose-500">*</span></span>
                    </label>
                    <select id="import_study_program" x-model="importForm.study_program_id" required
                        class="w-full text-xs font-bold px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-slate-800">
                        <option value="all">Todos los Programas de Estudio (Multidisciplinario e Integral)</option>
                        @foreach($studyPrograms as $prog)
                            <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">
                        Los términos de búsqueda y palabras clave se adaptarán rigurosamente al perfil técnico y profesional de la carrera seleccionada.
                    </p>
                </div>

                {{-- 2. Category / Material Type --}}
                <div class="sm:col-span-1">
                    <label for="import_category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center gap-1.5">
                        <i class="bi bi-tag-fill text-indigo-600"></i>
                        <span>Tipo de Contenido</span>
                    </label>
                    <select id="import_category" x-model="importForm.category"
                        class="w-full text-xs font-semibold px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-slate-800">
                        <option value="all">Todas las categorías (Revistas, Papers, Libros...)</option>
                        <option value="Revista">Revistas Científicas (Journals)</option>
                        <option value="Paper">Papers / Artículos de Investigación</option>
                        <option value="Libro">Libros y Capítulos Académicos</option>
                        <option value="Tesis">Tesis y Disertaciones</option>
                        <option value="Documento">Informes y Documentos Técnicos</option>
                    </select>
                </div>

                {{-- 3. Quantity / Limit --}}
                <div class="sm:col-span-1">
                    <label for="import_limit" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center gap-1.5">
                        <i class="bi bi-hash text-indigo-600"></i>
                        <span>Total de Ítems a Importar</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" id="import_limit" min="1" max="100" x-model.number="importForm.limit" required
                            class="w-24 text-xs font-black text-center px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-indigo-900">
                        
                        {{-- Presets --}}
                        <div class="flex items-center gap-1 flex-wrap">
                            <button type="button" @click="importForm.limit = 5"
                                :class="importForm.limit === 5 ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-2.5 py-1.5 rounded-lg text-xs transition">5</button>
                            <button type="button" @click="importForm.limit = 10"
                                :class="importForm.limit === 10 ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-2.5 py-1.5 rounded-lg text-xs transition">10</button>
                            <button type="button" @click="importForm.limit = 20"
                                :class="importForm.limit === 20 ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-2.5 py-1.5 rounded-lg text-xs transition">20</button>
                            <button type="button" @click="importForm.limit = 50"
                                :class="importForm.limit === 50 ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-2.5 py-1.5 rounded-lg text-xs transition">50</button>
                        </div>
                    </div>
                </div>

                {{-- 4. Reliable Sources Filter --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <i class="bi bi-shield-check text-emerald-600"></i>
                        <span>Fuentes Científicas Verificables (Open Access)</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1">
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-slate-100 cursor-pointer text-xs font-semibold text-slate-700 transition">
                            <input type="checkbox" value="scielo" checked x-model="importForm.sources" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span>SciELO</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-slate-100 cursor-pointer text-xs font-semibold text-slate-700 transition">
                            <input type="checkbox" value="redalyc" checked x-model="importForm.sources" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span>Redalyc</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-slate-100 cursor-pointer text-xs font-semibold text-slate-700 transition">
                            <input type="checkbox" value="doaj" checked x-model="importForm.sources" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span>DOAJ</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-slate-100 cursor-pointer text-xs font-semibold text-slate-700 transition">
                            <input type="checkbox" value="crossref" checked x-model="importForm.sources" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span>Crossref (DOI)</span>
                        </label>
                    </div>
                </div>

                {{-- 5. Optional Specific Topic / Custom Query --}}
                <div class="sm:col-span-2">
                    <label for="import_custom_query" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 flex items-center justify-between">
                        <span>Tema Específico o Palabras Clave (Opcional)</span>
                        <span class="text-[10px] text-slate-400 font-normal lowercase">opcional</span>
                    </label>
                    <input type="text" id="import_custom_query" x-model="importForm.custom_query"
                        placeholder="Deje en blanco para usar términos optimizados del programa, o ingrese: ej. 'manejo de suelos tropicales', 'ciberseguridad en redes'..."
                        class="w-full text-xs font-medium px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-slate-800">
                </div>

            </div>

            {{-- Loading Banner --}}
            <div x-show="importingAcademic" class="p-4 bg-indigo-50 border border-indigo-200 rounded-2xl flex items-center gap-3 animate-pulse">
                <div class="animate-spin text-indigo-600 text-xl flex-shrink-0">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="text-xs text-indigo-900">
                    <p class="font-bold">Consultando repositorios científicos abiertos (SciELO, Redalyc, OpenAlex, Crossref)...</p>
                    <p class="text-[11px] text-indigo-700 mt-0.5">Descargando metadatos verificados, filtrando publicaciones sin duplicados y registrando en la biblioteca institucional.</p>
                </div>
            </div>

            {{-- Footer / Submit Button --}}
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
                <button type="button" @click="showAcademicModal = false" :disabled="importingAcademic"
                    class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Cancelar
                </button>

                <button type="submit" :disabled="importingAcademic"
                    class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition inline-flex items-center gap-2 disabled:opacity-50">
                    <template x-if="!importingAcademic">
                        <span class="inline-flex items-center gap-2">
                            <i class="bi bi-cloud-arrow-down-fill text-sm"></i>
                            <span>Iniciar Búsqueda e Importación Masiva</span>
                        </span>
                    </template>
                    <template x-if="importingAcademic">
                        <span class="inline-flex items-center gap-2">
                            <span class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            <span>Procesando Importación...</span>
                        </span>
                    </template>
                </button>
            </div>

        </form>

        {{-- Results Summary View (Displayed after completion) --}}
        <div x-show="importResults" class="space-y-4" style="display: none;">
            
            <div class="p-4 rounded-2xl border"
                :class="importResults?.total > 0 ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-amber-50 border-amber-200 text-amber-900'">
                <div class="flex items-start gap-3">
                    <div class="text-2xl mt-0.5" :class="importResults?.total > 0 ? 'text-emerald-600' : 'text-amber-600'">
                        <i class="bi" :class="importResults?.total > 0 ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'"></i>
                    </div>
                    <div class="space-y-1 flex-1">
                        <h4 class="text-sm font-bold" x-text="importResults?.message"></h4>
                        <div class="flex items-center gap-3 text-xs pt-1 flex-wrap font-semibold">
                            <span class="bg-white/80 px-2.5 py-0.5 rounded-lg border border-emerald-200 text-emerald-800">
                                <strong>Nuevos Creados:</strong> <span x-text="importResults?.saved ?? 0"></span>
                            </span>
                            <span class="bg-white/80 px-2.5 py-0.5 rounded-lg border border-blue-200 text-blue-800">
                                <strong>Actualizados:</strong> <span x-text="importResults?.updated ?? 0"></span>
                            </span>
                            <span class="bg-white/80 px-2.5 py-0.5 rounded-lg border border-slate-200 text-slate-700">
                                <strong>Ya Existentes (Omitidos):</strong> <span x-text="importResults?.skipped ?? 0"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Imported Items Table / List --}}
            <template x-if="importResults?.items && importResults.items.length > 0">
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-slate-50/50">
                    <div class="p-3 bg-slate-100 border-b border-slate-200 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Publicaciones Registradas (<span x-text="importResults.items.length"></span>)
                        </span>
                        <span class="text-[11px] text-slate-500">Disponibles con visor integrado</span>
                    </div>
                    <div class="max-h-60 overflow-y-auto divide-y divide-slate-200">
                        <template x-for="item in importResults.items" :key="item.id">
                            <div class="p-3 bg-white hover:bg-slate-50/80 transition flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0 space-y-0.5">
                                    <div class="flex items-center gap-2">
                                        <span class="px-1.5 py-0.2 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded font-bold text-[10px]" x-text="item.category"></span>
                                        <span class="text-[10px] text-slate-500 font-semibold" x-text="item.program_name"></span>
                                        <span class="text-[10px] text-slate-400" x-show="item.publication_year" x-text="item.publication_year"></span>
                                    </div>
                                    <h5 class="font-bold text-slate-900 truncate" x-text="item.title" :title="item.title"></h5>
                                    <p class="text-[11px] text-slate-500 truncate" x-text="item.author + ' · ' + (item.publisher || 'SciELO/Redalyc')"></p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <button type="button" @click="openIframePreviewModal(item)"
                                        class="px-2.5 py-1 bg-teal-50 hover:bg-teal-100 text-teal-800 border border-teal-200 rounded-lg text-xs font-bold transition inline-flex items-center gap-1"
                                        title="Abrir en Visor Integrado">
                                        <i class="bi bi-window-fullscreen"></i> Visor
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Actions after import --}}
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <button type="button" @click="resetAcademicForm()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Nueva Búsqueda
                </button>

                <button type="button" @click="window.location.reload()"
                    class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition inline-flex items-center gap-2">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Actualizar Repositorio</span>
                </button>
            </div>

        </div>

    </div>
</div>
