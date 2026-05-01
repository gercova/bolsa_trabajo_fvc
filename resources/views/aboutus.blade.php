@extends('layouts.app')

@section('title', 'Nosotros — Instituto Francisco Vigo Caballero')

@section('content')
<!-- ═══════════════════════════════════════════
     HERO
═══════════════════════════════════════════ -->
<section class="hero-gradient text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22%22 fill-opacity=%221%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="max-w-7xl mx-auto px-6 py-20 md:py-28 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold tracking-wider uppercase mb-6" style="background: rgba(217,119,6,.15); color: var(--gold-vivid); border: 1px solid rgba(217,119,6,.25);">
                <i class="bi bi-buildings text-sm"></i> Conócenos
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight" style="font-family: 'DM Serif Display', serif;">
                Formamos el <em style="font-style: italic; color: var(--gold-vivid);">futuro técnico</em> del Perú
            </h1>
            <p class="text-lg md:text-xl leading-relaxed max-w-2xl mx-auto" style="color: rgba(255,255,255,.65);">
                Más de tres décadas formando profesionales técnicos de excelencia, conectando talento con las mejores oportunidades laborales del país.
            </p>
            <div class="flex flex-wrap justify-center gap-8 mt-10">
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold" style="font-family: 'DM Serif Display', serif; color: var(--gold-vivid);">35+</div>
                    <p class="text-sm mt-1" style="color: rgba(255,255,255,.55);">Años de trayectoria</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold" style="font-family: 'DM Serif Display', serif; color: var(--gold-vivid);">5,000+</div>
                    <p class="text-sm mt-1" style="color: rgba(255,255,255,.55);">Egresados</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold" style="font-family: 'DM Serif Display', serif; color: var(--gold-vivid);">{{ $partners->count() }}+</div>
                    <p class="text-sm mt-1" style="color: rgba(255,255,255,.55);">Empresas aliadas</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════
     QUIÉNES SOMOS
═══════════════════════════════════════════ -->
<section class="py-20 md:py-28" style="background: var(--white);">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Texto -->
            <div>
                <span class="inline-flex items-center gap-2 text-sm font-semibold tracking-wider uppercase mb-4" style="color: var(--gold); letter-spacing: .08em;">
                    <span style="display:inline-block;width:32px;height:2px;background:var(--gold-vivid);border-radius:2px;"></span> Nuestra historia
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 leading-tight" style="font-family: 'DM Serif Display', serif; color: var(--ink);">
                    Instituto de Educación Superior Tecnológico Público<br>
                    <em style="font-style: italic; color: var(--gold);">Francisco Vigo Caballero</em>
                </h2>
                <p class="mb-4 leading-relaxed" style="color: var(--ink-muted);">
                    Fundado en 1991, nos hemos consolidado como una institución líder en formación técnica profesional, 
                    comprometida con la excelencia académica y la inserción laboral de nuestros estudiantes.
                </p>
                <p class="leading-relaxed" style="color: var(--ink-muted);">
                    Nuestra bolsa de trabajo nace con el objetivo de conectar a nuestros estudiantes y egresados con las 
                    mejores oportunidades laborales del mercado, estableciendo alianzas estratégicas con empresas de diversos sectores.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('ofertas') }}" class="btn-primary" style="text-decoration:none;">
                        <i class="bi bi-briefcase"></i> Ver ofertas laborales
                    </a>
                    <a href="#valores" class="btn-ghost" style="text-decoration:none; border-color: var(--border);">
                        <i class="bi bi-arrow-down"></i> Nuestros valores
                    </a>
                </div>
            </div>

            <!-- Cita del Director -->
            <div class="rounded-2xl p-8 md:p-10 relative overflow-hidden" style="background: var(--ink);">
                <div class="absolute top-0 right-0 w-32 h-32 opacity-[0.06] pointer-events-none" style="background: var(--gold-vivid); border-radius: 0 0 0 100%;"></div>
                <i class="bi bi-quote text-6xl opacity-20" style="color: var(--gold-vivid);"></i>
                <blockquote class="text-xl md:text-2xl italic mb-6 leading-relaxed relative z-10" style="font-family: 'DM Serif Display', serif; color: rgba(255,255,255,.9);">
                    &ldquo;Formamos profesionales competentes y éticos, listos para enfrentar los desafíos del mundo laboral actual.&rdquo;
                </blockquote>
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg font-bold" style="background: var(--gold-vivid); color: var(--white); font-family: 'DM Serif Display', serif;">
                        TG
                    </div>
                    <div>
                        <p class="font-semibold text-white">Mg. Teodorico Ganoza Medina</p>
                        <p class="text-sm" style="color: rgba(255,255,255,.5);">Director General</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════
     VALORES
