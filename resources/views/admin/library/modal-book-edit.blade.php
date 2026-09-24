{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: EDITAR CONTENIDO DE BIBLIOTECA                              --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div x-show="showEditModal" x-transition.opacity
    x-effect="document.body.style.overflow = (showCreateModal || showEditModal) ? 'hidden' : ''"
    class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
    style="display: none;" @keydown.escape.window="showEditModal = false">
    
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl relative space-y-4 my-auto border border-slate-200 max-h-[92vh] overflow-y-auto"
        @click.away="showEditModal = false">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#0b4d57] border border-teal-200 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Editar Contenido de Biblioteca</h3>
                    <p class="text-xs text-slate-500">Actualice la información, carrera asignada o reemplace el documento.</p>
                </div>
            </div>
            <button type="button" @click="showEditModal = false"
                class="text-slate-400 hover:text-slate-700 w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center transition">
                <i class="bi bi-x-lg text-xs"></i>
            </button>
        </div>

        {{-- Form --}}
        <form :action="editActionUrl" method="POST" enctype="multipart/form-data"
            class="space-y-4" @submit="isSubmitting = true">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                {{-- Title --}}
                <div class="sm:col-span-2">
                    <label for="edit_book_title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Título del Libro o Documento <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" id="edit_book_title" x-model="editForm.title" required
                        class="w-full text-xs font-semibold px-3 py-2 bg-slate-50/50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition">
                </div>

                {{-- Author --}}
                <div class="sm:col-span-1">
                    <label for="edit_book_author" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Autor / Autores <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="author" id="edit_book_author" x-model="editForm.author" required
                        class="w-full text-xs font-medium px-3 py-2 bg-slate-50/50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition">
                </div>

                {{-- Category / Content Type --}}
                <div class="sm:col-span-1">
                    <label for="edit_book_category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Tipo de Contenido <span class="text-rose-500">*</span>
                    </label>
                    <select name="category" id="edit_book_category" x-model="editForm.category" required
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
                    <label for="edit_book_program" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Carrera / Programa Asignado
                    </label>
                    <select name="study_program_id" id="edit_book_program" x-model="editForm.study_program_id"
                        class="w-full text-xs border border-slate-300 rounded-xl py-2 px-2.5 bg-white text-slate-800 font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition">
                        <option value="">General (Todas las carreras / Transversal)</option>
                        @foreach($studyPrograms as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Current Document & Replacement --}}
                <div class="sm:col-span-2 space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Archivo PDF o Enlace Web Actual y Reemplazo
                    </label>

                    <template x-if="editForm.file_url">
                        <div class="flex items-center justify-between p-2.5 bg-slate-100 rounded-xl border border-slate-200 text-xs">
                            <div class="flex items-center gap-2 truncate">
                                <i class="bi bi-file-earmark-pdf-fill text-red-500 text-lg flex-shrink-0"></i>
                                <span class="font-semibold text-slate-700 truncate" x-text="editForm.file_size"></span>
                            </div>
                            <a :href="editForm.file_url" target="_blank"
                                class="px-2.5 py-1 bg-white hover:bg-slate-50 text-slate-700 font-bold rounded-lg border border-slate-300 text-xs transition flex-shrink-0 flex items-center gap-1">
                                <i class="bi bi-box-arrow-up-right"></i> Abrir Actual
                            </a>
                        </div>
                    </template>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                        <div>
                            <span class="block text-[11px] font-semibold text-slate-600 mb-1">Reemplazar archivo PDF</span>
                            <input type="file" name="file" accept="application/pdf"
                                class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[11px] file:font-semibold file:bg-slate-200 file:text-slate-700 cursor-pointer">
                        </div>
                        <div>
                            <span class="block text-[11px] font-semibold text-slate-600 mb-1">O cambiar Enlace Web</span>
                            <input type="url" name="external_url" x-model="editForm.external_url" placeholder="https://..."
                                class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                    </div>
                </div>

                {{-- Cover Image Replacement --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Imagen de Portada
                    </label>
                    <div class="flex items-center gap-3">
                        <template x-if="editForm.cover_url">
                            <img :src="editForm.cover_url" class="w-9 h-12 object-cover rounded-md border border-slate-200 flex-shrink-0">
                        </template>
                        <input type="file" name="cover" accept="image/jpeg,image/png,image/webp"
                            class="text-xs text-slate-500 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[11px] file:font-semibold file:bg-slate-100 file:text-slate-700 cursor-pointer">
                    </div>
                </div>

                {{-- Reference Activate Checkbox --}}
                <div class="sm:col-span-2 pt-1">
                    <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100/80 transition-colors w-full">
                        <input type="checkbox" name="is_active" value="1" x-model="editForm.is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-teal-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0b4d57] relative flex-shrink-0"></div>
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Contenido Activo</span>
                            <span class="text-[11px] text-slate-500 block">Los documentos activos se muestran públicamente en el portal institucional.</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" @click="showEditModal = false"
                    class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Cancelar
                </button>
                <button type="submit" :disabled="isSubmitting"
                    class="px-5 py-2 text-xs font-bold text-white bg-[#0b4d57] hover:bg-teal-900 rounded-xl shadow-xs transition flex items-center gap-2">
                    <template x-if="isSubmitting">
                        <i class="bi bi-arrow-repeat animate-spin"></i>
                    </template>
                    <template x-if="!isSubmitting">
                        <i class="bi bi-check2-circle"></i>
                    </template>
                    <span>Guardar Cambios</span>
                </button>
            </div>
        </form>

    </div>
</div>
