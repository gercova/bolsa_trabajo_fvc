<header id="site-header">
    <div class="nav-inner">
        <a href="{{ route('inicio') }}" class="nav-logo">
            <div class="logo-badge">
                {{-- <i class="bi bi-mortarboard-fill"></i> --}}
                <img src="{{ $enterprise->logo_path }}" style="background: #ffff" alt="{{ $enterprise->tarde_name }}">

            </div>
            <span class="logo-name whitespace-nowrap">
                {{ $enterprise->trade_name }}
            </span>
        </a>

        <nav class="nav-links hidden md:flex items-center lg:gap-1 xl:gap-2">
            <a href="{{ route('inicio') }}"
                class="nav-link whitespace-nowrap {{ request()->routeIs('inicio') ? 'active' : '' }}">
                Inicio
            </a>

            <div class="dropdown-wrap h-full flex items-center">
                <button type="button"
                    class="nav-link bg-transparent border-none cursor-pointer whitespace-nowrap flex items-center gap-1.5">
                    Admisión y Matrícula <i class="bi bi-chevron-down text-[10px]"></i>
                </button>
                <div class="dropdown-menu !top-[80%] !left-0 !right-auto w-[220px]">
                    <a href="#" class="dropdown-item">CEPRE FVC</a>
                    <a href="#" class="dropdown-item">Examen de Admisión</a>
                    <a href="#" class="dropdown-item">Matrículas</a>
                    <a href="#" class="dropdown-item">Becas y Créditos</a>
                </div>
            </div>

            <a href="{{ route('programas-de-estudio') }}"
                class="nav-link whitespace-nowrap {{ request()->routeIs('ofertas') ? 'active' : '' }}">
                Programas de Estudio
            </a>

            <div class="dropdown-wrap h-full flex items-center">
                <button type="button"
                    class="nav-link bg-transparent border-none cursor-pointer whitespace-nowrap flex items-center gap-1.5">
                    Transparencia <i class="bi bi-chevron-down text-[10px]"></i>
                </button>
                <div class="dropdown-menu !top-[80%] !left-0 !right-auto w-[240px]">
                    <a href="#" class="dropdown-item">Documentos de gestión</a>
                    <a href="#" class="dropdown-item">Estadísticas</a>
                    <a href="#" class="dropdown-item">Inversión y gestión</a>
                    <a href="#" class="dropdown-item">Licenciamiento</a>
                    <a href="#" class="dropdown-item">Libro de reclamaciones</a>
                </div>
            </div>

            <div class="dropdown-wrap h-full flex items-center">
                <button type="button"
                    class="nav-link bg-transparent border-none cursor-pointer whitespace-nowrap flex items-center gap-1.5">
                    Trámites <i class="bi bi-chevron-down text-[10px]"></i>
                </button>
                <div class="dropdown-menu !top-[80%] !left-0 !right-auto w-[200px]">
                    <a href="#" class="dropdown-item">Mesa de Partes</a>
                    <a href="#" class="dropdown-item">TUPA</a>
                </div>
            </div>

            <div class="dropdown-wrap h-full flex items-center">
                <button type="button"
                    class="nav-link bg-transparent border-none cursor-pointer whitespace-nowrap flex items-center gap-1.5 {{ request()->routeIs('nosotros') ? 'active' : '' }}">
                    Nosotros <i class="bi bi-chevron-down text-[10px]"></i>
                </button>
                <div class="dropdown-menu !top-[80%] !left-0 !right-auto w-[240px]">
                    <a href="{{ route('quienes-somos') }}" class="dropdown-item">¿Quiénes somos?</a>
                    <a href="{{ route('historia') }}" class="dropdown-item">Reseña Histórica</a>
                    <a href="{{ route('organigrama-institucional') }}" class="dropdown-item">Organigrama Institucional</a>
                    <a href="{{ route('plana-jerarquica') }}" class="dropdown-item">Plana Jerárquica</a>
                    <a href="{{ route('plana-de-docentes') }}" class="dropdown-item">Plana Docente</a>
                    <a href="{{ route('plana-administrativa') }}" class="dropdown-item">Plana Administrativa</a>
                    <a href="{{ route('consejo-de-estudiantes') }}" class="dropdown-item">Consejo de Estudiantes</a>
                    <a href="{{ route('locales') }}" class="dropdown-item">Locales</a>
                </div>
            </div>

            <div class="dropdown-wrap h-full flex items-center">
                <button type="button"
                    class="nav-link bg-transparent border-none cursor-pointer whitespace-nowrap flex items-center gap-1.5 {{ request()->routeIs('servicios') ? 'active' : '' }}">
                    Servicios <i class="bi bi-chevron-down text-[10px]"></i>
                </button>
                <div class="dropdown-menu !top-[80%] !left-0 !right-auto w-[240px]">
                    <a href="https://iestpfranciscovigocaballero.bibliotecalatina.com/login" target="_blank"
                        class="dropdown-item">Biblioteca Virtual</a>
                    <a href="{{ route('servicios.ofertas') }}" class="dropdown-item">Bolsa de Trabajo</a>
                </div>
            </div>
        </nav>

        <div class="nav-right hidden md:flex items-center gap-2">
            @auth
                <div class="dropdown-wrap">
                    <button class="user-trigger flex items-center" type="button" aria-haspopup="true">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->names, 0, 1)) }}</div>
                        <span
                            class="user-name whitespace-nowrap truncate max-w-[180px]">{{ explode(' ', Auth::user()->names)[0] }}</span>
                        <i class="bi bi-chevron-down user-chevron ml-1"></i>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        <a href="{{ route('admin.profile.edit', auth()->user()) }}" class="dropdown-item" role="menuitem">
                            <i class="bi bi-person-circle" style="font-size:16px;color:var(--ink-muted)"></i> Mi Perfil
                        </a>
                        <a href="#" class="dropdown-item" role="menuitem">
                            <i class="bi bi-bookmark" style="font-size:16px;color:var(--ink-muted)"></i> Gestionar puesto
                        </a>
                        <a href="{{ route('admin.enterprise.edit') }}" class="dropdown-item" role="menuitem">
                            <i class="bi bi-buildings"></i> Datos de empresa
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="dropdown-item" role="menuitem">
                            <i class="bi bi-people"></i> Usuarios
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger" role="menuitem">
                                <i class="bi bi-box-arrow-right" style="font-size:16px;color:#DC2626"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-ghost whitespace-nowrap">
                    <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                </a>
                <a href="{{ route('register') }}" class="btn-primary whitespace-nowrap">
                    <i class="bi bi-person-plus"></i> Registrarse
                </a>
            @endauth
        </div>

        <button class="hamburger flex md:hidden" id="hamburgerBtn" aria-label="Abrir menú" aria-expanded="false">
            <i class="bi bi-list" id="hamburgerIcon"></i>
        </button>

    </div>
