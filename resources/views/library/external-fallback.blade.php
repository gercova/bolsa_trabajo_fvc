<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $book->title }} — Biblioteca Virtual</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, ::before, ::after {
            box-sizing: border-box;
            border-width: 0;
            border-style: solid;
            border-color: #1e293b;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #0b1120;
            color: #f1f5f9;
            overflow-x: hidden;
        }
        .canvas-bg {
            min-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            background: radial-gradient(circle at 50% 20%, rgba(15, 118, 110, 0.15) 0%, rgba(11, 17, 32, 0.98) 70%);
        }
        .fallback-card {
            width: 100%;
            max-width: 640px;
            background: rgba(15, 23, 42, 0.92);
            border: 1px solid rgba(51, 65, 85, 0.8);
            border-radius: 1.25rem;
            padding: 2.25rem 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            text-align: center;
            position: relative;
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .icon-halo {
            width: 4.5rem;
            height: 4.5rem;
            margin: 0 auto 1.25rem auto;
            border-radius: 1.25rem;
            background: linear-gradient(135deg, rgba(13, 148, 136, 0.25) 0%, rgba(2, 132, 199, 0.2) 100%);
            border: 1px solid rgba(45, 212, 191, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2dd4bf;
            box-shadow: 0 10px 25px -5px rgba(20, 184, 166, 0.25);
        }
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.025em;
            text-transform: uppercase;
            background: rgba(14, 165, 233, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
            margin-bottom: 0.75rem;
        }
        .card-title {
            font-size: 1.125rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.4;
            margin: 0 0 0.5rem 0;
        }
        .card-author {
            font-size: 0.8125rem;
            color: #94a3b8;
            margin: 0 0 1.25rem 0;
        }
        .info-box {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(51, 65, 85, 0.6);
            border-radius: 0.875rem;
            padding: 1rem 1.125rem;
            margin-bottom: 1.5rem;
            text-align: left;
            font-size: 0.8125rem;
            line-height: 1.55;
            color: #cbd5e1;
        }
        .info-box-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            color: #2dd4bf;
            margin-bottom: 0.375rem;
            font-size: 0.8125rem;
        }
        .actions-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        @media (min-width: 520px) {
            .actions-group {
                flex-direction: row;
            }
        }
        .btn-primary-action {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%);
            text-decoration: none;
            box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3);
            transition: all 0.15s ease-in-out;
            cursor: pointer;
        }
        .btn-primary-action:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
        }
        .btn-secondary-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #e2e8f0;
            background: rgba(30, 41, 59, 0.9);
            border: 1px solid rgba(71, 85, 105, 0.7);
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            cursor: pointer;
        }
        .btn-secondary-action:hover {
            background: rgba(51, 65, 85, 0.9);
            color: #ffffff;
        }
        .meta-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(51, 65, 85, 0.6);
            font-size: 0.75rem;
            color: #64748b;
        }
        .meta-tag {
            background: rgba(30, 41, 59, 0.4);
            padding: 0.2rem 0.5rem;
            border-radius: 0.375rem;
        }
    </style>
</head>
<body>
    <div class="canvas-bg">
        <div class="fallback-card">
            <!-- Icon Halo -->
            <div class="icon-halo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>

            <!-- Repository Badge -->
            <div class="badge-pill">
                <span>{{ $book->category ?? 'Documento Académico' }}</span>
                <span>•</span>
                <span>{{ $book->publisher ?: 'Repositorio Externo' }}</span>
            </div>

            <!-- Document Title -->
            <h1 class="card-title">{{ $book->title }}</h1>

            <!-- Author & Career -->
            <p class="card-author">
                {{ $book->author }}
                @if($book->studyProgram)
                    · <strong style="color: #5eead4;">{{ $book->studyProgram->name }}</strong>
                @endif
                @if($book->publication_year)
                    ({{ $book->publication_year }})
                @endif
            </p>

            <!-- Explanation Box -->
            <div class="info-box">
                <div class="info-box-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Repositorio Externo Protegido</span>
                </div>
                <p style="margin: 0;">
                    Este recurso académico se encuentra custodiado en el servidor oficial de <strong>{{ $book->publisher ?: 'la entidad académica de origen' }}</strong>. Debido a las políticas de seguridad contra incrustación en marcos (<em>Cross-Origin Frame Protection</em>), el documento debe ser visualizado directamente en su visor oficial o descargado a tu equipo.
                </p>
            </div>

            <!-- Actions Group -->
            <div class="actions-group">
                <a href="{{ $targetUrl }}" target="_blank" rel="noopener noreferrer" class="btn-primary-action" id="btnOpenOfficial">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                    <span>Abrir en Visor Oficial</span>
                </a>

                <a href="{{ $targetUrl }}" target="_blank" download class="btn-secondary-action">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    <span>Descargar PDF</span>
                </a>
            </div>

            <!-- Meta tags info -->
            <div class="meta-tags">
                <span class="meta-tag">IESTP Francisco Vigo Caballero</span>
                @if($book->isbn)
                    <span class="meta-tag">DOI / Identificador: {{ $book->isbn }}</span>
                @endif
                <span class="meta-tag">Acceso Abierto (Open Access)</span>
            </div>
        </div>
    </div>

    <script>
        // Notificar a la ventana principal que el documento está listo y requiere enlace directo
        try {
            if (window.parent && window.parent !== window) {
                window.parent.postMessage({
                    type: 'LIBRARY_READER_EXTERNAL_PROTECTED',
                    bookId: {{ $book->id }},
                    targetUrl: {!! json_encode($targetUrl) !!}
                }, '*');
            }
        } catch (e) {
            // Ignorar errores de postMessage entre orígenes
        }
    </script>
</body>
</html>
