<!-- ═══════════════════════════════════════
    HEADER / NAVBAR
═══════════════════════════════════════ -->
<header id="site-header">
    <div class="nav-inner">
        <!-- Logo -->
        <a href="{{ route('inicio') }}" class="nav-logo">
            <div class="logo-badge">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <span class="logo-name">
                {{ $enterprise->trade_name }}
            </span>
        </a>

        <!-- Desktop Nav Links -->
        <nav class="nav-links">
            <a href="{{ route('inicio') }}"   class="nav-link {{ request()->routeIs('inicio')   ? 'active' : '' }}">
                Inicio
            </a>
            <a href="{{ route('ofertas') }}"  class="nav-link {{ request()->routeIs('ofertas')  ? 'active' : '' }}">
                Programas de Estudio
            </a>
            <a href="{{ route('ofertas') }}"  class="nav-link {{ request()->routeIs('ofertas')  ? 'active' : '' }}">
                Admisión y Matrículas
            </a>
            <a href="{{ route('ofertas') }}"  class="nav-link {{ request()->routeIs('ofertas')  ? 'active' : '' }}">
                Transparencia
            </a>
            <a href="{{ route('nosotros') }}" class="nav-link {{ request()->routeIs('nosotros') ? 'active' : '' }}">
                Trámites
            </a>
            <a href="{{ route('nosotros') }}" class="nav-link {{ request()->routeIs('nosotros') ? 'active' : '' }}">
                Nosotros
            </a>
        </nav>

        <!-- Desktop Right Actions -->
        <div class="nav-right">
            @auth
                <div class="dropdown-wrap">
                    <button class="user-trigger" type="button" aria-haspopup="true">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->names, 0, 1)) }}</div>
                        <span class="user-name">{{ Auth::user()->names }}</span>
                        <i class="bi bi-chevron-down user-chevron"></i>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        <a href="#" class="dropdown-item" role="menuitem">
                            <i class="bi bi-person-circle" style="font-size:16px;color:var(--ink-muted)"></i>
                            Mi Perfil
                        </a>
                        <a href="#" class="dropdown-item" role="menuitem">
                            <i class="bi bi-bookmark" style="font-size:16px;color:var(--ink-muted)"></i>
                            Gestionar puesto
                        </a>
                        <a href="{{ route('admin.enterprise.edit') }}" class="dropdown-item" role="menuitem">
                            <i class="bi bi-buildings"></i> Datos de empresa
                        </a>
                        <a href="{{ route('admin.usuarios') }}" class="dropdown-item" role="menuitem">
                            <i class="bi bi-people"></i> Usuarios
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger" role="menuitem">
                                <i class="bi bi-box-arrow-right" style="font-size:16px;color:#DC2626"></i>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">
                    <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                </a>
                <a href="{{ route('register') }}" class="btn-primary">
                    <i class="bi bi-person-plus"></i> Registrarse
                </a>
            @endauth
        </div>

        <!-- Hamburger (mobile) -->
        <button class="hamburger" id="hamburgerBtn" aria-label="Abrir menú" aria-expanded="false">
            <i class="bi bi-list" id="hamburgerIcon"></i>
        </button>

    </div><!-- /nav-inner -->
</header>

<!-- ═══════════════════════════════════════
        MOBILE MENU
═══════════════════════════════════════ -->
<div id="mobile-menu" aria-hidden="true">
    <div class="mobile-nav-inner">

        <!-- Nav links -->
        <a href="{{ route('inicio') }}"   class="mobile-link">
            <i class="bi bi-house-door"></i> Inicio
        </a>
        <a href="{{ route('ofertas') }}"  class="mobile-link">
            <i class="bi bi-briefcase"></i> Ofertas de empleo
            <span class="badge">Nuevo</span>
        </a>
        <a href="{{ route('nosotros') }}" class="mobile-link">
            <i class="bi bi-info-circle"></i> Nosotros
        </a>

        <div class="mobile-divider"></div>

        <!-- Auth section -->
        @auth
            <div class="mobile-user-card">
                <div class="mobile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div>
                    <p class="mobile-user-name">{{ Auth::user()->names }}</p>
                    <p class="mobile-user-email">{{ Auth::user()->email }}</p>
                </div>
            </div>
            {{-- <a href="{{ route('mi-perfil', auth()->user()) }}" class="mobile-link"> --}}
            <a href="{{ route('profile.edit', auth()->user()) }}" class="mobile-link">
                <i class="bi bi-person-circle"></i> Mi Perfil
            </a>
            <a href="#" class="mobile-link">
                <i class="bi bi-bookmark"></i> Ofertas Guardadas
            </a>
            <a href="#" class="mobile-link">
                <i class="bi bi-bookmark"></i> Ofertas Guardadas
            </a>
            <a href="#" class="mobile-link">
                <i class="bi bi-bookmark"></i> Ofertas Guardadas
            </a>
            <a href="{{ route('admin.usuarios') }}" class="mobile-link">
                <i class="bi bi-people"></i> Usuarios
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="mobile-link" style="width:100%;background:none;border:none;text-align:left;color:#DC2626;">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </button>
            </form>
        @else
            <div class="mobile-auth-btns">
                <a href="{{ route('login') }}"    class="mobile-btn-ghost"><i class="bi bi-box-arrow-in-right"></i> Ingresar</a>
                <a href="{{ route('register') }}" class="mobile-btn-primary"><i class="bi bi-person-plus"></i> Registrarse</a>
            </div>
        @endauth

    </div>
    </div>