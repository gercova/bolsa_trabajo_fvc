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
    'admin.dashboard': '/admin-dashboard',
    'admin.dashboard.index': '/admin-dashboard',
    'admin.profile.edit': '/admin-perfil/{user?}',
    'admin.profile.update': '/admin-perfil/{user?}',
    'admin.profile.destroy': '/admin-perfil/{user?}',
    'admin.exams.index': '/admin-exams',
    'admin.exams.create': '/admin-exams/crear-examen',
    'admin.exams.store': '/admin-exams/guardar',
    'admin.exams.edit': '/admin-exams/editar-examen/{admission}',
    'admin.exams.update': '/admin-exams/editar-examen/{admission}',
    'admin.exams.destroy': '/admin-exams/{admission}',
    'admin.exams.toggle-status': '/admin-exams/estado/{admission}',
    'admin.exams.update-cepre-image': '/admin-exams/imagen-cepre',
    'admin.exams.update-admission-image': '/admin-exams/imagen-admision',
    'admin.enrollments.index': '/admin-matriculas',
    'admin.enrollments.create': '/admin-matriculas/crear',
    'admin.enrollments.store': '/admin-matriculas/guardar',
    'admin.enrollments.edit': '/admin-matriculas/editar/{schedule}',
    'admin.enrollments.update': '/admin-matriculas/editar/{schedule}',
    'admin.enrollments.destroy': '/admin-matriculas/{schedule}',
    'admin.enrollments.toggle-status': '/admin-matriculas/estado/{schedule}',
    'admin.scholarships.index': '/admin-scholarships',
    'admin.scholarships.create': '/admin-scholarships/crear-beca',
    'admin.scholarships.store': '/admin-scholarships/guardar',
    'admin.scholarships.edit': '/admin-scholarships/editar-beca/{scholarship}',
    'admin.scholarships.update': '/admin-scholarships/editar-beca/{scholarship}',
    'admin.scholarships.destroy': '/admin-scholarships/{scholarship}',
    'admin.scholarships.toggle-status': '/admin-scholarships/estado/{scholarship}',
    'admin.scholarships.beneficiaries.store': '/admin-scholarships/beneficiarios/guardar',
    'admin.scholarships.beneficiaries.edit': '/admin-scholarships/beneficiarios/editar/{beneficiary}',
    'admin.scholarships.beneficiaries.update': '/admin-scholarships/beneficiarios/editar/{beneficiary}',
    'admin.scholarships.beneficiaries.destroy': '/admin-scholarships/beneficiarios/{beneficiary}',
    'admin.scholarships.beneficiaries.toggle-status': '/admin-scholarships/beneficiarios/estado/{beneficiary}',
    'admin.scholarships.beneficiaries.download': '/admin-scholarships/beneficiarios/descargar/{beneficiary}',
    'admin.tupa.index': '/admin-tupa',
    'admin.tupa.create': '/admin-tupa/crear-tupa',
    'admin.tupa.store': '/admin-tupa/guardar',
    'admin.tupa.edit': '/admin-tupa/editar-tupa/{tupa}',
    'admin.tupa.update': '/admin-tupa/editar-tupa/{tupa}',
    'admin.tupa.destroy': '/admin-tupa/{tupa}',
    'admin.tupa.toggle-status': '/admin-tupa/estado/{tupa}',
    'admin.tupa.categories.create': '/admin-tupa/categorias/crear',
    'admin.tupa.categories.store': '/admin-tupa/categorias/guardar',
    'admin.tupa.categories.edit': '/admin-tupa/categorias/editar/{category}',
    'admin.tupa.categories.update': '/admin-tupa/categorias/editar/{category}',
    'admin.tupa.categories.destroy': '/admin-tupa/categorias/{category}',
    'admin.tupa.categories.toggle-status': '/admin-tupa/categorias/estado/{category}',
    'admin.tupa.procedures.create': '/admin-tupa/procedimientos/crear',
    'admin.tupa.procedures.store': '/admin-tupa/procedimientos/guardar',
    'admin.tupa.procedures.edit': '/admin-tupa/procedimientos/editar/{procedure}',
    'admin.tupa.procedures.update': '/admin-tupa/procedimientos/editar/{procedure}',
    'admin.tupa.procedures.destroy': '/admin-tupa/procedimientos/{procedure}',
    'admin.tupa.procedures.toggle-status': '/admin-tupa/procedimientos/estado/{procedure}',
    'admin.documents.index': '/admin-documentos',
    'admin.documents.create': '/admin-documentos/crear',
    'admin.documents.store': '/admin-documentos/guardar',
    'admin.documents.edit': '/admin-documentos/editar/{managementDocument}',
    'admin.documents.update': '/admin-documentos/editar/{managementDocument}',
    'admin.documents.destroy': '/admin-documentos/{managementDocument}',
    'admin.documents.toggle-status': '/admin-documentos/estado/{managementDocument}',
    'admin.regulations.index': '/admin-reglamento-interno',
    'admin.regulations.download': '/admin-reglamento-interno/descargar',
    'admin.library.index': '/admin-biblioteca',
    'admin.users.index': '/admin-usuarios',
    'admin.users.create': '/admin-usuarios/crear',
    'admin.users.store': '/admin-usuarios',
    'admin.users.edit': '/admin-usuarios/{user}/editar',
    'admin.users.update': '/admin-usuarios/{user}',
    'admin.users.destroy': '/admin-usuarios/{user}',
    'admin.users.toggle-status': '/admin-usuarios/estado/{user}',
    'admin.users.template': '/admin-usuarios/plantilla',
    'admin.users.import': '/admin-usuarios/importar',
    'admin.users.password.update': '/admin-usuarios/{user}/password',
    'admin.users.bulk-delete': '/admin-usuarios/eliminar-masivo',
    'admin.users.count-bulk-delete': '/admin-usuarios/conteo-masivo',
    'admin.teacher-roles.index': '/admin-docentes-roles',
    'admin.teacher-roles.store': '/admin-docentes-roles/guardar',
    'admin.teacher-roles.update': '/admin-docentes-roles/{teacherRole}',
    'admin.teacher-roles.toggle-status': '/admin-docentes-roles/estado/{teacherRole}',
    'admin.teacher-roles.destroy': '/admin-docentes-roles/{teacherRole}',
    'admin.student-council.index': '/admin-consejo-estudiantil',
    'admin.student-council.store': '/admin-consejo-estudiantil/guardar',
    'admin.student-council.update': '/admin-consejo-estudiantil/{studentCouncil}',
    'admin.student-council.toggle-status': '/admin-consejo-estudiantil/estado/{studentCouncil}',
    'admin.student-council.destroy': '/admin-consejo-estudiantil/{studentCouncil}',
    'admin.blogs.index': '/admin-blogs',
    'admin.blogs.create': '/admin-blogs/crear-blog',
    'admin.works.index': '/admin-trabajos',
    'admin.works.create': '/admin-trabajos/crear-trabajo',
    'admin.carousel.index': '/admin-carrusel',
    'admin.carousel.create': '/admin-carrusel/crear',
    'admin.carousel.store': '/admin-carrusel/guardar',
    'admin.carousel.edit': '/admin-carrusel/editar/{carousel}',
    'admin.carousel.update': '/admin-carrusel/editar/{carousel}',
    'admin.carousel.destroy': '/admin-carrusel/{carousel}',
    'admin.carousel.toggle-status': '/admin-carrusel/estado/{carousel}',
    'admin.history.index': '/admin-historia',
    'admin.history.create': '/admin-historia/crear',
    'admin.history.store': '/admin-historia/guardar',
    'admin.history.edit': '/admin-historia/editar/{history}',
    'admin.history.update': '/admin-historia/editar/{history}',
    'admin.history.destroy': '/admin-historia/{history}',
    'admin.history.toggle-status': '/admin-historia/estado/{history}',
    'admin.areas.index': '/admin-areas',
    'admin.areas.create': '/admin-areas/crear',
    'admin.areas.store': '/admin-areas/guardar',
    'admin.areas.edit': '/admin-areas/editar/{area}',
    'admin.areas.update': '/admin-areas/editar/{area}',
    'admin.areas.destroy': '/admin-areas/{area}',
    'admin.programs.index': '/admin-programas',
    'admin.links.index': '/admin-enlaces',
    'admin.links.create': '/admin-enlaces/crear',
    'admin.links.store': '/admin-enlaces/guardar',
    'admin.links.edit': '/admin-enlaces/{link}/editar',
    'admin.links.update': '/admin-enlaces/{link}',
    'admin.links.toggle-status': '/admin-enlaces/estado/{link}',
    'admin.links.destroy': '/admin-enlaces/{link}',
    'admin.roles.index': '/admin-roles',
    'admin.claims.index': '/admin-reclamos',
    'admin.statistics.index': '/admin-estadisticas',
    'admin.degree-records.index': '/admin-titulos',
    'admin.licensing.index': '/admin-licenciamiento',
    'admin.partners.index': '/admin-socios',
    'admin.partners.create': '/admin-socios/crear',
    'admin.partners.store': '/admin-socios/guardar',
    'admin.partners.edit': '/admin-socios/{partner}/editar',
    'admin.partners.update': '/admin-socios/{partner}',
    'admin.partners.destroy': '/admin-socios/{partner}',
    'admin.enterprise.edit': '/admin-empresa/editar',
    'admin.enterprise.update': '/admin-empresa/{enterprise}',
    'admin.account-balances.index': '/admin-inversiones',
    'admin.certificates.index': '/admin-certificados',
    'biblioteca.index': '/biblioteca',
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
