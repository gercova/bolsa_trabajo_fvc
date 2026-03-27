<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Bolsa de Trabajo - Instituto Francisco Vigo Caballero')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Font Awesome (alternativa) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg fixed w-full top-0 z-50">
        <nav class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo y menú izquierda -->
                <div class="flex items-center space-x-8">
                    <a href="/" class="flex items-center space-x-2">
                        <i class="bi bi-mortarboard text-3xl text-purple-600"></i>
                        <span class="font-bold text-xl text-gray-800 hidden sm:block">Instituto Francisco Vigo Caballero</span>
                    </a>
                    
                    <!-- Menú desktop -->
                    <div class="hidden md:flex space-x-6">
                        <a href="/" class="text-gray-700 hover:text-purple-600 transition flex items-center space-x-1">
                            <i class="bi bi-house-door"></i>
                            <span>Inicio</span>
                        </a>
                        <a href="/ofertas" class="text-gray-700 hover:text-purple-600 transition flex items-center space-x-1">
                            <i class="bi bi-briefcase"></i>
                            <span>Buscar Ofertas</span>
                        </a>
                        <a href="/nosotros" class="text-gray-700 hover:text-purple-600 transition flex items-center space-x-1">
                            <i class="bi bi-info-circle"></i>
                            <span>Nosotros</span>
                        </a>
                    </div>
                </div>
                
                <!-- Menú derecha desktop -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/login" class="px-4 py-2 text-gray-700 hover:text-purple-600 transition flex items-center space-x-1">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Iniciar Sesión</span>
                    </a>
                    <a href="/register" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center space-x-1">
                        <i class="bi bi-person-plus"></i>
                        <span>Registrarse</span>
                    </a>
                </div>
                
                <!-- Botón menú móvil -->
                <button class="md:hidden text-gray-700 focus:outline-none" id="menuBtn">
                    <i class="bi bi-list text-3xl"></i>
                </button>
            </div>
            
            <!-- Menú móvil desplegable -->
            <div class="md:hidden hidden mt-4 pb-4" id="mobileMenu">
                <div class="flex flex-col space-y-3">
                    <a href="/" class="text-gray-700 hover:text-purple-600 transition flex items-center space-x-2 py-2">
                        <i class="bi bi-house-door w-6"></i>
                        <span>Inicio</span>
                    </a>
                    <a href="/ofertas" class="text-gray-700 hover:text-purple-600 transition flex items-center space-x-2 py-2">
                        <i class="bi bi-briefcase w-6"></i>
                        <span>Buscar Ofertas</span>
                    </a>
                    <a href="/nosotros" class="text-gray-700 hover:text-purple-600 transition flex items-center space-x-2 py-2">
                        <i class="bi bi-info-circle w-6"></i>
                        <span>Nosotros</span>
                    </a>
                    <hr class="border-gray-200">
                    <a href="/login" class="text-gray-700 hover:text-purple-600 transition flex items-center space-x-2 py-2">
                        <i class="bi bi-box-arrow-in-right w-6"></i>
                        <span>Iniciar Sesión</span>
                    </a>
                    <a href="/register" class="text-purple-600 hover:text-purple-700 transition flex items-center space-x-2 py-2">
                        <i class="bi bi-person-plus w-6"></i>
                        <span>Registrarse</span>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Contenido principal -->
    <main class="pt-20 min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-bold text-lg mb-4 flex items-center">
                        <i class="bi bi-mortarboard mr-2"></i>
                        Instituto Francisco Vigo Caballero
                    </h3>
                    <p class="text-gray-400">Formando profesionales para el futuro</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Enlaces rápidos</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/" class="hover:text-white transition">Inicio</a></li>
                        <li><a href="/ofertas" class="hover:text-white transition">Ofertas de empleo</a></li>
                        <li><a href="/nosotros" class="hover:text-white transition">Sobre nosotros</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Contacto</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center"><i class="bi bi-geo-alt mr-2"></i> Av. Principal 123</li>
                        <li class="flex items-center"><i class="bi bi-telephone mr-2"></i> +51 123 456 789</li>
                        <li class="flex items-center"><i class="bi bi-envelope mr-2"></i> info@fvigo.edu</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Síguenos</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition text-2xl"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition text-2xl"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition text-2xl"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition text-2xl"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-4 text-center text-gray-400">
                <p>&copy; 2024 Instituto Francisco Vigo Caballero. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Menú móvil toggle
        document.getElementById('menuBtn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
            
            // Cambiar ícono
            const icon = this.querySelector('i');
            if (mobileMenu.classList.contains('hidden')) {
                icon.classList.remove('bi-x');
                icon.classList.add('bi-list');
            } else {
                icon.classList.remove('bi-list');
                icon.classList.add('bi-x');
            }
        });

        // Cerrar menú móvil al hacer click en un enlace
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobileMenu').classList.add('hidden');
                const menuBtn = document.getElementById('menuBtn').querySelector('i');
                menuBtn.classList.remove('bi-x');
                menuBtn.classList.add('bi-list');
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>