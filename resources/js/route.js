/**
 * Helper de rutas nombradas institucional para la SPA de Inertia.
 * Evita ReferenceError cuando los componentes o vistas invocan route('...').
 */
export const routesMap = {
    'login': '/login',
    'register': '/register',
    'register.verify-dni': '/register/verify-dni',
    'password.request': '/forgot-password',
    'password.email': '/forgot-password',
    'password.reset': '/reset-password',
    'password.store': '/reset-password',
    'logout': '/logout',
    'inicio': '/',
    'home': '/',
    'mesa-de-partes': '/tramites/mesa-de-partes',
    'tupa': '/tramites/tupa',
    'bolsa-de-trabajo': '/servicios/bolsa-de-trabajo',
    'enlaces-institucionales': '/servicios/enlaces-institucionales',
    'programas-de-estudio': '/programas-de-estudios',
    'programas-de-estudios': '/programas-de-estudios',
    'programas-de-estudio.detalle': '/programas-de-estudios/{program}',
    'quienes-somos': '/nosotros/quienes-somos',
    'historia': '/nosotros/historia',
    'organigrama-institucional': '/nosotros/organigrama-institucional',
    'plana-jerarquica': '/nosotros/plana-jerarquica',
    'plana-administrativa': '/nosotros/plana-administrativa',
    'plana-de-docentes': '/nosotros/plana-de-docentes',
    'locales': '/nosotros/locales',
    'consejo-de-estudiantes': '/nosotros/consejo-de-estudiantes',
    'cepre-fvc': '/admision-y-matricula/cepre-fvc',
    'examen-de-admision': '/admision-y-matricula/examen-de-admision',
    'matriculas': '/admision-y-matricula/matriculas',
    'becas-y-creditos': '/admision-y-matriculas/becas-y-creditos',
    'licenciamiento': '/transparencia/licenciamiento',
    'documentos-de-gestion': '/transparencia/documentos-de-gestion',
    'inversion-y-gestion': '/transparencia/inversion-y-gestion',
    'estadisticas': '/transparencia/estadisticas',
    'libro-de-reclamaciones': '/transparencia/libro-de-reclamaciones',
    'validar-certificado': '/validar-certificado',
    'validar-certificado.print': '/validar-certificado/{certificate_code}/imprimir',
    'admin.dashboard': '/admin/dashboard',
};

export function route(name, params = null) {
    if (!name) return '/';
    if (typeof name !== 'string') return '/';
    if (name.startsWith('/') || name.startsWith('http://') || name.startsWith('https://')) {
        return name;
    }

    let pattern = routesMap[name] || `/${name.replace(/\./g, '/')}`;

    if (params !== null && params !== undefined) {
        if (typeof params === 'object' && !Array.isArray(params)) {
            const queryParams = [];
            for (const [key, val] of Object.entries(params)) {
                if (pattern.includes(`{${key}}`)) {
                    pattern = pattern.replace(`{${key}}`, encodeURIComponent(val));
                } else if (pattern.includes(`{${key}?}`)) {
                    pattern = pattern.replace(`{${key}?}`, encodeURIComponent(val));
                } else if (val !== null && val !== undefined) {
                    queryParams.push(`${encodeURIComponent(key)}=${encodeURIComponent(val)}`);
                }
            }
            pattern = pattern.replace(/\/\{[^}]+\?\}/g, '');
            if (queryParams.length > 0) {
                const separator = pattern.includes('?') ? '&' : '?';
                pattern += separator + queryParams.join('&');
            }
        } else {
            pattern = pattern.replace(/\{[^}]+\}/, encodeURIComponent(params));
        }
    }

    pattern = pattern.replace(/\/\{[^}]+\?\}/g, '');

    return pattern;
}

if (typeof window !== 'undefined') {
    window.route = route;
}

export default route;