═══════════════════════════════════════════ -->
<section id="valores" class="py-20 md:py-28" style="background: var(--sand);">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <span class="inline-flex items-center gap-2 text-sm font-semibold tracking-wider uppercase mb-4" style="color: var(--gold); letter-spacing: .08em;">
                <span style="display:inline-block;width:32px;height:2px;background:var(--gold-vivid);border-radius:2px;"></span> Lo que nos guía
            </span>
            <h2 class="text-3xl md:text-4xl font-bold" style="font-family: 'DM Serif Display', serif; color: var(--ink);">
                Nuestros Valores
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Excelencia -->
            <div class="bg-white rounded-2xl p-8 text-center card-hover border border-transparent hover:border-[var(--border)]">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl" style="background: var(--gold-light); color: var(--gold-vivid);">
                    <i class="bi bi-star-fill"></i>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--ink);">Excelencia</h3>
                <p class="text-sm leading-relaxed" style="color: var(--ink-muted);">Buscamos la mejora continua en todos nuestros procesos formativos.</p>
            </div>

            <!-- Compromiso -->
            <div class="bg-white rounded-2xl p-8 text-center card-hover border border-transparent hover:border-[var(--border)]">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl" style="background: var(--gold-light); color: var(--gold-vivid);">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--ink);">Compromiso</h3>
                <p class="text-sm leading-relaxed" style="color: var(--ink-muted);">Dedicación total con nuestros estudiantes y egresados.</p>
            </div>

            <!-- Integridad -->
            <div class="bg-white rounded-2xl p-8 text-center card-hover border border-transparent hover:border-[var(--border)]">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl" style="background: var(--gold-light); color: var(--gold-vivid);">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--ink);">Integridad</h3>
                <p class="text-sm leading-relaxed" style="color: var(--ink-muted);">Actuamos con ética, transparencia y responsabilidad.</p>
            </div>

            <!-- Innovación -->
            <div class="bg-white rounded-2xl p-8 text-center card-hover border border-transparent hover:border-[var(--border)]">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl" style="background: var(--gold-light); color: var(--gold-vivid);">
                    <i class="bi bi-lightbulb-fill"></i>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--ink);">Innovación</h3>
                <p class="text-sm leading-relaxed" style="color: var(--ink-muted);">Nos adaptamos a las nuevas tendencias educativas y tecnológicas.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════
     EQUIPO DIRECTIVO
