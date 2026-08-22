@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Mi Perfil</h1>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button onclick="showTab('profile')" id="tab-profile-btn" 
                            class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition
                                   border-purple-500 text-purple-600">
                        <i class="bi bi-person mr-2"></i>
                        Información del Perfil
                    </button>
                    <button onclick="showTab('password')" id="tab-password-btn"
                            class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition
                                   border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="bi bi-key mr-2"></i>
                        Actualizar Contraseña
                    </button>
                </nav>
            </div>
            
            <!-- Información del Perfil -->
            <div id="profile-tab" class="p-6">
                @include('profile.partials.update-profile-information-form')
            </div>
            
            <!-- Actualizar Contraseña -->
            <div id="password-tab" class="p-6 hidden">
                @include('profile.partials.update-password-form')
            </div>
        </div>
        
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 text-red-600">Eliminar Cuenta</h2>
                <p class="text-gray-600 mb-4">Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán borrados permanentemente.</p>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showTab(tab) {
        // Ocultar todos los tabs
        document.getElementById('profile-tab').classList.add('hidden');
        document.getElementById('password-tab').classList.add('hidden');
        
        // Remover estilos activos de los botones
        document.getElementById('tab-profile-btn').classList.remove('border-purple-500', 'text-purple-600');
        document.getElementById('tab-profile-btn').classList.add('border-transparent', 'text-gray-500');
        document.getElementById('tab-password-btn').classList.remove('border-purple-500', 'text-purple-600');
        document.getElementById('tab-password-btn').classList.add('border-transparent', 'text-gray-500');
        
        // Mostrar el tab seleccionado
        if (tab === 'profile') {
            document.getElementById('profile-tab').classList.remove('hidden');
            document.getElementById('tab-profile-btn').classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('tab-profile-btn').classList.add('border-purple-500', 'text-purple-600');
        } else {
            document.getElementById('password-tab').classList.remove('hidden');
            document.getElementById('tab-password-btn').classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('tab-password-btn').classList.add('border-purple-500', 'text-purple-600');
        }
    }
</script>
@endpush
@endsection