<template>
  <AdminLayout title="Gestión del TUPA Institucional">
    <div class="space-y-6">
      <!-- Breadcrumb & Top Bar -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            <span class="p-2 rounded-xl bg-purple-100 text-purple-700 inline-flex">
              <i class="bi bi-file-earmark-ruled-fill text-xl"></i>
            </span>
            Gestión del TUPA Institucional
          </h1>
          <p class="text-sm text-gray-500 mt-1">
            Administra el Texto Único de Procedimientos Administrativos, reglamentos oficiales, categorías y trámites.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <Link
            v-if="currentTab === 'documents'"
            :href="route('admin.tupa.create')"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/20 transition-all transform active:scale-95"
          >
            <i class="bi bi-plus-circle text-lg"></i>
            <span>Registrar Nuevo TUPA</span>
          </Link>
        </div>
      </div>

      <!-- Quick Stats Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Documentos TUPA</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ tupas.total ?? 0 }}</h3>
            <span class="text-xs text-purple-600 font-medium">Reglamentos registrados</span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl">
            <i class="bi bi-file-earmark-pdf"></i>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">TUPAs Activos</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-1">
              {{ activeTupasCount }}
            </h3>
            <span class="text-xs text-emerald-700 font-medium">Vigentes al público</span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
            <i class="bi bi-check2-circle"></i>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Categorías TUPA</p>
            <h3 class="text-2xl font-black text-blue-600 mt-1">{{ categories.total ?? 0 }}</h3>
            <span class="text-xs text-blue-700 font-medium">Grupos clasificados</span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
            <i class="bi bi-folder-fill"></i>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Procedimientos</p>
            <h3 class="text-2xl font-black text-indigo-600 mt-1">{{ procedures.total ?? 0 }}</h3>
            <span class="text-xs text-indigo-700 font-medium">Trámites institucionales</span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl">
            <i class="bi bi-list-check"></i>
          </div>
        </div>
      </div>

      <!-- Tab Navigation -->
      <div class="bg-white rounded-2xl p-2 border border-gray-100 shadow-sm flex flex-wrap sm:flex-nowrap gap-2">
        <button
          type="button"
          @click="switchTab('documents')"
          :class="currentTab === 'documents'
            ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold'
            : 'text-gray-600 hover:bg-gray-100 font-medium'"
          class="flex-1 py-3 px-4 rounded-xl text-sm transition-all duration-200 flex items-center justify-center gap-2"
        >
          <i class="bi bi-file-earmark-pdf-fill text-base"></i>
          <span>Documentos TUPA</span>
          <span
            class="ml-1 px-2 py-0.5 text-xs rounded-full"
            :class="currentTab === 'documents' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'"
          >{{ tupas.total ?? 0 }}</span>
        </button>

        <button
          type="button"
          @click="switchTab('categories')"
          :class="currentTab === 'categories'
            ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold'
            : 'text-gray-600 hover:bg-gray-100 font-medium'"
          class="flex-1 py-3 px-4 rounded-xl text-sm transition-all duration-200 flex items-center justify-center gap-2"
        >
          <i class="bi bi-folder-fill text-base"></i>
          <span>Categorías TUPA</span>
          <span
            class="ml-1 px-2 py-0.5 text-xs rounded-full"
            :class="currentTab === 'categories' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'"
          >{{ categories.total ?? 0 }}</span>
        </button>

        <button
          type="button"
          @click="switchTab('procedures')"
          :class="currentTab === 'procedures'
            ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold'
            : 'text-gray-600 hover:bg-gray-100 font-medium'"
          class="flex-1 py-3 px-4 rounded-xl text-sm transition-all duration-200 flex items-center justify-center gap-2"
        >
          <i class="bi bi-list-check text-base"></i>
          <span>Procedimientos TUPA</span>
          <span
            class="ml-1 px-2 py-0.5 text-xs rounded-full"
            :class="currentTab === 'procedures' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'"
          >{{ procedures.total ?? 0 }}</span>
        </button>
      </div>

      <!-- ========================================== -->
      <!-- TAB 1: DOCUMENTOS TUPA                    -->
      <!-- ========================================== -->
      <div v-show="currentTab === 'documents'" class="space-y-6">
        <!-- Filters Box -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
          <form @submit.prevent="applyFilters" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Documento</label>
              <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                  type="text"
                  v-model="filterForm.search"
                  placeholder="Título o palabra clave..."
                  class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                />
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
              <select
                v-model="filterForm.status"
                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              >
                <option value="">Todos los estados</option>
                <option value="active">Activo</option>
                <option value="inactive">Inactivo</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-600 mb-1">Año Vigencia</label>
              <input
                type="number"
                v-model="filterForm.year"
                placeholder="Ej: 2026"
                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              />
            </div>

            <div class="flex items-end gap-2">
              <button
                type="submit"
                class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors flex items-center justify-center gap-1.5"
              >
                <i class="bi bi-funnel-fill text-xs"></i>
                Filtrar
              </button>
              <button
                type="button"
                @click="clearFilters"
                class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors"
                title="Limpiar filtros"
              >
                <i class="bi bi-arrow-counterclockwise"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                  <th class="py-4 px-6">Documento TUPA</th>
                  <th class="py-4 px-6">Archivo PDF</th>
                  <th class="py-4 px-6">Vigencia</th>
                  <th class="py-4 px-6">Estado</th>
                  <th class="py-4 px-6 text-right">Acciones</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 text-sm">
                <tr
                  v-for="tupa in tupas.data"
                  :key="tupa.id"
                  class="hover:bg-purple-50/30 transition-colors"
                >
                  <td class="py-4 px-6">
                    <div class="flex items-start gap-3">
                      <div class="p-2.5 bg-purple-100 text-purple-600 rounded-xl flex-shrink-0 mt-0.5">
                        <i class="bi bi-file-earmark-pdf text-xl"></i>
                      </div>
                      <div>
                        <h3 class="font-bold text-gray-900 leading-tight">
                          {{ tupa.title }}
                        </h3>
                        <p class="text-xs text-gray-500 line-clamp-2 mt-1">
                          {{ tupa.description }}
                        </p>
                      </div>
                    </div>
                  </td>

                  <td class="py-4 px-6 whitespace-nowrap">
                    <div v-if="tupa.url" class="flex items-center gap-2">
                      <button
                        type="button"
                        @click="previewPdf(tupa.url, tupa.title)"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors"
                      >
                        <i class="bi bi-eye"></i> Previsualizar
                      </button>
                      <a
                        :href="tupa.url"
                        target="_blank"
                        download
                        class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg transition-colors"
                        title="Descargar PDF"
                      >
                        <i class="bi bi-download text-base"></i>
                      </a>
                    </div>
                    <span v-else class="text-xs text-gray-400 italic">Sin archivo</span>
                  </td>

                  <td class="py-4 px-6 whitespace-nowrap">
                    <div class="text-xs space-y-0.5">
                      <div class="flex items-center gap-1.5 text-gray-700">
                        <i class="bi bi-calendar-event text-purple-500"></i>
                        <span>Desde: <strong>{{ formatDate(tupa.effective_start_date) }}</strong></span>
                      </div>
                      <div class="flex items-center gap-1.5 text-gray-500">
                        <i class="bi bi-calendar-check"></i>
                        <span>Hasta: {{ tupa.effective_end_date ? formatDate(tupa.effective_end_date) : 'Indefinido' }}</span>
                      </div>
                    </div>
                  </td>

                  <td class="py-4 px-6 whitespace-nowrap">
                    <button
                      type="button"
                      @click="toggleStatus(tupa)"
                      class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200"
                      :class="tupa.is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    >
                      <span
                        class="w-1.5 h-1.5 rounded-full"
                        :class="tupa.is_active ? 'bg-emerald-500' : 'bg-gray-400'"
                      ></span>
                      <span>{{ tupa.is_active ? 'Activo' : 'Inactivo' }}</span>
                    </button>
                  </td>

                  <td class="py-4 px-6 whitespace-nowrap text-right">
                    <div class="flex items-center justify-end gap-2">
                      <Link
                        :href="route('admin.tupa.edit', tupa.id)"
                        class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors"
                        title="Editar TUPA"
                      >
                        <i class="bi bi-pencil-square"></i>
                      </Link>

                      <button
                        type="button"
                        @click="openDeleteModal(route('admin.tupa.destroy', tupa.id), tupa.title, 'Documento TUPA')"
                        class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors"
                        title="Eliminar TUPA"
                      >
                        <i class="bi bi-trash3"></i>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr v-if="!tupas.data || tupas.data.length === 0">
                  <td colspan="5" class="py-12 px-6 text-center">
                    <div class="max-w-xs mx-auto text-center space-y-3">
                      <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                        <i class="bi bi-file-earmark-x text-3xl"></i>
                      </div>
                      <p class="text-base font-bold text-gray-700">No se encontraron documentos TUPA</p>
                      <p class="text-xs text-gray-500">Registra un nuevo reglamento o cuadro de procedimientos.</p>
                      <Link
                        :href="route('admin.tupa.create')"
                        class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors"
                      >
                        Registrar Nuevo TUPA
                      </Link>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="tupas.links && tupas.links.length > 3" class="p-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
            <div>
              Mostrando <span class="font-bold">{{ tupas.from ?? 0 }}</span> a <span class="font-bold">{{ tupas.to ?? 0 }}</span> de <span class="font-bold">{{ tupas.total ?? 0 }}</span> registros
            </div>
            <div class="flex items-center gap-1">
              <template v-for="(link, i) in tupas.links" :key="i">
                <Link
                  v-if="link.url"
                  :href="link.url"
                  v-html="link.label"
                  class="px-3 py-1.5 rounded-lg border text-xs font-medium transition-colors"
                  :class="link.active ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100'"
                />
                <span
                  v-else
                  v-html="link.label"
                  class="px-3 py-1.5 rounded-lg border text-xs font-medium text-gray-400 border-gray-100 bg-gray-50 opacity-60"
                />
              </template>
            </div>
          </div>
        </div>
      </div>

      <!-- ========================================== -->
      <!-- TAB 2: CATEGORÍAS TUPA                    -->
      <!-- ========================================== -->
      <div v-show="currentTab === 'categories'" class="space-y-6">
        <!-- Header Info -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
          <div>
            <h2 class="text-lg font-bold text-gray-900">Categorías de Trámites y Procedimientos</h2>
            <p class="text-sm text-gray-500">Grupos funcionales que clasifican los diferentes procedimientos administrativos institucionales.</p>
          </div>
        </div>

        <!-- Filter Box -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
          <form @submit.prevent="applyFilters" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Categoría</label>
              <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                  type="text"
                  v-model="filterForm.search"
                  placeholder="Nombre de categoría..."
                  class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                />
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
              <select
                v-model="filterForm.status"
                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              >
                <option value="">Todos los estados</option>
                <option value="active">Activo</option>
                <option value="inactive">Inactivo</option>
              </select>
            </div>

            <div class="flex items-end gap-2">
              <button
                type="submit"
                class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors flex items-center justify-center gap-1.5"
              >
                <i class="bi bi-funnel-fill text-xs"></i>
                Filtrar
              </button>
              <button
                type="button"
                @click="clearFilters"
                class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors"
                title="Limpiar filtros"
              >
                <i class="bi bi-arrow-counterclockwise"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                  <th class="py-4 px-6">Nombre de Categoría</th>
                  <th class="py-4 px-6 text-center">Procedimientos Vinculados</th>
                  <th class="py-4 px-6">Estado</th>
                  <th class="py-4 px-6 text-right">Acciones</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 text-sm">
                <tr
                  v-for="cat in categories.data"
                  :key="cat.id"
                  class="hover:bg-purple-50/30 transition-colors"
                >
                  <td class="py-4 px-6 font-bold text-gray-900">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                        <i :class="cat.icon ? `bi ${cat.icon}` : 'bi bi-folder-fill'"></i>
                      </div>
                      <span>{{ cat.name }}</span>
                    </div>
                  </td>

                  <td class="py-4 px-6 text-center">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                      <i class="bi bi-list-check"></i>
                      {{ cat.procedures_count ?? 0 }} trámites
                    </span>
                  </td>

                  <td class="py-4 px-6 whitespace-nowrap">
                    <button
                      type="button"
                      @click="toggleCategoryStatus(cat)"
                      class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200"
                      :class="cat.is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    >
                      <span
                        class="w-1.5 h-1.5 rounded-full"
                        :class="cat.is_active ? 'bg-emerald-500' : 'bg-gray-400'"
                      ></span>
                      <span>{{ cat.is_active ? 'Activo' : 'Inactivo' }}</span>
                    </button>
                  </td>

                  <td class="py-4 px-6 whitespace-nowrap text-right">
                    <div class="flex items-center justify-end gap-2">
                      <button
                        type="button"
                        @click="openDeleteModal(route('admin.tupa.categories.destroy', cat.id), cat.name, 'Categoría TUPA')"
                        class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors"
                        title="Eliminar Categoría"
                      >
                        <i class="bi bi-trash3"></i>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr v-if="!categories.data || categories.data.length === 0">
                  <td colspan="4" class="py-12 px-6 text-center">
                    <div class="max-w-xs mx-auto text-center space-y-3">
                      <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                        <i class="bi bi-folder-x text-3xl"></i>
                      </div>
                      <p class="text-base font-bold text-gray-700">No se encontraron categorías</p>
                      <p class="text-xs text-gray-500">No hay categorías que coincidan con la búsqueda.</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="categories.links && categories.links.length > 3" class="p-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
            <div>
              Mostrando <span class="font-bold">{{ categories.from ?? 0 }}</span> a <span class="font-bold">{{ categories.to ?? 0 }}</span> de <span class="font-bold">{{ categories.total ?? 0 }}</span> categorías
            </div>
            <div class="flex items-center gap-1">
              <template v-for="(link, i) in categories.links" :key="i">
                <Link
                  v-if="link.url"
                  :href="link.url"
                  v-html="link.label"
                  class="px-3 py-1.5 rounded-lg border text-xs font-medium transition-colors"
                  :class="link.active ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100'"
                />
                <span
                  v-else
                  v-html="link.label"
                  class="px-3 py-1.5 rounded-lg border text-xs font-medium text-gray-400 border-gray-100 bg-gray-50 opacity-60"
                />
              </template>
            </div>
          </div>
        </div>
      </div>

      <!-- ========================================== -->
      <!-- TAB 3: PROCEDIMIENTOS TUPA                -->
      <!-- ========================================== -->
      <div v-show="currentTab === 'procedures'" class="space-y-6">
        <!-- Header Info -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
          <div>
            <h2 class="text-lg font-bold text-gray-900">Catálogo de Procedimientos y Tasas Administrativas</h2>
            <p class="text-sm text-gray-500">Detalle de requisitos, costos, plazos de atención y áreas responsables por cada trámite.</p>
          </div>
        </div>

        <!-- Filter Box -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
          <form @submit.prevent="applyFilters" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
            <div>
              <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Procedimiento</label>
              <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                  type="text"
                  v-model="filterForm.search"
                  placeholder="Código, trámite u oficina..."
                  class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                />
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-600 mb-1">Categoría</label>
              <select
                v-model="filterForm.category_id"
                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              >
                <option value="">Todas las categorías</option>
                <option v-for="cat in allCategoriesList" :key="cat.id" :value="cat.id">
                  {{ cat.name }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-600 mb-1">Documento TUPA</label>
              <select
                v-model="filterForm.tupa_id"
                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              >
                <option value="">Todos los TUPAs</option>
                <option v-for="t in allTupasList" :key="t.id" :value="t.id">
                  {{ t.title }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
              <select
                v-model="filterForm.status"
                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              >
                <option value="">Todos los estados</option>
                <option value="active">Activo</option>
                <option value="inactive">Inactivo</option>
              </select>
            </div>

            <div class="flex items-end gap-2">
              <button
                type="submit"
                class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors flex items-center justify-center gap-1.5"
              >
                <i class="bi bi-funnel-fill text-xs"></i>
                Filtrar
              </button>
              <button
                type="button"
                @click="clearFilters"
                class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors"
                title="Limpiar filtros"
              >
                <i class="bi bi-arrow-counterclockwise"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Procedures Table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                  <th class="py-4 px-6">Código / Procedimiento</th>
                  <th class="py-4 px-6">Categoría</th>
                  <th class="py-4 px-6">Costo / % UIT</th>
                  <th class="py-4 px-6">Atención & Área</th>
                  <th class="py-4 px-6">Estado</th>
                  <th class="py-4 px-6 text-right">Acciones</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 text-sm">
                <tr
                  v-for="proc in procedures.data"
                  :key="proc.id"
                  class="hover:bg-purple-50/30 transition-colors"
                >
                  <td class="py-4 px-6">
                    <div class="flex items-start gap-3">
                      <span class="px-2.5 py-1 rounded-lg bg-purple-100 text-purple-700 font-mono text-xs font-bold mt-0.5">
                        {{ proc.code }}
                      </span>
                      <div>
                        <h4 class="font-bold text-gray-900 leading-snug">{{ proc.name }}</h4>
                        <p v-if="proc.description" class="text-xs text-gray-500 line-clamp-2 mt-0.5">{{ proc.description }}</p>
                        <div v-if="proc.requirements && proc.requirements.length" class="mt-1 text-[11px] text-gray-400 flex items-center gap-1">
                          <i class="bi bi-card-checklist text-purple-500"></i>
                          <span>{{ proc.requirements.length }} requisitos detallados</span>
                        </div>
                      </div>
                    </div>
                  </td>

                  <td class="py-4 px-6 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                      <i class="bi bi-folder text-purple-600"></i>
                      {{ proc.category ? proc.category.name : 'Sin categoría' }}
                    </span>
                  </td>

                  <td class="py-4 px-6 whitespace-nowrap font-medium">
                    <div class="text-sm font-bold text-emerald-700">
                      {{ Number(proc.cost) > 0 ? `S/ ${Number(proc.cost).toFixed(2)}` : 'Gratuito' }}
                    </div>
                    <div v-if="proc.uit_percent" class="text-[11px] text-gray-400">
                      {{ proc.uit_percent }}% UIT
                    </div>
                  </td>

                  <td class="py-4 px-6 whitespace-nowrap text-xs text-gray-600">
                    <div class="flex items-center gap-1.5 font-medium text-gray-700">
                      <i class="bi bi-clock-history text-purple-500"></i>
                      <span>{{ proc.duration || 'Automático' }}</span>
                    </div>
                    <div v-if="proc.office" class="text-gray-400 text-[11px] mt-0.5 truncate max-w-[150px]">
                      {{ proc.office }}
                    </div>
                  </td>

                  <td class="py-4 px-6 whitespace-nowrap">
                    <button
                      type="button"
                      @click="toggleProcedureStatus(proc)"
                      class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200"
                      :class="proc.is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    >
                      <span
                        class="w-1.5 h-1.5 rounded-full"
                        :class="proc.is_active ? 'bg-emerald-500' : 'bg-gray-400'"
                      ></span>
                      <span>{{ proc.is_active ? 'Activo' : 'Inactivo' }}</span>
                    </button>
                  </td>

                  <td class="py-4 px-6 whitespace-nowrap text-right">
                    <div class="flex items-center justify-end gap-2">
                      <button
                        type="button"
                        @click="openDeleteModal(route('admin.tupa.procedures.destroy', proc.id), `${proc.code} - ${proc.name}`, 'Procedimiento TUPA')"
                        class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors"
                        title="Eliminar Procedimiento"
                      >
                        <i class="bi bi-trash3"></i>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr v-if="!procedures.data || procedures.data.length === 0">
                  <td colspan="6" class="py-12 px-6 text-center">
                    <div class="max-w-xs mx-auto text-center space-y-3">
                      <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                        <i class="bi bi-list-nested text-3xl"></i>
                      </div>
                      <p class="text-base font-bold text-gray-700">No se encontraron procedimientos</p>
                      <p class="text-xs text-gray-500">No hay trámites que coincidan con los filtros aplicados.</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="procedures.links && procedures.links.length > 3" class="p-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
            <div>
              Mostrando <span class="font-bold">{{ procedures.from ?? 0 }}</span> a <span class="font-bold">{{ procedures.to ?? 0 }}</span> de <span class="font-bold">{{ procedures.total ?? 0 }}</span> procedimientos
            </div>
            <div class="flex items-center gap-1">
              <template v-for="(link, i) in procedures.links" :key="i">
                <Link
                  v-if="link.url"
                  :href="link.url"
                  v-html="link.label"
                  class="px-3 py-1.5 rounded-lg border text-xs font-medium transition-colors"
                  :class="link.active ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100'"
                />
                <span
                  v-else
                  v-html="link.label"
                  class="px-3 py-1.5 rounded-lg border text-xs font-medium text-gray-400 border-gray-100 bg-gray-50 opacity-60"
                />
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- PDF Preview Modal -->
    <div
      v-if="pdfModal.open"
      class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300"
    >
      <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl h-[85vh] flex flex-col overflow-hidden"
        @click.stop
      >
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-slate-900 text-white">
          <div class="flex items-center gap-3">
            <i class="bi bi-file-earmark-pdf text-red-400 text-xl"></i>
            <h3 class="font-bold text-sm sm:text-base truncate max-w-md">{{ pdfModal.title }}</h3>
          </div>
          <button
            type="button"
            @click="pdfModal.open = false"
            class="text-gray-400 hover:text-white p-1 rounded-lg transition-colors"
          >
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>
        <div class="flex-1 bg-gray-100 relative">
          <iframe :src="pdfModal.url" class="w-full h-full border-none"></iframe>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="deleteModal.open"
      class="fixed inset-0 z-50 bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300"
    >
      <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 text-center space-y-4"
        @click.stop
      >
        <div class="w-16 h-16 mx-auto bg-red-100 text-red-600 rounded-full flex items-center justify-center">
          <i class="bi bi-exclamation-triangle-fill text-3xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-bold text-gray-900">¿Eliminar {{ deleteModal.type }}?</h3>
          <p class="text-xs text-gray-500 mt-1">
            Esta acción eliminará permanentemente <strong class="text-gray-700">{{ deleteModal.title }}</strong>.
          </p>
        </div>
        <div class="flex gap-3 pt-2">
          <button
            type="button"
            @click="deleteModal.open = false"
            class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors"
          >
            Cancelar
          </button>
          <button
            type="button"
            @click="confirmDelete"
            class="flex-1 py-2.5 bg-red-600 text-white font-semibold text-sm rounded-xl hover:bg-red-700 transition-colors"
          >
            Sí, Eliminar
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
  tupas: {
    type: Object,
    default: () => ({ data: [], links: [] })
  },
  categories: {
    type: Object,
    default: () => ({ data: [], links: [] })
  },
  procedures: {
    type: Object,
    default: () => ({ data: [], links: [] })
  },
  activeTab: {
    type: String,
    default: 'documents'
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  allCategoriesList: {
    type: Array,
    default: () => []
  },
  allTupasList: {
    type: Array,
    default: () => []
  }
});

const currentTab = ref(props.activeTab || 'documents');

const filterForm = ref({
  search: props.filters?.search || '',
  status: props.filters?.status || '',
  year: props.filters?.year || '',
  category_id: props.filters?.category_id || '',
  tupa_id: props.filters?.tupa_id || ''
});

const activeTupasCount = computed(() => {
  if (!props.tupas?.data) return 0;
  return props.tupas.data.filter(t => t.is_active).length;
});

function switchTab(tab) {
  currentTab.value = tab;
  router.get(route('admin.tupa.index'), { tab }, {
    preserveState: true,
    replace: true,
  });
}

function applyFilters() {
  const payload = {
    tab: currentTab.value,
    ...filterForm.value
  };
  // remove empty keys
  Object.keys(payload).forEach(key => {
    if (payload[key] === '' || payload[key] === null || payload[key] === undefined) {
      delete payload[key];
    }
  });

  router.get(route('admin.tupa.index'), payload, {
    preserveState: true,
    replace: true
  });
}

function clearFilters() {
  filterForm.value = {
    search: '',
    status: '',
    year: '',
    category_id: '',
    tupa_id: ''
  };
  router.get(route('admin.tupa.index'), { tab: currentTab.value }, {
    preserveState: true,
    replace: true
  });
}

function formatDate(dateStr) {
  if (!dateStr) return 'N/A';
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

// PDF Preview Modal
const pdfModal = ref({
  open: false,
  url: '',
  title: ''
});

function previewPdf(url, title) {
  pdfModal.value = {
    open: true,
    url,
    title: title || 'Documento TUPA'
  };
}

// Status toggles
function toggleStatus(tupa) {
  router.patch(route('admin.tupa.toggle-status', tupa.id), {}, {
    preserveScroll: true
  });
}

function toggleCategoryStatus(cat) {
  router.patch(route('admin.tupa.categories.toggle-status', cat.id), {}, {
    preserveScroll: true
  });
}

function toggleProcedureStatus(proc) {
  router.patch(route('admin.tupa.procedures.toggle-status', proc.id), {}, {
    preserveScroll: true
  });
}

// Delete Confirmation Modal
const deleteModal = ref({
  open: false,
  url: '',
  title: '',
  type: ''
});

function openDeleteModal(url, title, type) {
  deleteModal.value = {
    open: true,
    url,
    title,
    type: type || 'registro'
  };
}

function confirmDelete() {
  if (!deleteModal.value.url) return;
  router.delete(deleteModal.value.url, {
    preserveScroll: true,
    onSuccess: () => {
      deleteModal.value.open = false;
    }
  });
}
</script>
