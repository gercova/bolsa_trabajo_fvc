<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Recomendaciones para ti</h2>
        <div class="flex gap-2">
            <button class="px-3 py-1 border rounded-lg hover:bg-gray-50 transition">
                <i class="bi bi-sliders"></i> Filtrar
            </button>
        </div>
    </div>
    
    <div class="grid grid-cols-1 gap-4">
        @php
            $recommendations = [
                ['title' => 'Arquitecto de Software', 'company' => 'Innovatech', 'match' => 95, 'skills' => ['Python', 'AWS', 'Microservicios']],
                ['title' => 'DevOps Engineer', 'company' => 'Cloud Solutions', 'match' => 88, 'skills' => ['Docker', 'Kubernetes', 'CI/CD']],
                ['title' => 'Analista de Seguridad', 'company' => 'SecureCorp', 'match' => 82, 'skills' => ['Seguridad', 'Redes', 'Linux']],
                ['title' => 'Project Manager', 'company' => 'GlobalTech', 'match' => 78, 'skills' => ['Agile', 'Scrum', 'Liderazgo']],
            ];
        @endphp
        
        @foreach($recommendations as $rec)
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <h3 class="font-bold text-xl text-gray-800">{{ $rec['title'] }}</h3>
                        <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-xs font-semibold">
                            {{ $rec['match'] }}% match
                        </span>
                    </div>
                    <p class="text-gray-600">{{ $rec['company'] }}</p>
                    <div class="flex flex-wrap gap-2 mt-3">
                        @foreach($rec['skills'] as $skill)
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex space-x-2 mt-4 md:mt-0">
                    <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition text-sm">
                        Postularme
                    </button>
                    <button class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50 transition text-sm">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Barra de progreso de habilidades -->
    <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Habilidades recomendadas para mejorar</h3>
        
        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>JavaScript Avanzado</span>
                    <span class="text-gray-500">65%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: 65%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Inglés Técnico</span>
                    <span class="text-gray-500">40%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: 40%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Metodologías Ágiles</span>
                    <span class="text-gray-500">70%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: 70%"></div>
                </div>
            </div>
        </div>
        
        <button class="mt-4 text-purple-600 hover:text-purple-700 text-sm font-medium">
            Ver cursos recomendados <i class="bi bi-arrow-right"></i>
        </button>
    </div>
</div>