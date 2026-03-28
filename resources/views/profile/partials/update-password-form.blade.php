<div>
    <h3 class="text-lg font-medium text-gray-900 mb-4">Actualizar Contraseña</h3>
    <p class="text-sm text-gray-600 mb-6">Asegúrate de usar una contraseña larga y segura.</p>
    
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')
        
        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña Actual</label>
            <input id="current_password" name="current_password" type="password" required
                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            @error('current_password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
            <input id="password" name="password" type="password" required
                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                Actualizar Contraseña
            </button>
            
            @if (session('status') === 'password-updated')
                <p class="text-sm text-green-600">Contraseña actualizada correctamente.</p>
            @endif
        </div>
    </form>
</div>