<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $enterprise->favicon_path }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bolsa de Trabajo — Instituto Francisco Vigo Caballero')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    @include('admin.components.nav-header')
    <!-- ═══════════════════════════════════════
        MAIN CONTENT
    ═══════════════════════════════════════ -->
    <main id="main-content">
        @yield('content')
    </main>
    <!-- ═══════════════════════════════════════
        FOOTER
    ═══════════════════════════════════════ -->
    <footer id="site-footer">
        <div class="footer-grid">
            <!-- Col 1: Brand -->
            <div>
                <div class="footer-brand-badge">
                    <div class="footer-icon"><i class="bi bi-mortarboard-fill"></i></div>
                    <div class="footer-title">
                        IESTP<br><em>Francisco Vigo Caballero</em>
                    </div>
                </div>
                <p class="footer-desc">
                    Formando profesionales técnicos desde hace décadas. Elige entre 5 carreras profesionales y obtén tu
                    Título de Técnico a Nombre de la Nación en solo 3 años de formación 100% práctica.
                </p>
                <div class="footer-tagline">
                    <i class="bi bi-patch-check-fill"></i>
                    Institución en proceso de licenciamiento
                </div>
            </div>
            <!-- Col 2: Quick links -->
            <div>
                <p class="footer-col-title">Navegación</p>
                <a href="{{ route('inicio') }}" class="footer-link"> Inicio</a>
                <a href="{{ route('bolsa-de-trabajo') }}" class="footer-link"> Ofertas de empleo</a>
                <a href="{{ route('quienes-somos') }}" class="footer-link"> Sobre nosotros</a>
                @guest
                    <a href="{{ route('login') }}" class="footer-link"> Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="footer-link"> Registrarse</a>
                @endguest
            </div>
            <!-- Col 3: Contact -->
            <div>
                <p class="footer-col-title">Contacto</p>
                <div class="footer-contact-item">
                    <div class="footer-contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <span>{{ $enterprise->address ?? 'Av. Principal 123' }},
                        {{ $enterprise->city ?? 'Trujillo' }}</span>
                </div>
                <div class="footer-contact-item" style="margin-top:10px;">
                    <div class="footer-contact-icon"><i class="bi bi-telephone-fill"></i></div>
                    <span>{{ $enterprise->phone_number_1 ?? '+51 123 456 789' }}</span>
                </div>
                <div class="footer-contact-item" style="margin-top:10px;">
                    <div class="footer-contact-icon"><i class="bi bi-envelope-fill"></i></div>
                    <span>{{ $enterprise->email ?? 'info@fvigo.edu' }}</span>
                </div>
            </div>
            <!-- Col 4: Social -->
            <div>
                <p class="footer-col-title">Síguenos</p>
                <div class="social-grid">
                    @if ($enterprise->facebook_link)
                        <a href="{{ $enterprise->facebook_link }}" class="social-btn fb" target="_blank"
                            rel="noopener" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                    @endif
                    @if ($enterprise->twitter_link)
                        <a href="{{ $enterprise->twitter_link }}" class="social-btn tw" target="_blank"
                            rel="noopener" aria-label="Twitter/X">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                    @endif
                    @if ($enterprise->linkedin_link)
                        <a href="{{ $enterprise->linkedin_link }}" class="social-btn li" target="_blank"
                            rel="noopener" aria-label="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    @endif
                    @if ($enterprise->instagram_link)
                        <a href="{{ $enterprise->instagram_link }}" class="social-btn ig" target="_blank"
                            rel="noopener" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    @endif
                    @if ($enterprise->whatsapp_link)
                        <a href="{{ $enterprise->whatsapp_link }}" class="social-btn wa" target="_blank" rel="noopener"
                            aria-label="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    @endif
                </div>

                <div style="margin-top: 24px;">
                    <p class="footer-col-title">Horario</p>
                    <p style="font-size:14px; color:rgba(255,255,255,.45); line-height:1.7;">
                        Lunes a Viernes<br>
                        <span style="color:rgba(255,255,255,.65); font-weight:500;">7:30 am – 1:30 pm</span>
                    </p>
                </div>
            </div>
        </div><!-- /footer-grid -->
        <!-- Bottom bar -->
        <div class="footer-bottom">
            <div class="footer-bottom-inner">
                <p class="footer-copy">
                    &copy; {{ date('Y') }} IESTP Francisco Vigo Caballero · Todos los derechos reservados.
                </p>
                <div class="footer-legal">
                    <a href="#">Privacidad</a>
                    <a href="#">Términos</a>
                    <a href="#">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- SCRIPTS -->
    <script>
        /* ── Navbar scroll effect ──────────────────────────── */
        const header = document.getElementById('site-header');
        const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 10);
        window.addEventListener('scroll', onScroll, {
            passive: true
        });
        onScroll();

        /* ── Mobile menu toggle ────────────────────────────── */
        const hamburger = document.getElementById('hamburgerBtn');
        const mobileMenu = document.getElementById('mobile-menu');
        const hamburgerIcon = document.getElementById('hamburgerIcon');

        hamburger.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.toggle('open');
            hamburger.setAttribute('aria-expanded', isOpen);
            mobileMenu.setAttribute('aria-hidden', !isOpen);
            hamburgerIcon.className = isOpen ? 'bi bi-x' : 'bi bi-list';
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });

        /* Close mobile menu on link/button click */
        mobileMenu.querySelectorAll('a, button').forEach(el => {
            el.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                hamburger.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
                hamburgerIcon.className = 'bi bi-list';
                document.body.style.overflow = '';
            });
        });

        /* Close on outside click */
        document.addEventListener('click', e => {
            if (!header.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('open');
                hamburger.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
                hamburgerIcon.className = 'bi bi-list';
                document.body.style.overflow = '';
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
