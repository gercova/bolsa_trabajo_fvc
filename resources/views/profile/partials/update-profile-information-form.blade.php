<div>
    <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Perfil</h3>
    <p class="text-sm text-gray-600 mb-6">Actualiza la información de tu perfil y dirección de correo electrónico.</p>
    
    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')
        
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                Guardar Cambios
            </button>
            
            @if (session('status') === 'profile-updated')
                <p class="text-sm text-green-600">Perfil actualizado correctamente.</p>
            @endif
        </div>
    </form>
</div>