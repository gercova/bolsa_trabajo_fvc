<template>
  <AdminLayout title="Gestión de Usuarios">
    <div class="space-y-6">
      <!-- Top Bar / Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            <span class="p-2 rounded-xl bg-purple-100 text-purple-700 inline-flex">
              <i class="bi bi-people-fill text-xl"></i>
            </span>
            Gestión de Usuarios
          </h1>
          <p class="text-sm text-gray-500 mt-1">
            Administra las cuentas de docentes, estudiantes y personal administrativo de la institución.
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
          <!-- Bulk Import Button -->
          <button
            type="button"
            @click="importModalOpen = true"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-sm transition-all transform active:scale-95"
          >
            <i class="bi bi-file-earmark-spreadsheet-fill text-base"></i>
            <span>Importación Masiva</span>
          </button>

          <!-- Bulk Delete Trigger (if any selected) -->
          <button
            v-if="selectedIds.length > 0"
            type="button"
            @click="openBulkDeleteModal"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl shadow-sm transition-all animate-fade-in"
          >
            <i class="bi bi-trash-fill text-base"></i>
            <span>Eliminar ({{ selectedIds.length }})</span>
          </button>

          <!-- Create User Button -->
          <Link
            :href="route('admin.users.create')"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/25 hover:shadow-purple-600/35 transition-all transform active:scale-95"
          >
            <i class="bi bi-person-plus-fill text-base"></i>
            <span>Nuevo Usuario</span>
          </Link>
        </div>
      </div>

      <!-- Quick Metrics Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Usuarios</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ users.total ?? 0 }}</h3>
            <span class="text-xs text-purple-600 font-medium">Registrados en la plataforma</span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl">
            <i class="bi bi-people"></i>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Usuarios Activos</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ activeCount }}</h3>
            <span class="text-xs text-emerald-700 font-medium">En esta página</span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
            <i class="bi bi-check-circle-fill"></i>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Usuarios Inactivos</p>
            <h3 class="text-2xl font-black text-amber-600 mt-1">{{ inactiveCount }}</h3>
            <span class="text-xs text-amber-700 font-medium">Acceso suspendido</span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl">
            <i class="bi bi-dash-circle-fill"></i>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Roles Disponibles</p>
            <h3 class="text-2xl font-black text-blue-600 mt-1">{{ roles?.length ?? 0 }}</h3>
            <span class="text-xs text-blue-700 font-medium">Roles configurados</span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
            <i class="bi bi-shield-lock-fill"></i>
          </div>
        </div>
      </div>

      <!-- Filters Bar -->
      <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm space-y-3">
        <form @submit.prevent="applyFilters" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
          <!-- Search -->
          <div class="lg:col-span-2 relative">
            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
              type="text"
              v-model="filtersForm.search"
              placeholder="Buscar por nombre, email, DNI o cargo..."
              class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
            />
          </div>

          <!-- Status -->
          <div>
            <select
              v-model="filtersForm.status"
              @change="applyFilters"
              class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-700 font-medium"
            >
              <option value="">Todos los Estados</option>
              <option value="active">Activos</option>
              <option value="inactive">Inactivos</option>
            </select>
          </div>

          <!-- Role -->
          <div>
            <select
              v-model="filtersForm.role"
              @change="applyFilters"
              class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-700 font-medium"
            >
              <option value="">Todos los Roles</option>
              <option v-for="r in roles" :key="r.id" :value="r.name">
                {{ r.name }}
              </option>
            </select>
          </div>

          <!-- Job Position -->
          <div>
            <select
              name="job_position"
              v-model="filtersForm.job_position"
              @change="applyFilters"
              class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-700 font-medium"
            >
              <option value="">Todos los puestos</option>
              <option v-for="jp in jobPositions" :key="jp" :value="jp">
                {{ jp }}
              </option>
            </select>
          </div>

          <!-- Filter Actions -->
          <div class="flex items-center gap-2">
            <button
              type="submit"
              class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium text-sm rounded-xl transition-colors flex items-center justify-center gap-1.5 shadow-sm"
            >
              <i class="bi bi-funnel-fill text-xs"></i>
              Filtrar
            </button>
            <button
              v-if="hasActiveFilters"
              type="button"
              @click="clearFilters"
              class="px-3 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all"
              title="Limpiar filtros"
            >
              <i class="bi bi-x-circle text-gray-500"></i>
            </button>
          </div>
        </form>
      </div>

      <!-- Users Table Card -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50/80 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <th class="px-5 py-4 w-10">
                  <input
                    type="checkbox"
                    :checked="isAllSelected"
                    @change="toggleSelectAll"
                    class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-4 h-4"
                  />
                </th>
                <th class="px-5 py-4">Usuario</th>
                <th class="px-5 py-4">DNI</th>
                <th class="px-5 py-4">Rol / Puesto</th>
                <th class="px-5 py-4">Estado</th>
                <th class="px-5 py-4">F. Registro</th>
                <th class="px-5 py-4 text-right">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
              <tr
                v-for="user in users.data"
                :key="user.id"
                :class="selectedIds.includes(user.id) ? 'bg-purple-50/50' : 'hover:bg-gray-50/60'"
                class="transition-colors"
              >
                <!-- Checkbox -->
                <td class="px-5 py-4">
                  <input
                    type="checkbox"
                    :value="user.id"
                    v-model="selectedIds"
                    class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-4 h-4"
                  />
                </td>

                <!-- User Info -->
                <td class="px-5 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm overflow-hidden flex-shrink-0 border border-purple-200">
                      <img
                        v-if="user.photo_profile"
                        :src="getStorageUrl(user.photo_profile)"
                        :alt="user.names"
                        class="w-full h-full object-cover"
                      />
                      <span v-else>{{ getInitials(user.names) }}</span>
                    </div>
                    <div class="min-w-0">
                      <p class="font-bold text-gray-900 truncate flex items-center gap-1.5">
                        {{ user.names }}
                        <span v-if="user.id === currentUserId" class="px-1.5 py-0.5 text-[10px] bg-purple-100 text-purple-700 font-semibold rounded">
                          Tú
                        </span>
                      </p>
                      <p class="text-xs text-gray-500 truncate">{{ user.email }}</p>
                    </div>
                  </div>
                </td>

                <!-- DNI -->
                <td class="px-5 py-4 font-mono text-gray-600 text-xs">
                  {{ user.dni || 'Sin DNI' }}
                </td>

                <!-- Role / Position -->
                <td class="px-5 py-4">
                  <div class="space-y-1">
                    <span
                      :class="getRoleBadgeClass(user.role)"
                      class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
                    >
                      <i class="bi bi-shield-check mr-1 text-[10px]"></i>
                      {{ user.role || 'Sin Rol' }}
                    </span>
                    <p v-if="user.job_position" class="text-xs text-gray-500 truncate max-w-xs">
                      {{ user.job_position }}
                    </p>
                  </div>
                </td>

                <!-- Status Switch -->
                <td class="px-5 py-4">
                  <button
                    type="button"
                    @click="toggleStatus(user)"
                    :class="user.is_active ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-600'"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                    :title="user.is_active ? 'Desactivar usuario' : 'Activar usuario'"
                  >
                    <span
                      :class="user.is_active ? 'translate-x-6 bg-white' : 'translate-x-1 bg-white'"
                      class="inline-block h-4 w-4 transform rounded-full transition-transform"
                    />
                  </button>
                  <span class="ml-2 text-xs font-medium" :class="user.is_active ? 'text-emerald-700' : 'text-gray-500'">
                    {{ user.is_active ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>

                <!-- Date -->
                <td class="px-5 py-4 text-xs text-gray-500">
                  {{ formatDate(user.created_at) }}
                </td>

                <!-- Actions -->
                <td class="px-5 py-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <!-- Update Password -->
                    <button
                      type="button"
                      @click="openPasswordModal(user)"
                      class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                      title="Cambiar Contraseña"
                    >
                      <i class="bi bi-key-fill text-base"></i>
                    </button>

                    <!-- Edit User -->
                    <Link
                      :href="route('admin.users.edit', user.id)"
                      class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                      title="Editar Usuario"
                    >
                      <i class="bi bi-pencil-square text-base"></i>
                    </Link>

                    <!-- Delete User -->
                    <button
                      type="button"
                      @click="openDeleteModal(user)"
                      class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                      title="Eliminar Usuario"
                    >
                      <i class="bi bi-trash-fill text-base"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="!users.data || users.data.length === 0">
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                  <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center text-3xl text-gray-400 mb-3">
                    <i class="bi bi-people"></i>
                  </div>
                  <h4 class="font-bold text-gray-700">No se encontraron usuarios</h4>
                  <p class="text-xs text-gray-400 mt-1">Intenta ajustando los filtros de búsqueda.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="users.links && users.links.length > 3" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
          <p class="text-xs text-gray-500">
            Mostrando del <span class="font-medium text-gray-700">{{ users.from ?? 0 }}</span> al
            <span class="font-medium text-gray-700">{{ users.to ?? 0 }}</span> de
            <span class="font-medium text-gray-700">{{ users.total ?? 0 }}</span> usuarios
          </p>

          <div class="flex flex-wrap gap-1">
            <template v-for="(link, i) in users.links" :key="i">
              <Link
                v-if="link.url"
                :href="link.url"
                v-html="link.label"
                :class="link.active ? 'bg-purple-600 text-white font-bold' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                class="px-3 py-1.5 text-xs rounded-lg transition-colors"
              />
              <span
                v-else
                v-html="link.label"
                class="px-3 py-1.5 text-xs rounded-lg text-gray-400 bg-gray-100"
              />
            </template>
          </div>
        </div>
      </div>
    </div>

    <!-- ─── MODAL 1: IMPORTACIÓN MASIVA ─────────────────────────────────── -->
    <div
      v-if="importModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
    >
      <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-5 animate-scale-up border border-gray-100">
        <!-- Header -->
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg">
              <i class="bi bi-file-earmark-spreadsheet-fill"></i>
            </div>
            <div>
              <h3 class="text-base font-bold text-gray-900">Importación Masiva de Usuarios</h3>
              <p class="text-xs text-gray-500">Carga por lotes desde Excel o archivo CSV</p>
            </div>
          </div>
          <button
            type="button"
            @click="importModalOpen = false"
            class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <i class="bi bi-x-lg"></i>
          </button>
        </div>

        <!-- Templates Download -->
        <div class="p-3.5 bg-emerald-50 rounded-xl border border-emerald-200 text-xs text-emerald-900 flex flex-col gap-2">
          <p class="font-semibold flex items-center gap-1.5">
            <i class="bi bi-download text-emerald-600"></i>
            Descarga las plantillas modelo antes de subir tu archivo:
          </p>
          <div class="flex items-center gap-2 pt-1">
            <a
              :href="route('admin.users.template', { format: 'xlsx' })"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium text-xs shadow-sm transition-colors"
            >
              <i class="bi bi-file-earmark-excel"></i>
              Plantilla Excel (.xlsx)
            </a>
            <a
              :href="route('admin.users.template', { format: 'csv' })"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-lg font-medium text-xs transition-colors"
            >
              <i class="bi bi-filetype-csv"></i>
              Plantilla CSV (.csv)
            </a>
          </div>
        </div>

        <!-- File Upload Form -->
        <form @submit.prevent="submitImport" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
              Seleccionar archivo (.xlsx, .xls, .csv)
            </label>
            <input
              type="file"
              ref="importFileInput"
              accept=".xlsx,.xls,.csv"
              @change="handleImportFileChange"
              class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-colors border border-gray-200 rounded-xl p-1"
            />
            <p class="text-xs text-gray-500 mt-1.5">Tamaño máximo recomendado: 10MB.</p>
          </div>

          <div v-if="importForm.errors.file" class="text-xs text-red-600 font-medium">
            {{ importForm.errors.file }}
          </div>

          <!-- Actions -->
          <div class="flex gap-3 pt-3 border-t border-gray-100">
            <button
              type="button"
              @click="importModalOpen = false"
              class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="importForm.processing || !importForm.file"
              class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-semibold text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2"
            >
              <i v-if="importForm.processing" class="bi bi-arrow-repeat animate-spin"></i>
              <span>{{ importForm.processing ? 'Importando...' : 'Iniciar Importación' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ─── MODAL 2: ACTUALIZAR CONTRASEÑA ─────────────────────────────── -->
    <div
      v-if="passwordModal.open"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
    >
      <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 animate-scale-up border border-gray-100">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-lg">
              <i class="bi bi-key-fill"></i>
            </div>
            <div>
              <h3 class="text-base font-bold text-gray-900">Cambiar Contraseña</h3>
              <p class="text-xs text-gray-500 truncate max-w-xs">{{ passwordModal.userName }}</p>
            </div>
          </div>
          <button
            type="button"
            @click="passwordModal.open = false"
            class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <i class="bi bi-x-lg"></i>
          </button>
        </div>

        <form @submit.prevent="submitPasswordUpdate" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
              Nueva Contraseña
            </label>
            <div class="relative">
              <input
                :type="showPasswordText ? 'text' : 'password'"
                v-model="passwordForm.password"
                placeholder="Mínimo 8 caracteres"
                class="w-full pr-10 pl-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                required
              />
              <button
                type="button"
                @click="showPasswordText = !showPasswordText"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
              >
                <i :class="showPasswordText ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'"></i>
              </button>
            </div>
            <p v-if="passwordForm.errors.password" class="text-xs text-red-600 mt-1">
              {{ passwordForm.errors.password }}
            </p>
          </div>

          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
              Confirmar Contraseña
            </label>
            <input
              :type="showPasswordText ? 'text' : 'password'"
              v-model="passwordForm.password_confirmation"
              placeholder="Repite la contraseña"
              class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
              required
            />
            <p v-if="passwordForm.errors.password_confirmation" class="text-xs text-red-600 mt-1">
              {{ passwordForm.errors.password_confirmation }}
            </p>
          </div>

          <div class="flex gap-3 pt-3 border-t border-gray-100">
            <button
              type="button"
              @click="passwordModal.open = false"
              class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="passwordForm.processing"
              class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-semibold text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2"
            >
              <i v-if="passwordForm.processing" class="bi bi-arrow-repeat animate-spin"></i>
              <span>{{ passwordForm.processing ? 'Actualizando...' : 'Guardar Clave' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ─── MODAL 3: ELIMINAR USUARIO INDIVIDUAL ────────────────────────── -->
    <div
      v-if="deleteModal.open"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
    >
      <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl space-y-4 animate-scale-up border border-gray-100 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-red-100 text-red-600 flex items-center justify-center text-2xl">
          <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div>
          <h3 class="text-lg font-bold text-gray-900">¿Eliminar este usuario?</h3>
          <p class="text-xs text-gray-500 mt-1">
            Se eliminará permanentemente la cuenta de <strong class="text-gray-800">{{ deleteModal.userName }}</strong>.
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
            @click="confirmSingleDelete"
            class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl transition-colors shadow-md"
          >
            Eliminar
          </button>
        </div>
      </div>
    </div>

    <!-- ─── MODAL 4: ELIMINACIÓN MASIVA ─────────────────────────────────── -->
    <div
      v-if="bulkDeleteModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
    >
      <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 animate-scale-up border border-gray-100 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-red-100 text-red-600 flex items-center justify-center text-2xl">
          <i class="bi bi-trash3-fill"></i>
        </div>
        <div>
          <h3 class="text-lg font-bold text-gray-900">Eliminación Masiva de Usuarios</h3>
          <p class="text-xs text-gray-500 mt-1">
            ¿Estás seguro de eliminar los <strong>{{ selectedIds.length }}</strong> usuarios seleccionados? Esta acción no se puede deshacer.
          </p>
        </div>
        <div class="flex gap-3 pt-2">
          <button
            type="button"
            @click="bulkDeleteModalOpen = false"
            class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors"
          >
            Cancelar
          </button>
          <button
            type="button"
            @click="confirmBulkDelete"
            class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl transition-colors shadow-md"
          >
            Sí, Eliminar Selección
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
  users: {
    type: Object,
    default: () => ({ data: [], links: [] })
  },
  roles: {
    type: Array,
    default: () => []
  },
  jobPositions: {
    type: Array,
    default: () => []
  },
  search: {
    type: String,
    default: ''
  },
  status: {
    type: String,
    default: ''
  },
  role: {
    type: String,
    default: ''
  },
  jobPosition: {
    type: String,
    default: ''
  },
  perPage: {
    type: [Number, String],
    default: 10
  },
  sortBy: {
    type: String,
    default: 'created_at'
  },
  sortOrder: {
    type: String,
    default: 'desc'
  }
});

const page = usePage();
const currentUserId = computed(() => page.props.auth?.user?.id ?? null);

// Filters State
const filtersForm = ref({
  search: props.search || '',
  status: props.status || '',
  role: props.role || '',
  job_position: props.jobPosition || '',
  per_page: props.perPage || 10,
  sort_by: props.sortBy || 'created_at',
  sort_order: props.sortOrder || 'desc'
});

const hasActiveFilters = computed(() => {
  return Boolean(
    filtersForm.value.search ||
    filtersForm.value.status ||
    filtersForm.value.role ||
    filtersForm.value.job_position
  );
});

function applyFilters() {
  const payload = { ...filtersForm.value };
  Object.keys(payload).forEach(k => {
    if (payload[k] === '' || payload[k] === null || payload[k] === undefined) {
      delete payload[k];
    }
  });

  router.get(route('admin.users.index'), payload, {
    preserveState: true,
    replace: true
  });
}

function clearFilters() {
  filtersForm.value = {
    search: '',
    status: '',
    role: '',
    job_position: '',
    per_page: 10,
    sort_by: 'created_at',
    sort_order: 'desc'
  };
  router.get(route('admin.users.index'), {}, {
    preserveState: true,
    replace: true
  });
}

// Metrics
const activeCount = computed(() => {
  if (!props.users?.data) return 0;
  return props.users.data.filter(u => u.is_active).length;
});

const inactiveCount = computed(() => {
  if (!props.users?.data) return 0;
  return props.users.data.filter(u => !u.is_active).length;
});

// Row Selection
const selectedIds = ref([]);
const isAllSelected = computed(() => {
  if (!props.users?.data || props.users.data.length === 0) return false;
  return props.users.data.every(u => selectedIds.value.includes(u.id));
});

function toggleSelectAll(e) {
  if (e.target.checked) {
    selectedIds.value = props.users.data.map(u => u.id);
  } else {
    selectedIds.value = [];
  }
}

// Helpers
function getStorageUrl(path) {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  return `/storage/${path}`;
}

function getInitials(name) {
  if (!name) return 'U';
  return name
    .split(' ')
    .slice(0, 2)
    .map(w => w[0])
    .join('')
    .toUpperCase();
}

function formatDate(dateStr) {
  if (!dateStr) return 'N/A';
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function getRoleBadgeClass(roleName) {
  const r = (roleName || '').toLowerCase();
  if (r.includes('admin')) return 'bg-purple-100 text-purple-700';
  if (r.includes('docente') || r.includes('profesor')) return 'bg-blue-100 text-blue-700';
  if (r.includes('estudiante') || r.includes('alumno')) return 'bg-emerald-100 text-emerald-700';
  return 'bg-gray-100 text-gray-700';
}

// Toggle User Status
function toggleStatus(user) {
  router.patch(route('admin.users.toggle-status', user.id), {}, {
    preserveScroll: true
  });
}

// ─── Modal 1: Import ──────────────────────────────────────────
const importModalOpen = ref(false);
const importFileInput = ref(null);
const importForm = useForm({
  file: null
});

function handleImportFileChange(e) {
  importForm.file = e.target.files[0] || null;
}

function submitImport() {
  if (!importForm.file) return;
  importForm.post(route('admin.users.import'), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      importModalOpen.value = false;
      importForm.reset();
      if (importFileInput.value) importFileInput.value.value = '';
    }
  });
}

// ─── Modal 2: Password Update ─────────────────────────────────
const passwordModal = ref({
  open: false,
  userId: null,
  userName: ''
});
const showPasswordText = ref(false);
const passwordForm = useForm({
  password: '',
  password_confirmation: ''
});

function openPasswordModal(user) {
  passwordModal.value = {
    open: true,
    userId: user.id,
    userName: user.names
  };
  passwordForm.reset();
  passwordForm.clearErrors();
}

function submitPasswordUpdate() {
  if (!passwordModal.value.userId) return;
  passwordForm.post(route('admin.users.password.update', passwordModal.value.userId), {
    preserveScroll: true,
    onSuccess: () => {
      passwordModal.value.open = false;
      passwordForm.reset();
    }
  });
}

// ─── Modal 3: Single Delete ───────────────────────────────────
const deleteModal = ref({
  open: false,
  userId: null,
  userName: ''
});

function openDeleteModal(user) {
  deleteModal.value = {
    open: true,
    userId: user.id,
    userName: user.names
  };
}

function confirmSingleDelete() {
  if (!deleteModal.value.userId) return;
  router.delete(route('admin.users.destroy', deleteModal.value.userId), {
    preserveScroll: true,
    onSuccess: () => {
      deleteModal.value.open = false;
    }
  });
}

// ─── Modal 4: Bulk Delete ─────────────────────────────────────
const bulkDeleteModalOpen = ref(false);

function openBulkDeleteModal() {
  if (selectedIds.value.length === 0) return;
  bulkDeleteModalOpen.value = true;
}

function confirmBulkDelete() {
  router.delete(route('admin.users.bulk-delete'), {
    data: { ids: selectedIds.value },
    preserveScroll: true,
    onSuccess: () => {
      bulkDeleteModalOpen.value = false;
      selectedIds.value = [];
    }
  });
}
</script>
