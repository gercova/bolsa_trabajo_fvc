<div class="max-w-3xl mx-auto space-y-6">
    <!-- Foto de perfil -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex flex-col items-center">
            <div class="relative">
                <div class="w-32 h-32 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                    <i class="bi bi-person-circle text-6xl text-white"></i>
                </div>
                <button class="absolute bottom-0 right-0 bg-purple-600 text-white p-2 rounded-full hover:bg-purple-700 transition">
                    <i class="bi bi-camera text-sm"></i>
                </button>
            </div>
            <h3 class="mt-4 text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h3>
            <p class="text-gray-500">{{ Auth::user()->email }}</p>
            <span class="mt-2 px-3 py-1 bg-green-100 text-green-600 rounded-full text-sm">Verificado</span>
        </div>
    </div>
    
    <!-- Información personal -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Información personal</h3>
        
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                    <input type="email" name="email" value="{{ Auth::user()->email }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="tel" placeholder="+51 987 654 321" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de nacimiento</label>
                    <input type="date" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                    <input type="text" placeholder="Ciudad, País" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Biografía</label>
                    <textarea rows="4" placeholder="Cuéntanos sobre ti..." 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
    
    <!-- Habilidades -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Habilidades</h3>
        
        <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-sm flex items-center">
                JavaScript
                <button class="ml-2 text-purple-400 hover:text-purple-600"><i class="bi bi-x"></i></button>
            </span>
            <span class="px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-sm flex items-center">
                Laravel
                <button class="ml-2 text-purple-400 hover:text-purple-600"><i class="bi bi-x"></i></button>
            </span>
            <span class="px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-sm flex items-center">
                React
                <button class="ml-2 text-purple-400 hover:text-purple-600"><i class="bi bi-x"></i></button>
            </span>
        </div>
        
        <div class="flex">
            <input type="text" placeholder="Agregar habilidad..." 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <button class="bg-purple-600 text-white px-4 py-2 rounded-r-lg hover:bg-purple-700 transition">
                Agregar
            </button>
        </div>
    </div>
    
    <!-- Experiencia laboral -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Experiencia laboral</h3>
            <button class="text-purple-600 hover:text-purple-700">
                <i class="bi bi-plus-circle"></i> Agregar experiencia
            </button>
        </div>
        
        <div class="space-y-4">
            <div class="border border-gray-100 rounded-lg p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold text-gray-800">Desarrollador Web</h4>
                        <p class="text-sm text-gray-600">Tech Solutions · 2022 - Presente</p>
                        <p class="text-sm text-gray-500 mt-2">Desarrollo de aplicaciones web con Laravel y Vue.js</p>
                    </div>
                    <button class="text-gray-400 hover:text-red-500">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>