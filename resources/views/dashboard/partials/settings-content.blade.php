<div class="max-w-2xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">Configuración</h2>
    
    <!-- Preferencias de notificaciones -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Notificaciones</h3>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-800">Notificaciones por email</p>
                    <p class="text-sm text-gray-500">Recibe alertas de nuevas ofertas</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                </label>
            </div>
            
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-800">Ofertas semanales</p>
                    <p class="text-sm text-gray-500">Recibe un resumen semanal de ofertas</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                </label>
            </div>
        </div>
    </div>
    
    <!-- Privacidad -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Privacidad</h3>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-800">Perfil público</p>
                    <p class="text-sm text-gray-500">Permite que empresas vean tu perfil</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                </label>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mostrar email en mi perfil</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option>Solo a empresas</option>
                    <option>A todos</option>
                    <option>Nadie</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Seguridad -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Seguridad</h3>
        
        <div class="space-y-4">
            <button class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                Cambiar contraseña
            </button>
            <button class="text-purple-600 hover:text-purple-700 text-sm font-medium block">
                Activar verificación en dos pasos
            </button>
        </div>
    </div>
    
    <!-- Eliminar cuenta -->
    <div class="bg-red-50 rounded-xl p-6 border border-red-200">
        <h3 class="text-lg font-bold text-red-800 mb-2">Zona peligrosa</h3>
        <p class="text-sm text-red-600 mb-4">Una vez que elimines tu cuenta, no podrás recuperar tus datos.</p>
        <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
            Eliminar mi cuenta
        </button>
    </div>
</div>