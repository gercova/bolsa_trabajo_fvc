<!DOCTYPE html>
<html lang="es" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- ═══ PRIMARY META ══════════════════════════════════════════ --}}
    <title>@yield('title', $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero')</title>
    <meta name="description" content="@yield('meta_description', 'Bolsa de Trabajo del IESTP Francisco Vigo Caballero — Conecta con las mejores ofertas de empleo y prácticas profesionales en Uchiza.')">
    <meta name="keywords"    content="@yield('meta_keywords', 'bolsa de trabajo, empleo, prácticas, instituto, francisco vigo caballero, uchiza, carreras técnicas, san martín')">
    <meta name="author"      content="{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}">
    <meta name="robots"      content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical"    href="@yield('canonical_url', request()->url())">

    {{-- ═══ GEO ════════════════════════════════════════════════════ --}}
    <meta name="geo.region"      content="PE-SAM">
    <meta name="geo.placename"   content="Uchiza, San Martín, Perú">

    {{-- ═══ FAVICON ════════════════════════════════════════════════ --}}
    <link rel="icon"              type="image/png" sizes="32x32" href="{{ $enterprise->favicon_path }}">
    <link rel="icon"              type="image/png" sizes="16x16" href="{{ $enterprise->favicon_path }}">
    <link rel="apple-touch-icon"                               href="{{ $enterprise->favicon_path }}">

    {{-- ═══ OPEN GRAPH ════════════════════════════════════════════ --}}
    <meta property="og:type"        content="website">
    <meta property="og:locale"      content="es_PE">
    <meta property="og:title"       content="@yield('title', $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero')">
    <meta property="og:description" content="@yield('meta_description', 'Bolsa de Trabajo del IESTP Francisco Vigo Caballero — Conecta con las mejores ofertas de empleo y prácticas profesionales en Uchiza.')">
    <meta property="og:image"       content="@yield('og_image', url($enterprise->logo_path ?? ''))">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url"         content="{{ request()->url() }}">
    <meta property="og:site_name"   content="{{ $enterprise->trade_name ?? 'IESTP Francisco Vigo Caballero' }}">

    {{-- ═══ TWITTER CARD ══════════════════════════════════════════ --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('title', $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero')">
    <meta name="twitter:description" content="@yield('meta_description', 'Bolsa de Trabajo del IESTP Francisco Vigo Caballero — Ofertas de empleo y prácticas profesionales.')">
    <meta name="twitter:image"       content="@yield('og_image', url($enterprise->logo_path ?? ''))">

    {{-- ═══ JSON-LD STRUCTURED DATA ══════════════════════════════ --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "EducationalOrganization",
      "name":  "{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}",
      "alternateName": "{{ $enterprise->trade_name ?? 'IESTP FVC' }}",
      "url":   "{{ url('/') }}",
      "logo":  "{{ url($enterprise->logo_path ?? '') }}",
      "description": "{{ $enterprise->description ?? 'Instituto de Educación Superior Tecnológico Público Francisco Vigo Caballero.' }}",
      "address": {
        "@type": "PostalAddress",
        "streetAddress":   "{{ $enterprise->address    ?? 'Av. Ricardo Palma N° 1401' }}",
        "addressLocality": "{{ $enterprise->city       ?? 'Uchiza' }}",
        "addressRegion":   "San Martín",
        "addressCountry":  "PE"
      },
      "telephone": "{{ $enterprise->phone_number_1 ?? '' }}",
      "email":     "{{ $enterprise->email          ?? '' }}",
      "sameAs": [
        @if($enterprise->facebook_link)  "{{ $enterprise->facebook_link  }}", @endif
        @if($enterprise->instagram_link) "{{ $enterprise->instagram_link }}", @endif
        @if($enterprise->linkedin_link)  "{{ $enterprise->linkedin_link  }}" @endif
      ]
    }
    </script>

    {{-- ═══ FONTS (preconnect first, then actual load) ════════════ --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- ═══ TAILWIND CDN ══════════════════════════════════════════ --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:    ['Outfit', 'Inter', 'ui-sans-serif', 'system-ui'],
                        display: ['"DM Serif Display"', 'serif'],
                    }
                }
            }
        }
    </script>

    {{-- ═══ ICONS & STYLES ════════════════════════════════════════ --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')

    {{-- ═══ ALPINE.JS ══════════════════════════════════════════════ --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body
    x-data="{ mobileMenuOpen: false }"
    :class="mobileMenuOpen ? 'overflow-hidden' : ''"
    class="bg-white text-slate-900 antialiased">

    {{-- skip to main content (accessibility) --}}
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[200] focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded-lg focus:text-sm focus:font-semibold">
        Saltar al contenido principal
    </a>

    @include('admin.components.nav-header')

    {{-- ═══ MAIN CONTENT ══════════════════════════════════════════ --}}
    <main id="main-content" role="main">
        @yield('content')
    </main>

    {{-- ═══ FOOTER ════════════════════════════════════════════════ --}}
    <footer id="site-footer" role="contentinfo" aria-label="Pie de página del sitio">
        <div class="footer-grid">

            {{-- Col 1: Brand --}}
            <div>
                <div class="footer-brand-badge">
                    <div class="footer-icon"><i class="bi bi-mortarboard-fill" aria-hidden="true"></i></div>
                    <div class="footer-title">
                        IESTP<br><em>Francisco Vigo Caballero</em>
                    </div>
                </div>
                <p class="footer-desc">
                    {{ $enterprise->description
                        ? Str::limit($enterprise->description, 160)
                        : 'Formando profesionales técnicos con 5 carreras a Nombre de la Nación. Calidad, práctica y vocación de servicio.' }}
                </p>
                <div class="footer-tagline">
                    <i class="bi bi-patch-check-fill" aria-hidden="true"></i>
                    Institución en proceso de licenciamiento
                </div>
            </div>

            {{-- Col 2: Quick links --}}
            <div>
                <p class="footer-col-title">Navegación</p>
                <nav aria-label="Links rápidos del footer">
                    <a href="{{ route('inicio') }}"          class="footer-link"><i class="bi bi-arrow-right-short" aria-hidden="true"></i> Inicio</a>
                    <a href="{{ route('bolsa-de-trabajo') }}" class="footer-link"><i class="bi bi-arrow-right-short" aria-hidden="true"></i> Ofertas de empleo</a>
                    <a href="{{ route('programas-de-estudio') }}" class="footer-link"><i class="bi bi-arrow-right-short" aria-hidden="true"></i> Programas de estudio</a>
                    <a href="{{ route('quienes-somos') }}"   class="footer-link"><i class="bi bi-arrow-right-short" aria-hidden="true"></i> Sobre nosotros</a>
                    <a href="{{ route('mesa-de-partes') }}"  class="footer-link"><i class="bi bi-arrow-right-short" aria-hidden="true"></i> Mesa de partes</a>
                    @guest
                        <a href="{{ route('login') }}"    class="footer-link"><i class="bi bi-arrow-right-short" aria-hidden="true"></i> Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="footer-link"><i class="bi bi-arrow-right-short" aria-hidden="true"></i> Registrarse</a>
                    @endguest
                </nav>
            </div>

            {{-- Col 3: Contact --}}
            <div>
                <p class="footer-col-title">Contacto</p>
                <address class="not-italic">
                    @if($enterprise->address || $enterprise->city)
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i></div>
                        <span>{{ $enterprise->address ?? 'Av. Ricardo Palma N° 1401' }},
                              {{ $enterprise->city    ?? 'Uchiza' }}</span>
                    </div>
                    @endif
                    @if($enterprise->phone_number_1)
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon"><i class="bi bi-telephone-fill" aria-hidden="true"></i></div>
                        <a href="tel:{{ $enterprise->phone_number_1 }}" class="hover:text-white transition-colors">
                            {{ $enterprise->phone_number_1 }}
                        </a>
                    </div>
                    @endif
                    @if($enterprise->phone_number_2)
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon"><i class="bi bi-telephone-fill" aria-hidden="true"></i></div>
                        <a href="tel:{{ $enterprise->phone_number_2 }}" class="hover:text-white transition-colors">
                            {{ $enterprise->phone_number_2 }}
                        </a>
                    </div>
                    @endif
                    @if($enterprise->email)
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon"><i class="bi bi-envelope-fill" aria-hidden="true"></i></div>
                        <a href="mailto:{{ $enterprise->email }}" class="hover:text-white transition-colors break-all">
                            {{ $enterprise->email }}
                        </a>
                    </div>
                    @endif
                </address>
            </div>

            {{-- Col 4: Social + Hours --}}
            <div>
                <p class="footer-col-title">Síguenos</p>
                <div class="social-grid" role="list" aria-label="Redes sociales">
                    @if($enterprise->facebook_link)
                        <a href="{{ $enterprise->facebook_link }}" class="social-btn fb" target="_blank" rel="noopener noreferrer" aria-label="Visítanos en Facebook" role="listitem">
                            <i class="bi bi-facebook" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if($enterprise->twitter_link)
                        <a href="{{ $enterprise->twitter_link }}" class="social-btn tw" target="_blank" rel="noopener noreferrer" aria-label="Síguenos en Twitter/X" role="listitem">
                            <i class="bi bi-twitter-x" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if($enterprise->linkedin_link)
                        <a href="{{ $enterprise->linkedin_link }}" class="social-btn li" target="_blank" rel="noopener noreferrer" aria-label="Conéctanos en LinkedIn" role="listitem">
                            <i class="bi bi-linkedin" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if($enterprise->instagram_link)
                        <a href="{{ $enterprise->instagram_link }}" class="social-btn ig" target="_blank" rel="noopener noreferrer" aria-label="Síguenos en Instagram" role="listitem">
                            <i class="bi bi-instagram" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if($enterprise->whatsapp_link)
                        <a href="{{ $enterprise->whatsapp_link }}" class="social-btn wa" target="_blank" rel="noopener noreferrer" aria-label="Escríbenos por WhatsApp" role="listitem">
                            <i class="bi bi-whatsapp" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>

                <div class="footer-schedule">
                    <p class="footer-col-title">Horario de atención</p>
                    <p class="footer-schedule-text">
                        Lunes a Viernes<br>
                        <span class="footer-schedule-hours">7:30 am – 1:30 pm</span>
                    </p>
                </div>
            </div>

        </div><!-- /footer-grid -->

        {{-- Bottom Bar --}}
        <div class="footer-bottom">
            <div class="footer-bottom-inner">
                <p class="footer-copy">
                    &copy; {{ date('Y') }} IESTP Francisco Vigo Caballero &middot; Todos los derechos reservados.
                </p>
                <nav class="footer-legal" aria-label="Links legales">
                    <a href="#">Privacidad</a>
                    <a href="#">Términos</a>
                    <a href="#">Cookies</a>
                </nav>
            </div>
        </div>
    </footer>

    {{-- ═══ GLOBAL SCRIPTS ══════════════════════════════════════ --}}
    <script>
        /* ── Navbar scroll-shrink effect ───────────────────────── */
        const siteHeader = document.getElementById('site-header');
        if (siteHeader) {
            const NAV_DEFAULT = 72;
            const NAV_SHRUNK  = 58;
            document.documentElement.style.setProperty('--nav-h', NAV_DEFAULT + 'px');

            const onScroll = () => {
                const scrolled = window.scrollY > 20;
                siteHeader.classList.toggle('scrolled', scrolled);
                const h = scrolled ? NAV_SHRUNK : NAV_DEFAULT;
                document.documentElement.style.setProperty('--nav-h', h + 'px');
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        }

        /* ── Close mobile menu on link click ───────────────────── */
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                const body = document.querySelector('[x-data]');
                if (body && body._x_dataStack) {
                    body._x_dataStack[0].mobileMenuOpen = false;
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
