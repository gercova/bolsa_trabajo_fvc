<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — IESTP Francisco Vigo Caballero</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        ink: {
                            DEFAULT: '#0F172A',
                            soft: '#1E293B',
                            muted: '#475569',
                        },
                        gold: {
                            DEFAULT: '#B45309',
                            light: '#FEF3C7',
                            vivid: '#D97706',
                        },
                        sand: {
                            DEFAULT: '#F7F4EF',
                            dark: '#EDE8E0',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0F172A;
        }

        .solid-card {
            background-color: #1E293B;
            border: 1px solid #334155;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.5);
        }

        .fade-in-up {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body
    class="h-full text-slate-100 flex items-center justify-center p-4 relative antialiased selection:bg-blue-600 selection:text-white">

    <!-- Main Card -->
    <div
        class="w-full max-w-xl solid-card rounded-3xl p-8 md:p-12 text-center relative z-10 fade-in-up overflow-hidden">
        <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

        <!-- Institution Logo -->
        <div class="flex items-center justify-center gap-3 mb-8">
            <div
                class="w-11 h-11 bg-slate-900 border border-slate-700 rounded-xl flex items-center justify-center shadow-md">
                <i class="bi bi-mortarboard-fill text-amber-500 text-2xl"></i>
            </div>
            <div class="text-left leading-none">
                <span class="text-xs uppercase tracking-widest text-slate-400 font-bold">Portal web</span>
                <h2 class="text-sm font-sans font-bold text-slate-200 mt-0.5">IESTP <span
                        class="text-amber-500">Francisco Vigo Caballero</span></h2>
            </div>
        </div>

        <!-- Error Icon -->
        <div
            class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-500 mb-6">
            <i class="bi @yield('icon', 'bi-exclamation-triangle-fill') text-4xl"></i>
        </div>

        <!-- Error Code -->
        <h1 class="text-7xl md:text-8xl font-black tracking-tighter text-white mb-2">
            @yield('code')
        </h1>

        <!-- Error Message -->
        <h3 class="text-2xl font-sans font-bold text-slate-100 mb-4">
            @yield('message')
        </h3>

        <!-- Error Description -->
        <p class="text-slate-400 text-base md:text-lg mb-10 max-w-md mx-auto leading-relaxed">
            @yield('description')
        </p>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button onclick="window.history.back()"
                class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-700 hover:border-slate-500 bg-slate-800 text-slate-300 font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2">
                <i class="bi bi-arrow-left"></i> Regresar
            </button>
            <a href="{{ route('inicio') }}"
                class="w-full sm:w-auto px-7 py-3 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm shadow-md transition-all duration-200 flex items-center justify-center gap-2">
                <i class="bi bi-house-fill"></i> Ir al Inicio
            </a>
        </div>

        <!-- Footer Note -->
        <div class="mt-12 pt-6 border-t border-slate-800 text-xs text-slate-500">
            Si crees que esto es un error del sistema, por favor contacta al administrador.
        </div>

    </div>
</body>

</html>
