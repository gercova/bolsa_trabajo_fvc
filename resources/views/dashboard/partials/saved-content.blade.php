<div class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">Ofertas Guardadas</h2>
    
    @php
        $savedJobs = [
            ['title' => 'Desarrollador Backend', 'company' => 'TechCorp', 'location' => 'Lima', 'salary' => 'S/. 4000', 'type' => 'Tiempo completo', 'savedDate' => 'Hace 2 días'],
            ['title' => 'Data Scientist', 'company' => 'Analytics Inc', 'location' => 'Remoto', 'salary' => 'S/. 5000', 'type' => 'Tiempo completo', 'savedDate' => 'Hace 3 días'],
            ['title' => 'Product Manager', 'company' => 'Startup XYZ', 'location' => 'Arequipa', 'salary' => 'S/. 4500', 'type' => 'Tiempo completo', 'savedDate' => 'Hace 5 días'],
            ['title' => 'Frontend Developer', 'company' => 'Web Solutions', 'location' => 'Remoto', 'salary' => 'S/. 3000', 'type' => 'Medio tiempo', 'savedDate' => 'Hace 1 semana'],
            ['title' => 'Marketing Specialist', 'company' => 'Agencia Digital', 'location' => 'Lima', 'salary' => 'S/. 2500', 'type' => 'Tiempo completo', 'savedDate' => 'Hace 1 semana'],
        ];
    @endphp
    
    @foreach($savedJobs as $job)
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between">
            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-bold text-xl text-gray-800">{{ $job['title'] }}</h3>
                        <p class="text-gray-600 mt-1">{{ $job['company'] }}</p>
                    </div>
                    <button class="text-red-400 hover:text-red-600">
                        <i class="bi bi-bookmark-fill text-xl"></i>
                    </button>
                </div>
                
                <div class="flex flex-wrap gap-3 mt-3">
                    <span class="text-sm text-gray-500"><i class="bi bi-geo-alt"></i> {{ $job['location'] }}</span>
                    <span class="text-sm text-gray-500"><i class="bi bi-cash"></i> {{ $job['salary'] }}</span>
                    <span class="text-sm bg-purple-100 text-purple-600 px-2 py-1 rounded-full">{{ $job['type'] }}</span>
                </div>
                
                <p class="text-xs text-gray-400 mt-3"><i class="bi bi-clock"></i> Guardado {{ $job['savedDate'] }}</p>
            </div>
            
            <div class="flex space-x-2 mt-4 md:mt-0">
                <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition text-sm">
                    Postularme
                </button>
                <button class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50 transition text-sm">
                    Ver más
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>