</header>

<div id="mobile-menu" aria-hidden="true" class="overflow-y-auto max-h-[calc(100vh-var(--nav-h))] md:hidden">
    <div class="mobile-nav-inner flex flex-col gap-1">

        <a href="{{ route('inicio') }}" class="mobile-link">
            <i class="bi bi-house-door"></i> Inicio
        </a>

        <div x-data="{ open: false }" class="w-full">
            <button @click="open = !open"
                class="mobile-link w-full bg-transparent border-none flex justify-between items-center cursor-pointer">
                <div class="flex items-center gap-[12px]">
                    <i class="bi bi-journal-bookmark w-[22px] text-center text-[18px]"></i> Admisión y Matrícula
                </div>
                <i class="bi bi-chevron-down transition-transform duration-200 text-[14px]"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" style="display: none;" class="pl-[46px] pr-4 py-2 flex flex-col gap-3">
                <a href="{{ route('cepre-fvc') }}"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">CEPRE
                    FVC</a>
                <a href="{{ route('examen-de-admision') }}"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Examen
                    de Admisión</a>
                <a href="{{ route('matriculas') }}"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Matrículas</a>
                <a href="{{ route('becas-y-creditos') }}"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Becas
                    y Créditos</a>
            </div>
        </div>

        <a href="{{ route('programas-de-estudio') }}" class="mobile-link">
            <i class="bi bi-briefcase"></i> Programas de Estudio
        </a>

        <div x-data="{ open: false }" class="w-full">
            <button @click="open = !open"
                class="mobile-link w-full bg-transparent border-none flex justify-between items-center cursor-pointer">
                <div class="flex items-center gap-[12px]">
                    <i class="bi bi-search w-[22px] text-center text-[18px]"></i> Transparencia
                </div>
                <i class="bi bi-chevron-down transition-transform duration-200 text-[14px]"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" style="display: none;" class="pl-[46px] pr-4 py-2 flex flex-col gap-3">
                <a href="#"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Documentos
                    de gestión</a>
                <a href="#"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Estadísticas</a>
                <a href="#"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Inversión
                    y gestión</a>
                <a href="#"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Licenciamiento</a>
                <a href="#"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Libro
                    de reclamaciones</a>
            </div>
        </div>

        <div x-data="{ open: false }" class="w-full">
            <button @click="open = !open"
                class="mobile-link w-full bg-transparent border-none flex justify-between items-center cursor-pointer">
                <div class="flex items-center gap-[12px]">
                    <i class="bi bi-file-earmark-text w-[22px] text-center text-[18px]"></i> Trámites
                </div>
                <i class="bi bi-chevron-down transition-transform duration-200 text-[14px]"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" style="display: none;" class="pl-[46px] pr-4 py-2 flex flex-col gap-3">
                <a href="#"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Mesa
                    de Partes</a>
                <a href="#"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">TUPA</a>
            </div>
        </div>

        <div x-data="{ open: false }" class="w-full">
            <button @click="open = !open"
                class="mobile-link w-full bg-transparent border-none flex justify-between items-center cursor-pointer">
                <div class="flex items-center gap-[12px]">
                    <i class="bi bi-info-circle w-[22px] text-center text-[18px]"></i> Nosotros
                </div>
                <i class="bi bi-chevron-down transition-transform duration-200 text-[14px]"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" style="display: none;" class="pl-[46px] pr-4 py-2 flex flex-col gap-3">
                <a href="{{ route('historia') }}"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Reseña
                    Histórica</a>
                <a href="{{ route('organigrama-institucional') }}"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Organigrama
                    Institucional</a>
                <a href="{{ route('plana-jerarquica') }}"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Plana
                    Jerárquica</a>
                <a href="{{ route('plana-de-docentes') }}"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Plana
                    Docente</a>
                <a href="#"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Plana
                    Administrativa</a>
                <a href="#"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Consejo
                    de Estudiantes</a>
                <a href="#"
                    class="text-[14px] font-medium text-[var(--ink-muted)] hover:text-[var(--ink)] transition-colors">Locales</a>
            </div>
        </div>

        <div class="mobile-divider"></div>

        @auth
            <div class="mobile-user-card">
                {{-- <div class="mobile-avatar">{{ explode(" ", Auth::user()->names)[0]; }}</div> --}}
                <div>
                    <p class="mobile-user-name">{{ explode(' ', Auth::user()->names)[0] }}</p>
                    <p class="mobile-user-email">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <a href="{{ route('admin.profile.edit', auth()->user()) }}" class="mobile-link">
                <i class="bi bi-person-circle"></i> Mi Perfil
            </a>
            <a href="#" class="mobile-link">
                <i class="bi bi-bookmark"></i> Ofertas Guardadas
            </a>
            <a href="{{ route('admin.users.index') }}" class="mobile-link">
                <i class="bi bi-people"></i> Usuarios
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="mobile-link w-full bg-transparent border-none text-left text-[#DC2626] cursor-pointer">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </button>
            </form>
        @else
            <div class="mobile-auth-btns">
                <a href="{{ route('login') }}" class="mobile-btn-ghost"><i class="bi bi-box-arrow-in-right"></i>
                    Ingresar</a>
                <a href="{{ route('register') }}" class="mobile-btn-primary"><i class="bi bi-person-plus"></i>
                    Registrarse</a>
            </div>
        @endauth

    </div>
</div>
