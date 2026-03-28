<div class="space-y-6">
    <!-- Bienvenida -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">¡Bienvenido de vuelta, {{ Auth::user()->names }}!</h2>
        <p class="opacity-90">Aquí encontrarás todas las herramientas para gestionar tu carrera profesional.</p>
    </div>
    
    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Postulaciones activas</p>
                    <p class="text-2xl font-bold text-gray-800">3</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-briefcase text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Ofertas guardadas</p>
                    <p class="text-2xl font-bold text-gray-800">5</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-bookmark text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Empresas que te vieron</p>
                    <p class="text-2xl font-bold text-gray-800">12</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-eye text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Completitud de perfil</p>
                    <p class="text-2xl font-bold text-gray-800">85%</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-graph-up text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Últimas ofertas -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Últimas ofertas para ti</h3>
            <a href="#" @click.prevent="currentTab = 'recommendations'" class="text-purple-600 hover:text-purple-700 text-sm">
                Ver todas <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        <div class="space-y-4">
            @php
                $recentJobs = [
                    ['title' => 'Desarrollador Full Stack', 'company' => 'Tech Solutions', 'location' => 'Lima, Perú', 'salary' => 'S/. 3500 - 4500', 'type' => 'Tiempo completo'],
                    ['title' => 'Analista de Datos', 'company' => 'DataCorp', 'location' => 'Arequipa, Perú', 'salary' => 'S/. 2800 - 3500', 'type' => 'Tiempo completo'],
                    ['title' => 'Diseñador UX/UI', 'company' => 'Creative Agency', 'location' => 'Remoto', 'salary' => 'S/. 2500 - 3200', 'type' => 'Medio tiempo'],
                ];
            @endphp
            
            @foreach($recentJobs as $job)
            <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-3 md:mb-0">
                        <h4 class="font-semibold text-gray-800">{{ $job['title'] }}</h4>
                        <p class="text-sm text-gray-600">{{ $job['company'] }}</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="text-xs text-gray-500"><i class="bi bi-geo-alt"></i> {{ $job['location'] }}</span>
                            <span class="text-xs text-gray-500"><i class="bi bi-cash"></i> {{ $job['salary'] }}</span>
                            <span class="text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded-full">{{ $job['type'] }}</span>
                        </div>
                    </div>
                    <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition text-sm">
                        Postularme
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Actividad reciente -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Actividad reciente</h3>
        <div class="space-y-3">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-eye text-purple-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-800">Viste la oferta <strong>Desarrollador Web</strong></p>
                    <p class="text-xs text-gray-500">Hace 2 horas</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-bookmark-check text-green-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-800">Guardaste la oferta <strong>Analista de Marketing</strong></p>
                    <p class="text-xs text-gray-500">Hace 1 día</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-send text-blue-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-800">Postulaste a <strong>UX/UI Designer</strong></p>
                    <p class="text-xs text-gray-500">Hace 3 días</p>
                </div>
            </div>
        </div>
    </div>
</div>