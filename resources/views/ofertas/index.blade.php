@extends('layouts.app')

@section('title', 'Buscar Ofertas - Bolsa de Trabajo')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                <i class="bi bi-funnel"></i>
                Filtros de búsqueda
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" 
                       placeholder="Palabra clave" 
                       class="px-4 py-2 border rounded-lg focus:outline-none focus:border-purple-600">
                
                <select class="px-4 py-2 border rounded-lg focus:outline-none focus:border-purple-600">
                    <option value="">Categoría</option>
                    <option value="tecnologia">Tecnología</option>
                    <option value="marketing">Marketing</option>
                    <option value="ventas">Ventas</option>
                    <option value="administracion">Administración</option>
                </select>
                
                <select class="px-4 py-2 border rounded-lg focus:outline-none focus:border-purple-600">
                    <option value="">Ubicación</option>
                    <option value="lima">Lima</option>
                    <option value="arequipa">Arequipa</option>
                    <option value="trujillo">Trujillo</option>
                    <option value="cusco">Cusco</option>
                </select>
                
                <button class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition flex items-center justify-center gap-2">
                    <i class="bi bi-search"></i>
                    Buscar
                </button>
            </div>
        </div>

        <!-- Resultados -->
        <h2 class="text-2xl font-bold mb-6">Ofertas disponibles (24)</h2>
        
        <div class="space-y-4">
            <!-- Oferta 1 -->
            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex items-start gap-4 mb-4 md:mb-0">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-laptop text-3xl text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl">Desarrollador Web Full Stack</h3>
                            <p class="text-gray-600">Tech Solutions S.A. · Lima, Perú</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm">Tiempo completo</span>
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm">S/. 3000 - 4000</span>
                                <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm">Urgente</span>
                            </div>
                        </div>
                    </div>
                    <button class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition self-start md:self-center">
                        Ver detalles
                    </button>
                </div>
            </div>

            <!-- Oferta 2 -->
            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex items-start gap-4 mb-4 md:mb-0">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-graph-up text-3xl text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl">Analista de Marketing Digital</h3>
                            <p class="text-gray-600">Agencia Creativa · Arequipa, Perú</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm">Medio tiempo</span>
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm">S/. 1500 - 2000</span>
                                <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-sm">Remoto</span>
                            </div>
                        </div>
                    </div>
                    <button class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition self-start md:self-center">
                        Ver detalles
                    </button>
                </div>
            </div>
        </div>

        <!-- Paginación -->
        <div class="mt-8 flex justify-center">
            <div class="flex gap-2">
                <button class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">1</button>
                <button class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">2</button>
                <button class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">3</button>
                <button class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
@endsection