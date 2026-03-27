@extends('layouts.app')
@section('title', 'Inicio - Bolsa de Trabajo')
@section('content')
    <!-- Hero Section -->
    <section class="hero-gradient text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 animate-bounce">
                ¡Bienvenido a la Bolsa de Trabajo!
            </h1>
            <p class="text-xl mb-8">Encuentra las mejores oportunidades laborales para estudiantes y egresados</p>
            
            <!-- Buscador rápido -->
            <div class="max-w-2xl mx-auto">
                <div class="flex flex-col md:flex-row gap-4">
                    <input type="text" 
                           placeholder="Buscar por palabra clave..." 
                           class="flex-1 px-6 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300">
                    <button class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition flex items-center justify-center gap-2">
                        <i class="bi bi-search"></i>
                        Buscar
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Estadísticas -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 card-hover rounded-lg bg-gray-50">
                    <i class="bi bi-briefcase text-4xl text-purple-600 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">150+</h3>
                    <p class="text-gray-600">Ofertas activas</p>
                </div>
                <div class="text-center p-6 card-hover rounded-lg bg-gray-50">
                    <i class="bi bi-building text-4xl text-purple-600 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">50+</h3>
                    <p class="text-gray-600">Empresas asociadas</p>
                </div>
                <div class="text-center p-6 card-hover rounded-lg bg-gray-50">
                    <i class="bi bi-people text-4xl text-purple-600 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">300+</h3>
                    <p class="text-gray-600">Estudiantes colocados</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Últimas ofertas -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Últimas Ofertas de Empleo</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Oferta 1 -->
                <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-laptop text-2xl text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Desarrollador Web</h3>
                            <p class="text-gray-600 text-sm">Tech Solutions S.A.</p>
                        </div>
                    </div>
                    <div class="space-y-2 mb-4">
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="bi bi-geo-alt text-purple-600"></i>
                            Lima, Perú
                        </p>
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="bi bi-cash text-purple-600"></i>
                            S/. 2500 - 3500
                        </p>
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="bi bi-clock text-purple-600"></i>
                            Tiempo completo
                        </p>
                    </div>
                    <button class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition flex items-center justify-center gap-2">
                        Ver más
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

                <!-- Oferta 2 -->
                <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-graph-up text-2xl text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Analista de Datos</h3>
                            <p class="text-gray-600 text-sm">DataCorp Perú</p>
                        </div>
                    </div>
                    <div class="space-y-2 mb-4">
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="bi bi-geo-alt text-blue-600"></i>
                            Arequipa, Perú
                        </p>
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="bi bi-cash text-blue-600"></i>
                            S/. 2800 - 4000
                        </p>
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="bi bi-clock text-blue-600"></i>
                            Tiempo completo
                        </p>
                    </div>
                    <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        Ver más
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

                <!-- Oferta 3 -->
                <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-megaphone text-2xl text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Marketing Digital</h3>
                            <p class="text-gray-600 text-sm">Agencia Creativa</p>
                        </div>
                    </div>
                    <div class="space-y-2 mb-4">
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="bi bi-geo-alt text-green-600"></i>
                            Trujillo, Perú
                        </p>
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="bi bi-cash text-green-600"></i>
                            S/. 2000 - 3000
                        </p>
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="bi bi-clock text-green-600"></i>
                            Medio tiempo
                        </p>
                    </div>
                    <button class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                        Ver más
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <a href="/ofertas" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-700 font-semibold">
                    Ver todas las ofertas
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Empresas que confían -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Empresas que confían en nosotros</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <!-- Logos de empresas -->
                <div class="bg-white p-4 rounded-lg flex items-center justify-center card-hover">
                    <span class="text-gray-400 font-bold">EMPRESA 1</span>
                </div>
                <div class="bg-white p-4 rounded-lg flex items-center justify-center card-hover">
                    <span class="text-gray-400 font-bold">EMPRESA 2</span>
                </div>
                <div class="bg-white p-4 rounded-lg flex items-center justify-center card-hover">
                    <span class="text-gray-400 font-bold">EMPRESA 3</span>
                </div>
                <div class="bg-white p-4 rounded-lg flex items-center justify-center card-hover">
                    <span class="text-gray-400 font-bold">EMPRESA 4</span>
                </div>
                <div class="bg-white p-4 rounded-lg flex items-center justify-center card-hover">
                    <span class="text-gray-400 font-bold">EMPRESA 5</span>
                </div>
                <div class="bg-white p-4 rounded-lg flex items-center justify-center card-hover">
                    <span class="text-gray-400 font-bold">EMPRESA 6</span>
                </div>
            </div>
        </div>
    </section>
@endsection