<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    {{-- <link rel="icon" type="image/png" sizes="16x16" href="{{ $enterprise->favicon_path }}"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bolsa de Trabajo — Instituto Francisco Vigo Caballero')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts: DM Serif Display + Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* ═══════════════════════════════════════════
           DESIGN TOKENS
        ═══════════════════════════════════════════ */
        :root {
            --ink:        #0F172A;
            --ink-soft:   #1E293B;
            --ink-muted:  #475569;
            --sand:       #F7F4EF;
            --sand-dark:  #EDE8E0;
            --gold:       #B45309;
            --gold-light: #FEF3C7;
            --gold-vivid: #D97706;
            --white:      #FFFFFF;
            --border:     rgba(15,23,42,.10);
            --nav-h:      68px;
        }

        /* ═══════════════════════════════════════════
           BASE
        ═══════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--sand);
            color: var(--ink);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ═══════════════════════════════════════════
           NAVBAR
        ═══════════════════════════════════════════ */
        #site-header {
            position: fixed; top: 0; left: 0; right: 0;
            height: var(--nav-h);
            z-index: 100;
            transition: background .35s ease, box-shadow .35s ease, border-color .35s ease;
        }
        #site-header.scrolled {
            background: rgba(255,255,255,.88);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            box-shadow: 0 1px 0 var(--border), 0 4px 24px rgba(15,23,42,.06);
        }
        #site-header:not(.scrolled) {
            background: rgba(255,255,255,.72);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }

        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            height: var(--nav-h);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        /* — Logo ——————————————————————————————————— */
        .nav-logo {
            display: flex; align-items: center; gap: 11px;
            text-decoration: none;
            flex-shrink: 0;
        }
        .logo-badge {
            width: 40px; height: 40px;
            background: var(--ink);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: background .2s;
        }
        .logo-badge i {
            color: var(--gold-vivid);
            font-size: 20px;
        }
        .nav-logo:hover .logo-badge { background: var(--ink-soft); }
        .logo-name {
            font-family: 'DM Serif Display', serif;
            font-size: 17px;
            line-height: 1.15;
            color: var(--ink);
            display: none;
        }
        .logo-name em { font-style: italic; color: var(--gold); }
        @media (min-width: 640px) { .logo-name { display: block; } }
        @media (min-width: 768px) { .logo-name { font-size: 18px; } }

        /* — Nav Links ———————————————————————————————— */
        .nav-links {
            display: none;
            align-items: center;
            gap: 4px;
        }
        @media (min-width: 768px) { .nav-links { display: flex; } }

        .nav-link {
            position: relative;
            display: flex; align-items: center; gap: 6px;
            padding: 8px 14px;
            font-size: 14px; font-weight: 500;
            color: var(--ink-muted);
            text-decoration: none;
            border-radius: 8px;
            transition: color .2s, background .2s;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 4px; left: 50%; right: 50%;
            height: 2px;
            background: var(--gold-vivid);
            border-radius: 2px;
            transition: left .25s ease, right .25s ease;
        }
        .nav-link:hover { color: var(--ink); background: rgba(15,23,42,.04); }
        .nav-link:hover::after, .nav-link.active::after { left: 14px; right: 14px; }
        .nav-link.active { color: var(--ink); font-weight: 600; }
        .nav-link i { font-size: 15px; }

        /* — Right Side ———————————————————————————————— */
        .nav-right {
            display: none;
            align-items: center;
            gap: 10px;
        }
        @media (min-width: 768px) { .nav-right { display: flex; } }

        .btn-ghost {
            display: flex; align-items: center; gap: 7px;
            padding: 8px 16px;
            font-size: 14px; font-weight: 500;
            color: var(--ink-muted);
            text-decoration: none;
            border-radius: 8px;
            border: 1.5px solid transparent;
            transition: all .2s;
            font-family: 'Outfit', sans-serif;
            cursor: pointer;
            background: transparent;
        }
        .btn-ghost:hover {
            color: var(--ink);
            background: rgba(15,23,42,.05);
            border-color: var(--border);
        }

        .btn-primary {
            display: flex; align-items: center; gap: 7px;
            padding: 8px 18px;
            font-size: 14px; font-weight: 600;
            color: var(--white);
            background: var(--ink);
            text-decoration: none;
            border-radius: 8px;
            border: 1.5px solid var(--ink);
            transition: all .2s;
            font-family: 'Outfit', sans-serif;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: var(--ink-soft);
            border-color: var(--ink-soft);
            box-shadow: 0 4px 16px rgba(15,23,42,.18);
            transform: translateY(-1px);
        }

        /* — User Dropdown ——————————————————————————— */
        .user-trigger {
            display: flex; align-items: center; gap: 9px;
            padding: 6px 14px 6px 8px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            cursor: pointer;
            background: var(--white);
            transition: all .2s;
            font-family: 'Outfit', sans-serif;
        }
        .user-trigger:hover { border-color: rgba(15,23,42,.22); box-shadow: 0 2px 8px rgba(15,23,42,.07); }
        .user-avatar {
            width: 30px; height: 30px;
            background: var(--ink);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: var(--gold-vivid);
            font-size: 14px; font-weight: 700;
            font-family: 'DM Serif Display', serif;
            flex-shrink: 0;
        }
        .user-name { font-size: 14px; font-weight: 500; color: var(--ink); }
        .user-chevron { font-size: 11px; color: var(--ink-muted); transition: transform .2s; }

        .dropdown-menu {
            position: absolute; top: calc(100% + 8px); right: 0;
            width: 220px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 8px 40px rgba(15,23,42,.12), 0 2px 8px rgba(15,23,42,.06);
            padding: 6px;
            opacity: 0; pointer-events: none; transform: translateY(-6px);
            transition: all .22s ease;
        }
        .dropdown-wrap:hover .dropdown-menu,
        .dropdown-wrap:focus-within .dropdown-menu {
            opacity: 1; pointer-events: auto; transform: translateY(0);
        }
        .dropdown-wrap:hover .user-chevron { transform: rotate(180deg); }
        .dropdown-wrap { position: relative; }
        .dropdown-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: 9px;
            font-size: 14px; font-weight: 500;
            color: var(--ink-muted);
            text-decoration: none;
            transition: all .15s;
            cursor: pointer; background: transparent; border: none; width: 100%;
            font-family: 'Outfit', sans-serif;
            text-align: left;
        }
        .dropdown-item:hover { background: var(--sand); color: var(--ink); }
        .dropdown-item.danger:hover { background: #FEF2F2; color: #DC2626; }
        .dropdown-divider { height: 1px; background: var(--border); margin: 5px 8px; }

        /* — Hamburger ——————————————————————————————— */
        .hamburger {
            display: flex; align-items: center; justify-content: center;
            width: 42px; height: 42px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: var(--white);
            cursor: pointer;
            color: var(--ink);
            font-size: 20px;
            transition: all .2s;
        }
        .hamburger:hover { border-color: var(--ink-muted); background: var(--sand); }
        @media (min-width: 768px) { .hamburger { display: none; } }

        /* ═══════════════════════════════════════════
           MOBILE MENU
        ═══════════════════════════════════════════ */
        #mobile-menu {
            position: fixed;
            top: var(--nav-h); left: 0; right: 0;
            z-index: 99;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 12px 40px rgba(15,23,42,.12);
            transform: translateY(-8px);
            opacity: 0;
            pointer-events: none;
            transition: transform .3s cubic-bezier(.4,0,.2,1), opacity .3s ease;
        }
        #mobile-menu.open {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        .mobile-nav-inner {
            max-width: 1280px; margin: 0 auto;
            padding: 16px 20px 20px;
        }
        .mobile-link {
            display: flex; align-items: center; gap: 12px;
            padding: 13px 16px;
            border-radius: 12px;
            font-size: 15px; font-weight: 500;
            color: var(--ink-muted);
            text-decoration: none;
            transition: all .2s;
        }
        .mobile-link:hover { background: var(--sand); color: var(--ink); }
        .mobile-link i { font-size: 18px; width: 22px; text-align: center; }
        .mobile-link .badge {
            margin-left: auto;
            font-size: 11px; font-weight: 600;
            background: var(--gold-light);
            color: var(--gold);
            padding: 2px 8px;
            border-radius: 99px;
        }
        .mobile-divider { height: 1px; background: var(--border); margin: 10px 0; }
        .mobile-user-card {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 16px;
            background: var(--sand);
            border-radius: 14px;
            margin-bottom: 8px;
        }
        .mobile-avatar {
            width: 44px; height: 44px;
            background: var(--ink);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--gold-vivid);
            font-size: 18px; font-weight: 700;
            font-family: 'DM Serif Display', serif;
            flex-shrink: 0;
        }
        .mobile-user-name { font-size: 15px; font-weight: 600; color: var(--ink); }
        .mobile-user-email { font-size: 12px; color: var(--ink-muted); margin-top: 1px; }
        .mobile-auth-btns { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 4px 0; }
        .mobile-btn-ghost {
            display: flex; align-items: center; justify-content: center; gap: 7px;
            padding: 12px; border-radius: 12px;
            font-size: 14px; font-weight: 600;
            color: var(--ink);
            text-decoration: none;
            border: 1.5px solid var(--border);
            transition: all .2s;
            font-family: 'Outfit', sans-serif;
        }
        .mobile-btn-ghost:hover { background: var(--sand); }
        .mobile-btn-primary {
            display: flex; align-items: center; justify-content: center; gap: 7px;
            padding: 12px; border-radius: 12px;
            font-size: 14px; font-weight: 600;
            color: var(--white);
            background: var(--ink);
            text-decoration: none;
            border: 1.5px solid var(--ink);
            transition: all .2s;
            font-family: 'Outfit', sans-serif;
        }
        .mobile-btn-primary:hover { background: var(--ink-soft); }

        /* ═══════════════════════════════════════════
           MAIN
        ═══════════════════════════════════════════ */
        #main-content {
            padding-top: var(--nav-h);
            min-height: 100vh;
        }

        /* ═══════════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════════ */
        #site-footer {
            background: var(--ink);
            color: rgba(255,255,255,.55);
            position: relative;
            overflow: hidden;
        }
        #site-footer::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold-vivid) 0%, #F59E0B 40%, transparent 100%);
        }

        .footer-grid {
            max-width: 1280px;
            margin: 0 auto;
            padding: 56px 24px 40px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
        }
        @media (min-width: 640px) {
            .footer-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (min-width: 1024px) {
            .footer-grid { grid-template-columns: 2fr 1fr 1fr 1fr; gap: 32px; }
        }

        .footer-brand-badge {
            display: inline-flex; align-items: center; gap: 10px;
            margin-bottom: 16px;
        }
        .footer-icon {
            width: 42px; height: 42px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            color: var(--gold-vivid);
        }
        .footer-title {
            font-family: 'DM Serif Display', serif;
            font-size: 18px;
            color: var(--white);
            line-height: 1.2;
        }
        .footer-title em { font-style: italic; color: var(--gold-vivid); }
        .footer-desc {
            font-size: 14px;
            line-height: 1.7;
            color: rgba(255,255,255,.45);
            max-width: 320px;
        }

        .footer-tagline {
            display: inline-flex; align-items: center; gap: 6px;
            margin-top: 16px;
            padding: 6px 12px;
            background: rgba(217,119,6,.15);
            border: 1px solid rgba(217,119,6,.25);
            border-radius: 99px;
            font-size: 12px; font-weight: 600;
            color: var(--gold-vivid);
            letter-spacing: .03em;
        }

        .footer-col-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255,255,255,.35);
            margin-bottom: 16px;
        }
        .footer-link {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 0;
            font-size: 14px;
            color: rgba(255,255,255,.5);
            text-decoration: none;
            transition: color .2s;
        }
        .footer-link:hover { color: var(--white); }
        .footer-link i { font-size: 13px; width: 16px; }

        .footer-contact-item {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 5px 0;
            font-size: 14px;
            color: rgba(255,255,255,.5);
        }
        .footer-contact-icon {
            width: 28px; height: 28px;
            background: rgba(255,255,255,.06);
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            color: var(--gold-vivid);
            flex-shrink: 0;
            margin-top: 1px;
        }

        .social-grid { display: flex; flex-wrap: wrap; gap: 8px; }
        .social-btn {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            text-decoration: none;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            color: rgba(255,255,255,.5);
            transition: all .25s;
        }
        .social-btn:hover { transform: translateY(-3px); }
        .social-btn.fb:hover  { background:#1877F2; border-color:#1877F2; color:#fff; }
        .social-btn.tw:hover  { background:#111827; border-color:#374151; color:#fff; }
        .social-btn.li:hover  { background:#0A66C2; border-color:#0A66C2; color:#fff; }
        .social-btn.ig:hover  { background: linear-gradient(135deg,#F58529,#DD2A7B,#8134AF); border-color:transparent; color:#fff; }
        .social-btn.wa:hover  { background:#25D366; border-color:#25D366; color:#fff; }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .footer-bottom-inner {
            max-width: 1280px; margin: 0 auto;
            padding: 20px 24px;
            display: flex; flex-wrap: wrap; align-items: center;
            justify-content: space-between; gap: 12px;
        }
        .footer-copy {
            font-size: 13px;
            color: rgba(255,255,255,.3);
        }
        .footer-legal {
            display: flex; gap: 20px;
        }
        .footer-legal a {
            font-size: 13px;
            color: rgba(255,255,255,.3);
            text-decoration: none;
            transition: color .2s;
        }
        .footer-legal a:hover { color: rgba(255,255,255,.6); }

        /* ═══════════════════════════════════════════
           UTILITIES
        ═══════════════════════════════════════════ */
        .hero-gradient {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        }
        .card-hover {
            transition: transform .3s ease, box-shadow .3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(15,23,42,.1);
        }

        /* Page-load animation */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .nav-inner { animation: fadeUp .5s ease both; }
    </style>

    @stack('styles')
</head>
<body>

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
                    {{ $enterprise->trade_name ?? 'IESTP Francisco Vigo Caballero' }}
                </span>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="nav-links">
                <a href="{{ route('inicio') }}"   class="nav-link {{ request()->routeIs('inicio')   ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i> Inicio
                </a>
                <a href="{{ route('ofertas') }}"  class="nav-link {{ request()->routeIs('ofertas')  ? 'active' : '' }}">
                    <i class="bi bi-briefcase"></i> Ofertas
                </a>
                <a href="{{ route('nosotros') }}" class="nav-link {{ request()->routeIs('nosotros') ? 'active' : '' }}">
                    <i class="bi bi-info-circle"></i> Nosotros
                </a>
            </nav>

            <!-- Desktop Right Actions -->
            <div class="nav-right">
                @auth
                    <div class="dropdown-wrap">
                        <button class="user-trigger" type="button" aria-haspopup="true">
                            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down user-chevron"></i>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a href="#" class="dropdown-item" role="menuitem">
                                <i class="bi bi-person-circle" style="font-size:16px;color:var(--ink-muted)"></i>
                                Mi Perfil
                            </a>
                            <a href="#" class="dropdown-item" role="menuitem">
                                <i class="bi bi-bookmark" style="font-size:16px;color:var(--ink-muted)"></i>
                                Guardados
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

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
                    Formando profesionales técnicos desde hace décadas. Elige entre 5 carreras profesionales y obtén tu Título de Técnico a Nombre de la Nación en solo 3 años de formación 100% práctica.
                </p>
                <div class="footer-tagline">
                    <i class="bi bi-patch-check-fill"></i>
                    Institución en proceso de licenciamiento
                </div>
            </div>

            <!-- Col 2: Quick links -->
            <div>
                <p class="footer-col-title">Navegación</p>
                <a href="{{ route('inicio') }}"   class="footer-link"> Inicio</a>
                <a href="{{ route('ofertas') }}"  class="footer-link"> Ofertas de empleo</a>
                <a href="{{ route('nosotros') }}" class="footer-link"> Sobre nosotros</a>
                @guest
                    <a href="{{ route('login') }}"    class="footer-link"> Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="footer-link"> Registrarse</a>
                @endguest
            </div>

            <!-- Col 3: Contact -->
            <div>
                <p class="footer-col-title">Contacto</p>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <span>{{ $enterprise->address ?? 'Av. Principal 123' }}, {{ $enterprise->city ?? 'Trujillo' }}</span>
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
                    <a href="{{ $enterprise->facebook_link  ?? '#' }}" class="social-btn fb" target="_blank" rel="noopener" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="{{ $enterprise->twitter_link   ?? '#' }}" class="social-btn tw" target="_blank" rel="noopener" aria-label="Twitter/X">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="{{ $enterprise->linkedin_link  ?? '#' }}" class="social-btn li" target="_blank" rel="noopener" aria-label="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="{{ $enterprise->instagram_link ?? '#' }}" class="social-btn ig" target="_blank" rel="noopener" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    @if($enterprise->whatsapp_link ?? false)
                    <a href="{{ $enterprise->whatsapp_link }}" class="social-btn wa" target="_blank" rel="noopener" aria-label="WhatsApp">
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

    <!-- ═══════════════════════════════════════
         SCRIPTS
    ═══════════════════════════════════════ -->
    <script>
    /* ── Navbar scroll effect ──────────────────────────── */
    const header = document.getElementById('site-header');
    const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 10);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    /* ── Mobile menu toggle ────────────────────────────── */
    const hamburger    = document.getElementById('hamburgerBtn');
    const mobileMenu   = document.getElementById('mobile-menu');
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