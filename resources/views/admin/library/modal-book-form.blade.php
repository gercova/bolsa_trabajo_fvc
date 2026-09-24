{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: SUBIR / CREAR NUEVO CONTENIDO DE BIBLIOTECA                  --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div x-show="showCreateModal" x-transition.opacity
    x-effect="document.body.style.overflow = (showCreateModal || showEditModal) ? 'hidden' : ''"
    class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
    style="display: none;" @keydown.escape.window="showCreateModal = false">
    
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl relative space-y-4 my-auto border border-slate-200 max-h-[92vh] overflow-y-auto"
        @click.away="showCreateModal = false">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#0b4d57] border border-teal-200 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="bi bi-journal-plus"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Agregar Contenido a la Biblioteca</h3>
                    <p class="text-xs text-slate-500">Suba libros, revistas, papers o documentos en PDF o mediante enlace web.</p>
                </div>
            </div>
            <button type="button" @click="showCreateModal = false"
                class="text-slate-400 hover:text-slate-700 w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center transition">
                <i class="bi bi-x-lg text-xs"></i>
            </button>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.library.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-4" @submit="isSubmitting = true">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                {{-- Title --}}
                <div class="sm:col-span-2">
                    <label for="create_book_title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Título del Libro o Documento <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" id="create_book_title" required
                        placeholder="Ej. JADAM Agricultura Ecológica"
                        class="w-full text-xs font-semibold px-3 py-2 bg-slate-50/50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition">
                </div>

                {{-- Author --}}
                <div class="sm:col-span-1">
                    <label for="create_book_author" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Autor / Autores <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="author" id="create_book_author" required
                        placeholder="Ej. Youngsang Cho"
                        class="w-full text-xs font-medium px-3 py-2 bg-slate-50/50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition">
                </div>

                {{-- Category / Content Type --}}
                <div class="sm:col-span-1">
                    <label for="create_book_category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Tipo de Contenido <span class="text-rose-500">*</span>
                    </label>
                    <select name="category" id="create_book_category" required
                        class="w-full text-xs border border-slate-300 rounded-xl py-2 px-2.5 bg-white text-slate-800 font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition">
                        <option value="Libro">Libro</option>
                        <option value="Revista">Revista Científica / Técnica</option>
                        <option value="Paper">Paper / Artículo Académico</option>
                        <option value="Tesis">Tesis / Proyecto de Titulación</option>
                        <option value="Documento">Documento / Manual Oficial</option>
                    </select>
                </div>

                {{-- Career / Study Program --}}
                <div class="sm:col-span-2">
                    <label for="create_book_program" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Carrera / Programa Asignado
                    </label>
                    <select name="study_program_id" id="create_book_program"
                        class="w-full text-xs border border-slate-300 rounded-xl py-2 px-2.5 bg-white text-slate-800 font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition">
                        <option value="">General (Todas las carreras / Transversal)</option>
                        @foreach($studyPrograms as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Source Selector (PDF File vs External URL) --}}
                <div class="sm:col-span-2 space-y-2">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-700">
                            Origen del Documento <span class="text-rose-500">*</span>
                        </span>
                        <div class="inline-flex p-0.5 bg-slate-100 rounded-lg text-xs font-semibold">
                            <button type="button" @click="sourceType = 'file'"
                                :class="sourceType === 'file' ? 'bg-white text-teal-800 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                                class="px-2.5 py-1 rounded-md transition">
                                <i class="bi bi-file-earmark-pdf mr-1 text-red-500"></i> Archivo PDF
                            </button>
                            <button type="button" @click="sourceType = 'link'"
                                :class="sourceType === 'link' ? 'bg-white text-teal-800 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                                class="px-2.5 py-1 rounded-md transition">
                                <i class="bi bi-link-45deg mr-1 text-blue-500"></i> Enlace Web (URL)
                            </button>
                        </div>
                    </div>

                    {{-- Option 1: File Dropzone --}}
                    <div x-show="sourceType === 'file'" x-transition.opacity>
                        <div class="p-4 rounded-xl text-center cursor-pointer bg-slate-50 hover:bg-teal-50/40 border-2 border-dashed border-slate-300 hover:border-teal-500 transition relative"
                            @click="$refs.createBookFileInput.click()">
                            <input type="file" name="file" accept="application/pdf" x-ref="createBookFileInput" class="hidden"
                                @change="
                                    const file = $event.target.files[0];
                                    if(file) {
                                        createSelectedPdfName = file.name + ' (' + (file.size / (1024*1024)).toFixed(2) + ' MB)';
                                    } else {
                                        createSelectedPdfName = '';
                                    }
                                ">
                            <div class="space-y-1">
                                <div class="w-8 h-8 mx-auto rounded-lg bg-red-50 text-red-500 flex items-center justify-center text-lg">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </div>
                                <template x-if="!createSelectedPdfName">
                                    <div>
                                        <p class="text-xs font-bold text-slate-700">Arrastre o seleccione el archivo PDF</p>
                                        <p class="text-[11px] text-slate-400">Formato .pdf · Tamaño máx. 50 MB</p>
                                    </div>
                                </template>
                                <template x-if="createSelectedPdfName">
                                    <p class="text-xs font-extrabold text-emerald-700 flex items-center justify-center gap-1">
                                        <i class="bi bi-check-circle-fill"></i> <span x-text="createSelectedPdfName"></span>
                                    </p>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Option 2: External URL --}}
                    <div x-show="sourceType === 'link'" x-transition.opacity style="display: none;">
                        <input type="url" name="external_url" placeholder="https://scielo.org/... o enlace a repositorio / Google Drive"
                            class="w-full text-xs px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition">
                        <p class="text-[11px] text-slate-400 mt-1">Permite enlazar publicaciones alojadas en repositorios externos sin recargar almacenamiento.</p>
                    </div>
                </div>

                {{-- Cover Image Dropzone --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Imagen de Portada (Opcional)
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="file" name="cover" accept="image/jpeg,image/png,image/webp" x-ref="createCoverInput"
                            class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                    </div>
                </div>

                {{-- Reference Activate Checkbox (Same base design as /admin-programas) --}}
                <div class="sm:col-span-2 pt-1">
                    <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100/80 transition-colors w-full">
                        <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-teal-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0b4d57] relative flex-shrink-0"></div>
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Contenido Activo</span>
                            <span class="text-[11px] text-slate-500 block">Los documentos activos se muestran a estudiantes y docentes en la biblioteca virtual.</span>
                        </div>
                    </label>
                </div>

                {{-- Optional Metadata Collapsible --}}
                <div class="sm:col-span-2">
                    <details class="group border border-slate-200 rounded-xl bg-slate-50/50 overflow-hidden text-xs">
                        <summary class="flex items-center justify-between px-3 py-2 text-slate-600 cursor-pointer hover:bg-slate-100/80 transition select-none font-semibold">
                            <span class="flex items-center gap-1.5"><i class="bi bi-sliders text-teal-700"></i> Metadatos adicionales (Editorial, Año, ISBN, Sinopsis)</span>
                            <i class="bi bi-chevron-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <div class="p-3 pt-2 border-t border-slate-100 space-y-3 bg-white">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Editorial</label>
                                    <input type="text" name="publisher" placeholder="Ej. Editorial Lumbreras"
                                        class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Año de Publicación</label>
                                    <input type="number" name="publication_year" placeholder="{{ date('Y') }}" min="1900" max="{{ date('Y')+1 }}"
                                        class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">ISBN / Código</label>
                                    <input type="text" name="isbn" placeholder="Ej. 978-607-..."
                                        class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Sinopsis o Resumen</label>
                                <textarea name="description" rows="2" placeholder="Breve descripción o temática del libro..."
                                    class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg"></textarea>
                            </div>
                        </div>
                    </details>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" @click="showCreateModal = false"
                    class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Cancelar
                </button>
                <button type="submit" :disabled="isSubmitting"
                    class="px-5 py-2 text-xs font-bold text-white bg-[#0b4d57] hover:bg-teal-900 rounded-xl shadow-xs transition flex items-center gap-2">
                    <template x-if="isSubmitting">
                        <i class="bi bi-arrow-repeat animate-spin"></i>
                    </template>
                    <template x-if="!isSubmitting">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    </template>
                    <span>Guardar y Asignar Contenido</span>
                </button>
            </div>
        </form>

    </div>
</div>
