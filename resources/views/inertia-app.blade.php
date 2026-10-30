<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Google Search Console --}}
    <meta name="google-site-verification" content="n-kFDeVvGTkr1sar6qNZRP1l5kFEqJzI-4PDgCCTC2s" />

    {{-- Título y meta gestionados por Vue con <Head> de Inertia --}}
    @inertiaHead

    {{-- Favicon dinámico --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ \App\Models\Enterprise::getDefault()->favicon_path }}">

    {{-- Fuentes --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap">

    {{-- Tailwind CDN (mismo que usa app.blade.php) --}}
    <script src="{{ asset('js/cdn.tailwind-3.4.17.js') }}"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto',
                            'Helvetica', 'Arial', 'sans-serif'
                        ],
                        display: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Icons & Estilos --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')

    {{-- Vite (Vue + Inertia) --}}
    @vite('resources/js/app.js')
</head>

<body class="bg-white text-slate-900 antialiased">
    @inertia
</body>

</html>
