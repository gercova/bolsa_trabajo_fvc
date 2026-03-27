@extends('layouts.app')

@section('title', 'Nosotros - Instituto Francisco Vigo Caballero')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Título -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4">Sobre Nosotros</h1>
            <div class="w-24 h-1 bg-purple-600 mx-auto"></div>
        </div>

        <!-- Contenido -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
            <div>
                <h2 class="text-3xl font-bold mb-6">Instituto Francisco Vigo Caballero</h2>
                <p class="text-gray-600 mb-4">
                    Fundado en 1985, el Instituto Francisco Vigo Caballero se ha consolidado como una 
                    institución líder en formación técnica profesional, comprometida con la excelencia 
                    académica y la inserción laboral de sus estudiantes.
                </p>
                <p class="text-gray-600 mb-4">
                    Nuestra bolsa de trabajo nace con el objetivo de conectar a nuestros estudiantes y 
                    egresados con las mejores oportunidades laborales del mercado, estableciendo alianzas 
                    estratégicas con empresas de diversos sectores.
                </p>
                <div class="flex gap-4 mt-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">35+</div>
                        <div class="text-gray-600">Años de experiencia</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">5000+</div>
                        <div class="text-gray-600">Egresados</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">200+</div>
                        <div class="text-gray-600">Empresas aliadas</div>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-purple-600 to-blue-500 rounded-lg p-8 text-white">
                <i class="bi bi-quote text-6xl opacity-50"></i>
                <p class="text-xl italic mb-4">
                    "Formamos profesionales competentes y éticos, listos para enfrentar los desafíos 
                    del mundo laboral actual."
                </p>
                <p class="font-bold">- Dr. Juan Pérez</p>
                <p class="text-sm opacity-75">Director General</p>
            </div>
        </div>

        <!-- Valores -->
        <section class="mb-16">
            <h2 class="text-3xl font-bold text-center mb-12">Nuestros Valores</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center p-6 card-hover">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-star text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="font-bold mb-2">Excelencia</h3>
                    <p class="text-gray-600">Buscamos la mejora continua en todos nuestros procesos</p>
                </div>
                
                <div class="text-center p-6 card-hover">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-heart text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="font-bold mb-2">Compromiso</h3>
                    <p class="text-gray-600">Dedicación total con nuestros estudiantes y egresados</p>
                </div>
                
                <div class="text-center p-6 card-hover">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-hand-thumbs-up text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="font-bold mb-2">Integridad</h3>
                    <p class="text-gray-600">Actuamos con ética y transparencia</p>
                </div>
                
                <div class="text-center p-6 card-hover">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-people text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="font-bold mb-2">Innovación</h3>
                    <p class="text-gray-600">Nos adaptamos a las nuevas tendencias educativas</p>
                </div>
            </div>
        </section>

        <!-- Equipo -->
        <section>
            <h2 class="text-3xl font-bold text-center mb-12">Nuestro Equipo</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-white rounded-lg shadow-md card-hover">
                    <img src="https://ui-avatars.com/api/?name=Juan+Perez&size=128&background=random" 
                         alt="Juan Pérez" 
                         class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="font-bold text-xl">Juan Pérez</h3>
                    <p class="text-purple-600 mb-2">Director General</p>
                    <p class="text-gray-600">Más de 20 años de experiencia en educación</p>
                </div>
                
                <div class="text-center p-6 bg-white rounded-lg shadow-md card-hover">
                    <img src="https://ui-avatars.com/api/?name=Maria+Garcia&size=128&background=random" 
                         alt="María García" 
                         class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="font-bold text-xl">María García</h3>
                    <p class="text-purple-600 mb-2">Coordinadora de Bolsa de Trabajo</p>
                    <p class="text-gray-600">Especialista en reclutamiento y selección</p>
                </div>
                
                <div class="text-center p-6 bg-white rounded-lg shadow-md card-hover">
                    <img src="https://ui-avatars.com/api/?name=Carlos+Lopez&size=128&background=random" 
                         alt="Carlos López" 
                         class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="font-bold text-xl">Carlos López</h3>
                    <p class="text-purple-600 mb-2">Asesor de Carreras</p>
                    <p class="text-gray-600">Orientación profesional y desarrollo laboral</p>
                </div>
            </div>
        </section>
    </div>
@endsection