═══════════════════════════════════════════ -->
<section class="py-20 md:py-28" style="background: var(--white);">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <span class="inline-flex items-center gap-2 text-sm font-semibold tracking-wider uppercase mb-4" style="color: var(--gold); letter-spacing: .08em;">
                <span style="display:inline-block;width:32px;height:2px;background:var(--gold-vivid);border-radius:2px;"></span> Liderazgo
            </span>
            <h2 class="text-3xl md:text-4xl font-bold" style="font-family: 'DM Serif Display', serif; color: var(--ink);">
                Nuestro Equipo Directivo
            </h2>
            <p class="mt-3 max-w-xl mx-auto" style="color: var(--ink-muted);">
                Profesionales con amplia trayectoria que guían nuestra institución hacia la excelencia.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @if ($director)
            <div class="bg-white rounded-2xl p-8 text-center card-hover border" style="border-color: var(--border);">
                <div class="w-28 h-28 rounded-2xl mx-auto mb-5 overflow-hidden" style="background: var(--sand);">
                    <img src="{{ Storage::url($director->photo_profile) }}" alt="{{ $director->names }}" class="w-full h-full object-cover">
                </div>
                <h3 class="text-xl font-bold mb-1" style="font-family: 'DM Serif Display', serif; color: var(--ink);">{{ $director->names }}</h3>
                <p class="text-sm font-semibold mb-3" style="color: var(--gold-vivid);">{{ $director->job_position }}</p>
                <p class="text-sm leading-relaxed" style="color: var(--ink-muted);">Más de 20 años de experiencia en educación superior tecnológica.</p>
                <div class="mt-5 flex justify-center gap-3">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm" style="background: var(--sand); color: var(--ink-muted);"><i class="bi bi-linkedin"></i></span>
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm" style="background: var(--sand); color: var(--ink-muted);"><i class="bi bi-envelope"></i></span>
                </div>
            </div>
            @endif

            @if ($jua)
            <div class="bg-white rounded-2xl p-8 text-center card-hover border" style="border-color: var(--border);">
                <div class="w-28 h-28 rounded-2xl mx-auto mb-5 overflow-hidden" style="background: var(--sand);">
                    <img src="{{ Storage::url($jua->photo_profile) }}" alt="{{ $jua->names }}" class="w-full h-full object-cover">
                </div>
                <h3 class="text-xl font-bold mb-1" style="font-family: 'DM Serif Display', serif; color: var(--ink);">{{ $jua->names }}</h3>
                <p class="text-sm font-semibold mb-3" style="color: var(--gold-vivid);">{{ $jua->job_position }}</p>
                <p class="text-sm leading-relaxed" style="color: var(--ink-muted);">Más de 20 años de experiencia en educación superior tecnológica.</p>
                <div class="mt-5 flex justify-center gap-3">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm" style="background: var(--sand); color: var(--ink-muted);"><i class="bi bi-linkedin"></i></span>
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm" style="background: var(--sand); color: var(--ink-muted);"><i class="bi bi-envelope"></i></span>
                </div>
            </div>
            @endif

            @if ($be)
            <div class="bg-white rounded-2xl p-8 text-center card-hover border" style="border-color: var(--border);">
                <div class="w-28 h-28 rounded-2xl mx-auto mb-5 overflow-hidden" style="background: var(--sand);">
                    <img src="{{ Storage::url($be->photo_profile) }}" alt="{{ $be->names }}" class="w-full h-full object-cover">
                </div>
                <h3 class="text-xl font-bold mb-1" style="font-family: 'DM Serif Display', serif; color: var(--ink);">{{ $be->names }}</h3>
                <p class="text-sm font-semibold mb-3" style="color: var(--gold-vivid);">{{ $be->job_position }}</p>
                <p class="text-sm leading-relaxed" style="color: var(--ink-muted);">Más de 20 años de experiencia en educación superior tecnológica.</p>
                <div class="mt-5 flex justify-center gap-3">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm" style="background: var(--sand); color: var(--ink-muted);"><i class="bi bi-linkedin"></i></span>
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm" style="background: var(--sand); color: var(--ink-muted);"><i class="bi bi-envelope"></i></span>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════
     CTA FINAL
═══════════════════════════════════════════ -->
<section class="py-20 md:py-28 hero-gradient relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22%22 fill-opacity=%221%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 text-white" style="font-family: 'DM Serif Display', serif;">
            ¿Listo para formar parte de nuestra <em style="font-style: italic; color: var(--gold-vivid);">comunidad</em>?
        </h2>
        <p class="text-lg mb-8" style="color: rgba(255,255,255,.6);">
            Postula a nuestras ofertas laborales y da el siguiente paso en tu carrera profesional.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-200 hover:-translate-y-0.5" style="background: var(--gold-vivid); color: var(--white); box-shadow: 0 4px 20px rgba(217,119,6,.35);">
                <i class="bi bi-person-plus"></i> Registrarme ahora
            </a>
            <a href="{{ route('ofertas') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-200 border" style="color: var(--white); border-color: rgba(255,255,255,.25);">
                <i class="bi bi-briefcase"></i> Explorar ofertas
            </a>
        </div>
    </div>
</section>
@endsection