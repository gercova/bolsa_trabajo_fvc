<div>
    <p class="text-sm text-gray-600 mb-4">Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán borrados permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.</p>
    
    <button type="button" onclick="openDeleteModal()" 
            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
        Eliminar Cuenta
    </button>
    
    <!-- Modal de confirmación -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">¿Estás seguro?</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Una vez que tu cuenta sea eliminada, no podrás recuperarla.
                    </p>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}" class="mt-4">
                    @csrf
                    @method('delete')
                    <input type="password" name="password" placeholder="Ingresa tu contraseña" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-3 focus:outline-none focus:border-red-500">
                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteModal()"
                                class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush