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
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['DM Serif Display', 'serif'],
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS Animations & Background -->
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0F172A;
            overflow: hidden;
        }
        
        .bg-glow-1 {
            background: radial-gradient(circle, rgba(217, 119, 6, 0.12) 0%, rgba(217, 119, 6, 0) 70%);
            animation: float-1 20s ease-in-out infinite alternate;
        }

        .bg-glow-2 {
            background: radial-gradient(circle, rgba(30, 41, 59, 0.6) 0%, rgba(30, 41, 59, 0) 70%);
            animation: float-2 25s ease-in-out infinite alternate;
        }

        .bg-glow-3 {
            background: radial-gradient(circle, rgba(254, 243, 199, 0.04) 0%, rgba(254, 243, 199, 0) 60%);
            animation: float-3 18s ease-in-out infinite alternate;
        }

        @keyframes float-1 {
            0% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(50px, 80px) scale(1.2); }
            100% { transform: translate(-30px, 40px) scale(0.9); }
        }

        @keyframes float-2 {
            0% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(-80px, -50px) scale(1.1); }
            100% { transform: translate(40px, -70px) scale(0.95); }
        }

        @keyframes float-3 {
            0% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(60px, -90px) scale(1.2); }
            100% { transform: translate(-40px, -30px) scale(0.85); }
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="h-full text-slate-100 flex items-center justify-center p-4 relative antialiased selection:bg-gold selection:text-white">
    
    <!-- Background Glow Blobs -->
    <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-glow-1 pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] rounded-full bg-glow-2 pointer-events-none"></div>
    <div class="absolute top-[30%] right-[20%] w-[40%] h-[40%] rounded-full bg-glow-3 pointer-events-none"></div>
    
    <!-- Pattern Overlay -->
    <div class="absolute inset-0 opacity-[0.02] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22 fill-opacity=%221%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <!-- Main Card -->
    <div class="w-full max-w-xl glass-card rounded-3xl p-8 md:p-12 text-center relative z-10 fade-in-up">
        
        <!-- Institution Logo -->
        <div class="flex items-center justify-center gap-3 mb-8">
            <div class="w-11 h-11 bg-slate-900 border border-slate-700/50 rounded-xl flex items-center justify-center shadow-lg">
                <i class="bi bi-mortarboard-fill text-gold-vivid text-2xl"></i>
            </div>
            <div class="text-left leading-none">
                <span class="text-xs uppercase tracking-widest text-slate-400 font-bold">Bolsa de Trabajo</span>
                <h2 class="text-sm font-serif text-slate-200 mt-0.5">IESTP <span class="italic text-gold-vivid">F. Vigo Caballero</span></h2>
            </div>
        </div>

        <!-- Error Icon with pulse/glow -->
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gold-vivid/10 border border-gold-vivid/20 text-gold-vivid mb-6">
            <i class="bi @yield('icon', 'bi-exclamation-triangle-fill') text-4xl"></i>
        </div>

        <!-- Error Code -->
        <h1 class="text-7xl md:text-8xl font-black tracking-tighter bg-gradient-to-b from-slate-100 via-slate-200 to-slate-400 bg-clip-text text-transparent mb-2">
            @yield('code')
        </h1>

        <!-- Error Message -->
        <h3 class="text-2xl font-serif font-bold text-slate-100 mb-4">
            @yield('message')
        </h3>

        <!-- Error Description -->
        <p class="text-slate-400 text-base md:text-lg mb-10 max-w-md mx-auto leading-relaxed">
            @yield('description')
        </p>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button onclick="window.history.back()" class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-700 hover:border-slate-500 bg-slate-800/40 hover:bg-slate-800 text-slate-300 font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2">
                <i class="bi bi-arrow-left"></i> Regresar
            </button>
            <a href="{{ route('inicio') }}" class="w-full sm:w-auto px-7 py-3 rounded-xl bg-gold-vivid hover:bg-amber-600 text-white font-semibold text-sm shadow-lg shadow-gold-vivid/20 hover:shadow-gold-vivid/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <i class="bi bi-house-fill"></i> Ir al Inicio
            </a>
        </div>

        <!-- Footer Note -->
        <div class="mt-12 pt-6 border-t border-slate-800/60 text-xs text-slate-500">
            Si crees que esto es un error del sistema, por favor contacta al administrador.
        </div>

    </div>
</body>
</html>
