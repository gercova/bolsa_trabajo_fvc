{{-- ═══════════════════════════════════════════════════════════
     SITE HEADER — Fixed, glassmorphism, scroll-aware
═══════════════════════════════════════════════════════════ --}}
<header id="site-header" class="fixed top-0 left-0 right-0 z-50"
    x-data="{}">

    {{-- ── Top accent stripe (visible on scroll) ──────────────── --}}
    <div class="header-stripe" aria-hidden="true"></div>

    {{-- ── Inner wrapper ──────────────────────────────────────── --}}
    <div class="nav-inner">

        {{-- ── Logo ─────────────────────────────────────────── --}}
        <a href="{{ route('inicio') }}" class="nav-logo" aria-label="Inicio — {{ $enterprise->trade_name }}">
            <div class="logo-badge">
                <img src="{{ $enterprise->logo_path }}" class="w-full h-full object-contain rounded-lg"
                    alt="{{ $enterprise->trade_name }}">
            </div>
            <div class="logo-text-block">
                <span class="logo-name">{{ $enterprise->trade_name }}</span>
                <span class="logo-sub">Educación Superior Tecnológica</span>
            </div>
        </a>

        {{-- ── Desktop Navigation ──────────────────────────────── --}}
        <nav class="nav-links" aria-label="Navegación principal">

            {{-- Inicio --}}
            <a href="{{ route('inicio') }}"
                class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('inicio') ? 'page' : 'false' }}">
                Inicio
            </a>

            {{-- Admisión y Matrícula --}}
            <div class="nav-dropdown" x-data="{ open: false }"
                @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false">
                <button type="button" class="nav-link nav-link--dropdown" @click="open = !open"
                    :class="open ? 'active' : ''" :aria-expanded="open">
                    Admisión
                    <i class="bi bi-chevron-down nav-chevron" :class="open ? 'rotated' : ''"></i>
                </button>
                <div class="dropdown-panel" x-show="open"
                    x-transition:enter="dropdown-enter" x-transition:enter-start="dropdown-enter-start"
                    x-transition:enter-end="dropdown-enter-end"
                    x-transition:leave="dropdown-leave" x-transition:leave-start="dropdown-enter-end"
                    x-transition:leave-end="dropdown-enter-start"
                    style="display:none;">
                    <div class="dropdown-panel-inner">
                        <a href="{{ route('cepre-fvc') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-book"></i></span>
                            <span class="dp-label">CEPRE FVC</span>
                        </a>
                        <a href="{{ route('examen-de-admision') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-pencil-square"></i></span>
                            <span class="dp-label">Examen de Admisión</span>
                        </a>
                        <a href="{{ route('matriculas') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-clipboard-check"></i></span>
                            <span class="dp-label">Matrículas</span>
                        </a>
                        <a href="{{ route('becas-y-creditos') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-award"></i></span>
                            <span class="dp-label">Becas y Créditos</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Programas de Estudio --}}
            <a href="{{ route('programas-de-estudio') }}"
                class="nav-link {{ request()->routeIs('programas-de-estudio') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('programas-de-estudio') ? 'page' : 'false' }}">
                Programas
            </a>

            {{-- Transparencia --}}
            <div class="nav-dropdown" x-data="{ open: false }"
                @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false">
                <button type="button" class="nav-link nav-link--dropdown" @click="open = !open"
                    :class="open ? 'active' : ''" :aria-expanded="open">
                    Transparencia
                    <i class="bi bi-chevron-down nav-chevron" :class="open ? 'rotated' : ''"></i>
                </button>
                <div class="dropdown-panel" x-show="open"
                    x-transition:enter="dropdown-enter" x-transition:enter-start="dropdown-enter-start"
                    x-transition:enter-end="dropdown-enter-end"
                    x-transition:leave="dropdown-leave" x-transition:leave-start="dropdown-enter-end"
                    x-transition:leave-end="dropdown-enter-start"
                    style="display:none;">
                    <div class="dropdown-panel-inner">
                        <a href="{{ route('documentos-de-gestion') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-folder2-open"></i></span>
                            <span class="dp-label">Documentos de Gestión</span>
                        </a>
                        <a href="{{ route('estadisticas') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-bar-chart-line"></i></span>
                            <span class="dp-label">Estadísticas</span>
                        </a>
                        <a href="{{ route('inversion-y-gestion') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-graph-up-arrow"></i></span>
                            <span class="dp-label">Inversión y Gestión</span>
                        </a>
                        <a href="{{ route('licenciamiento') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-patch-check"></i></span>
                            <span class="dp-label">Licenciamiento</span>
                        </a>
                        <a href="{{ route('libro-de-reclamaciones') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-journal-text"></i></span>
                            <span class="dp-label">Libro de Reclamaciones</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Trámites --}}
            <div class="nav-dropdown" x-data="{ open: false }"
                @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false">
                <button type="button" class="nav-link nav-link--dropdown" @click="open = !open"
                    :class="open ? 'active' : ''" :aria-expanded="open">
                    Trámites
                    <i class="bi bi-chevron-down nav-chevron" :class="open ? 'rotated' : ''"></i>
                </button>
                <div class="dropdown-panel" x-show="open"
                    x-transition:enter="dropdown-enter" x-transition:enter-start="dropdown-enter-start"
                    x-transition:enter-end="dropdown-enter-end"
                    x-transition:leave="dropdown-leave" x-transition:leave-start="dropdown-enter-end"
                    x-transition:leave-end="dropdown-enter-start"
                    style="display:none;">
                    <div class="dropdown-panel-inner">
                        <a href="{{ route('mesa-de-partes') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-inbox"></i></span>
                            <span class="dp-label">Mesa de Partes</span>
                        </a>
                        <a href="{{ route('tupa') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-file-earmark-ruled"></i></span>
                            <span class="dp-label">TUPA</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Nosotros --}}
            <div class="nav-dropdown" x-data="{ open: false }"
                @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false">
                <button type="button" class="nav-link nav-link--dropdown" @click="open = !open"
                    :class="open ? 'active' : ''" :aria-expanded="open">
                    Nosotros
                    <i class="bi bi-chevron-down nav-chevron" :class="open ? 'rotated' : ''"></i>
                </button>
                <div class="dropdown-panel" x-show="open"
                    x-transition:enter="dropdown-enter" x-transition:enter-start="dropdown-enter-start"
                    x-transition:enter-end="dropdown-enter-end"
                    x-transition:leave="dropdown-leave" x-transition:leave-start="dropdown-enter-end"
                    x-transition:leave-end="dropdown-enter-start"
                    style="display:none;">
                    <div class="dropdown-panel-inner">
                        <a href="{{ route('quienes-somos') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-people"></i></span>
                            <span class="dp-label">¿Quiénes somos?</span>
                        </a>
                        <a href="{{ route('historia') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-hourglass-split"></i></span>
                            <span class="dp-label">Reseña Histórica</span>
                        </a>
                        <a href="{{ route('organigrama-institucional') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-diagram-3"></i></span>
                            <span class="dp-label">Organigrama</span>
                        </a>
                        <a href="{{ route('plana-jerarquica') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-person-badge"></i></span>
                            <span class="dp-label">Plana Jerárquica</span>
                        </a>
                        <a href="{{ route('plana-de-docentes') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-mortarboard"></i></span>
                            <span class="dp-label">Plana Docente</span>
                        </a>
                        <a href="{{ route('plana-administrativa') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-briefcase"></i></span>
                            <span class="dp-label">Plana Administrativa</span>
                        </a>
                        <a href="{{ route('consejo-de-estudiantes') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-person-heart"></i></span>
                            <span class="dp-label">Consejo de Estudiantes</span>
                        </a>
                        <a href="{{ route('locales') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-geo-alt"></i></span>
                            <span class="dp-label">Locales</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Servicios --}}
            <div class="nav-dropdown" x-data="{ open: false }"
                @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false">
                <button type="button" class="nav-link nav-link--dropdown" @click="open = !open"
                    :class="open ? 'active' : ''" :aria-expanded="open">
                    Servicios
                    <i class="bi bi-chevron-down nav-chevron" :class="open ? 'rotated' : ''"></i>
                </button>
                <div class="dropdown-panel" x-show="open"
                    x-transition:enter="dropdown-enter" x-transition:enter-start="dropdown-enter-start"
                    x-transition:enter-end="dropdown-enter-end"
                    x-transition:leave="dropdown-leave" x-transition:leave-start="dropdown-enter-end"
                    x-transition:leave-end="dropdown-enter-start"
                    style="display:none;">
                    <div class="dropdown-panel-inner">
                        <a href="https://iestpfranciscovigocaballero.bibliotecalatina.com/login"
                            target="_blank" rel="noopener" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-book-half"></i></span>
                            <span class="dp-label">Biblioteca Virtual <i class="bi bi-box-arrow-up-right dp-ext"></i></span>
                        </a>
                        <a href="{{ route('bolsa-de-trabajo') }}" class="dp-item">
                            <span class="dp-icon"><i class="bi bi-briefcase-fill"></i></span>
                            <span class="dp-label">Bolsa de Trabajo</span>
                        </a>
                    </div>
                </div>
            </div>

        </nav>

        {{-- ── Right actions (Auth / User) ────────────────────── --}}
        <div class="nav-right">
            @auth
                <div class="nav-dropdown" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button" class="user-trigger" @click="open = !open" :aria-expanded="open">
                        <div class="user-avatar" aria-hidden="true">
                            {{ strtoupper(substr(Auth::user()->names, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <span class="user-greeting">Bienvenido,</span>
                            <span class="user-name">{{ explode(' ', Auth::user()->names)[0] }}</span>
                        </div>
                        <i class="bi bi-chevron-down nav-chevron nav-chevron--sm" :class="open ? 'rotated' : ''"></i>
                    </button>

                    <div class="dropdown-panel dropdown-panel--right" x-show="open"
                        x-transition:enter="dropdown-enter" x-transition:enter-start="dropdown-enter-start"
                        x-transition:enter-end="dropdown-enter-end"
                        x-transition:leave="dropdown-leave" x-transition:leave-start="dropdown-enter-end"
                        x-transition:leave-end="dropdown-enter-start"
                        style="display:none;">
                        <div class="dropdown-panel-inner">

                            {{-- User identity card --}}
                            <div class="dp-user-card">
                                <div class="dp-user-avatar">{{ strtoupper(substr(Auth::user()->names, 0, 1)) }}</div>
                                <div class="dp-user-info">
                                    <p class="dp-user-name">{{ Auth::user()->names }}</p>
                                    <p class="dp-user-email">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <div class="dp-divider"></div>

                            <a href="{{ route('admin.profile.edit', auth()->user()) }}" class="dp-item">
                                <span class="dp-icon"><i class="bi bi-person-circle"></i></span>
                                <span class="dp-label">Mi Perfil</span>
                            </a>
                            <a href="{{ route('admin.enterprise.edit') }}" class="dp-item">
                                <span class="dp-icon"><i class="bi bi-buildings"></i></span>
                                <span class="dp-label">Datos de Empresa</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="dp-item">
                                <span class="dp-icon"><i class="bi bi-people"></i></span>
                                <span class="dp-label">Usuarios</span>
                            </a>
                            <div class="dp-divider"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dp-item dp-item--danger">
                                    <span class="dp-icon"><i class="bi bi-box-arrow-right"></i></span>
                                    <span class="dp-label">Cerrar Sesión</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-ghost" id="nav-btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Ingresar
                </a>
                <a href="{{ route('register') }}" class="btn-primary" id="nav-btn-register">
                    <i class="bi bi-person-plus"></i> Registrarse
                </a>
            @endauth
        </div>

        {{-- ── Hamburger (mobile only) ─────────────────────────── --}}
        <button class="hamburger" @click="mobileMenuOpen = !mobileMenuOpen"
            :aria-expanded="mobileMenuOpen" aria-label="Abrir menú" aria-controls="mobile-menu">
            <i class="bi" :class="mobileMenuOpen ? 'bi-x-lg' : 'bi-list'"></i>
        </button>

    </div>{{-- /nav-inner --}}
</header>

{{-- ═══════════════════════════════════════════════════════════
     MOBILE NAVIGATION DRAWER
═══════════════════════════════════════════════════════════ --}}
<div id="mobile-menu" role="dialog" aria-label="Menú móvil" aria-modal="true"
    x-show="mobileMenuOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-3"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-3"
    class="fixed left-0 right-0 z-40 overflow-y-auto bg-white/98 backdrop-blur-md border-b border-sky-100 shadow-xl md:hidden"
    :style="'top: ' + document.getElementById('site-header')?.offsetHeight + 'px; max-height: calc(100vh - ' + (document.getElementById('site-header')?.offsetHeight ?? 68) + 'px)'"
    style="display:none; top: var(--nav-h); max-height: calc(100vh - var(--nav-h))">

    <div class="mobile-nav-inner">

        {{-- Inicio --}}
        <a href="{{ route('inicio') }}" class="mobile-link">
            <i class="bi bi-house-door"></i> Inicio
        </a>

        {{-- Admisión y Matrícula --}}
        <div x-data="{ open: false }" class="w-full">
            <button @click="open = !open" class="mobile-link mobile-link--accordion w-full"
                :class="open ? 'active' : ''" :aria-expanded="open">
                <div class="flex items-center gap-3">
                    <i class="bi bi-journal-bookmark"></i> Admisión y Matrícula
                </div>
                <i class="bi bi-chevron-down mobile-chevron" :class="open ? 'rotated' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display:none;" class="mobile-sub">
                <a href="{{ route('cepre-fvc') }}" class="mobile-sub-link">CEPRE FVC</a>
                <a href="{{ route('examen-de-admision') }}" class="mobile-sub-link">Examen de Admisión</a>
                <a href="{{ route('matriculas') }}" class="mobile-sub-link">Matrículas</a>
                <a href="{{ route('becas-y-creditos') }}" class="mobile-sub-link">Becas y Créditos</a>
            </div>
        </div>

        {{-- Programas de Estudio --}}
        <a href="{{ route('programas-de-estudio') }}" class="mobile-link">
            <i class="bi bi-mortarboard"></i> Programas de Estudio
        </a>

        {{-- Transparencia --}}
        <div x-data="{ open: false }" class="w-full">
            <button @click="open = !open" class="mobile-link mobile-link--accordion w-full"
                :class="open ? 'active' : ''" :aria-expanded="open">
                <div class="flex items-center gap-3">
                    <i class="bi bi-shield-check"></i> Transparencia
                </div>
                <i class="bi bi-chevron-down mobile-chevron" :class="open ? 'rotated' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display:none;" class="mobile-sub">
                <a href="{{ route('documentos-de-gestion') }}" class="mobile-sub-link">Documentos de Gestión</a>
                <a href="{{ route('estadisticas') }}" class="mobile-sub-link">Estadísticas</a>
                <a href="{{ route('inversion-y-gestion') }}" class="mobile-sub-link">Inversión y Gestión</a>
                <a href="{{ route('licenciamiento') }}" class="mobile-sub-link">Licenciamiento</a>
                <a href="{{ route('libro-de-reclamaciones') }}" class="mobile-sub-link">Libro de Reclamaciones</a>
            </div>
        </div>

        {{-- Trámites --}}
        <div x-data="{ open: false }" class="w-full">
            <button @click="open = !open" class="mobile-link mobile-link--accordion w-full"
                :class="open ? 'active' : ''" :aria-expanded="open">
                <div class="flex items-center gap-3">
                    <i class="bi bi-file-earmark-text"></i> Trámites
                </div>
                <i class="bi bi-chevron-down mobile-chevron" :class="open ? 'rotated' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display:none;" class="mobile-sub">
                <a href="{{ route('mesa-de-partes') }}" class="mobile-sub-link">Mesa de Partes</a>
                <a href="{{ route('tupa') }}" class="mobile-sub-link">TUPA</a>
            </div>
        </div>

        {{-- Nosotros --}}
        <div x-data="{ open: false }" class="w-full">
            <button @click="open = !open" class="mobile-link mobile-link--accordion w-full"
                :class="open ? 'active' : ''" :aria-expanded="open">
                <div class="flex items-center gap-3">
                    <i class="bi bi-info-circle"></i> Nosotros
                </div>
                <i class="bi bi-chevron-down mobile-chevron" :class="open ? 'rotated' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display:none;" class="mobile-sub">
                <a href="{{ route('quienes-somos') }}" class="mobile-sub-link">¿Quiénes somos?</a>
                <a href="{{ route('historia') }}" class="mobile-sub-link">Reseña Histórica</a>
                <a href="{{ route('organigrama-institucional') }}" class="mobile-sub-link">Organigrama</a>
                <a href="{{ route('plana-jerarquica') }}" class="mobile-sub-link">Plana Jerárquica</a>
                <a href="{{ route('plana-de-docentes') }}" class="mobile-sub-link">Plana Docente</a>
                <a href="{{ route('plana-administrativa') }}" class="mobile-sub-link">Plana Administrativa</a>
                <a href="{{ route('consejo-de-estudiantes') }}" class="mobile-sub-link">Consejo de Estudiantes</a>
                <a href="{{ route('locales') }}" class="mobile-sub-link">Locales</a>
            </div>
        </div>

        {{-- Servicios --}}
        <div x-data="{ open: false }" class="w-full">
            <button @click="open = !open" class="mobile-link mobile-link--accordion w-full"
                :class="open ? 'active' : ''" :aria-expanded="open">
                <div class="flex items-center gap-3">
                    <i class="bi bi-briefcase"></i> Servicios
                </div>
                <i class="bi bi-chevron-down mobile-chevron" :class="open ? 'rotated' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display:none;" class="mobile-sub">
                <a href="https://iestpfranciscovigocaballero.bibliotecalatina.com/login"
                    target="_blank" rel="noopener" class="mobile-sub-link">
                    Biblioteca Virtual <i class="bi bi-box-arrow-up-right text-[10px] ml-1"></i>
                </a>
                <a href="{{ route('bolsa-de-trabajo') }}" class="mobile-sub-link">Bolsa de Trabajo</a>
            </div>
        </div>

        <div class="mobile-divider"></div>

        {{-- Auth section --}}
        @auth
            <div class="mobile-user-card">
                <div class="mobile-avatar">{{ strtoupper(substr(Auth::user()->names, 0, 1)) }}</div>
                <div class="overflow-hidden">
                    <p class="mobile-user-name truncate">{{ Auth::user()->names }}</p>
                    <p class="mobile-user-email truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.profile.edit', auth()->user()) }}" class="mobile-link">
                <i class="bi bi-person-circle"></i> Mi Perfil
            </a>
            <a href="{{ route('admin.enterprise.edit') }}" class="mobile-link">
                <i class="bi bi-buildings"></i> Datos de Empresa
            </a>
            <a href="{{ route('admin.users.index') }}" class="mobile-link">
                <i class="bi bi-people"></i> Usuarios
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="mobile-link mobile-link--danger w-full text-left">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </button>
            </form>
        @else
            <div class="mobile-auth-btns">
                <a href="{{ route('login') }}" class="mobile-btn-ghost">
                    <i class="bi bi-box-arrow-in-right"></i> Ingresar
                </a>
                <a href="{{ route('register') }}" class="mobile-btn-primary">
                    <i class="bi bi-person-plus"></i> Registrarse
                </a>
            </div>
        @endauth

    </div>{{-- /mobile-nav-inner --}}
</div>
