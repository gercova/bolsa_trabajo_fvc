<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Mis Postulaciones</h2>
        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option>Todas</option>
            <option>En revisión</option>
            <option>Entrevista</option>
            <option>Rechazadas</option>
            <option>Aceptadas</option>
        </select>
    </div>
    
    @php
        $applications = [
            ['title' => 'Desarrollador Full Stack', 'company' => 'Tech Solutions', 'date' => '15/03/2024', 'status' => 'En revisión', 'statusColor' => 'yellow'],
            ['title' => 'Analista de Datos', 'company' => 'DataCorp', 'date' => '10/03/2024', 'status' => 'Entrevista', 'statusColor' => 'blue'],
            ['title' => 'UX/UI Designer', 'company' => 'Creative Agency', 'date' => '05/03/2024', 'status' => 'Rechazada', 'statusColor' => 'red'],
        ];
    @endphp
    
    @foreach($applications as $app)
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-briefcase text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">{{ $app['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $app['company'] }}</p>
                    </div>
                </div>
                <p class="text-sm text-gray-500"><i class="bi bi-calendar"></i> Postulado el {{ $app['date'] }}</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $app['statusColor'] }}-100 text-{{ $app['statusColor'] }}-600">
                    {{ $app['status'] }}
                </span>
                <button class="text-purple-600 hover:text-purple-700">
                    <i class="bi bi-eye"></i> Ver detalles
                </button>
            </div>
        </div>
    </div>
    @endforeach
    
    <!-- Paginación -->
    <div class="flex justify-center mt-6">
        <div class="flex gap-2">
            <button class="px-3 py-1 border rounded-lg hover:bg-gray-50 transition">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="px-3 py-1 bg-purple-600 text-white rounded-lg">1</button>
            <button class="px-3 py-1 border rounded-lg hover:bg-gray-50 transition">2</button>
            <button class="px-3 py-1 border rounded-lg hover:bg-gray-50 transition">3</button>
            <button class="px-3 py-1 border rounded-lg hover:bg-gray-50 transition">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
</div>