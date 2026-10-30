# PDR — Product Definition Report & Architectural Specification
## Plataforma Integral Web, Bolsa de Trabajo, Biblioteca Virtual y Certificación Digital
### IESTP "Francisco Vigo Caballero" — Uchiza, San Martín

---

## Control del Documento

| Campo | Detalle |
| :--- | :--- |
| **Documento** | Product Definition Report (PDR) / Technical Architecture Document |
| **Proyecto** | Plataforma Integral IESTP Francisco Vigo Caballero (Bolsa FVC) |
| **Versión** | 2.6.0-PROD |
| **Entorno de Referencia** | PHP 8.3 / Laravel 11.31 / MySQL 8.0+ / Tailwind CSS 3.4 / Alpine.js |
| **URL Oficial de Producción** | `https://iestpfranciscovigocaballero.edu.pe/` |
| **Estado** | Aprobado para Producción y Auditoría Técnica |
| **Última Actualización** | Octubre 2026 |
| **Inventario Técnico** | 45 Modelos • 44 Controladores • 42 FormRequests • 55 Migraciones • 30 Seeders • 318 Rutas • 147 Vistas Blade • 33 Tests |

---

## 1. Visión del Producto y Objetivos Estratégicos

### 1.1. Contexto Institucional
El **Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero"** (IESTP FVC), ubicado en el distrito de Uchiza, provincia de Tocache, departamento de San Martín, Perú, es una institución pública de educación superior licenciada por el Ministerio de Educación (MINEDU) que brinda formación técnica profesional a Nombre de la Nación.

### 1.2. Propósito del Sistema
El presente sistema unifica y digitaliza los procesos clave de la institución a través de una arquitectura web modular y resiliente:
1. **Presencia Institucional y Transparencia Activa:** Cumplimiento de la Ley de Transparencia y Acceso a la Información Pública, publicación de documentos de gestión, estadísticas de admisión y matrícula, balances contables y avance de licenciamiento (Condiciones Básicas de Calidad - CBC).
2. **Promoción de Carreras Profesionales:** Difusión de los 5 programas de estudio oficiales, mallas curriculares, competencias técnicas, perfiles laborales y cabecera interactiva con carrusel multimedia.
3. **Admisión y Matrícula Predictiva:** Gestión integral de ciclos formativos ordinarios, extraordinarios y CEPRE, con proyección dinámica de periodos futuros y deducción de vacantes.
4. **Intermediación Laboral (Bolsa de Trabajo):** Conexión entre estudiantes/egresados y empresas del sector productivo local, regional y nacional, complementada con un motor automatizado de scraping de empleo con filtros geográficos peruanos.
5. **Certificación Digital Soberana con QR:** Emisión y validación instantánea de certificados (capacitación modular y talleres) mediante códigos QR generados directamente en el servidor sin dependencias externas, con exportación masiva a PDF.
6. **Biblioteca Virtual e Investigación Abierta:** Repositorio digital con lector integrado, streaming protegido de PDFs y sincronización masiva con 10 índices científicos mundiales (Europe PMC, Open Library/Internet Archive, SciELO, Redalyc, DOAJ, OpenAlex, Crossref, Semantic Scholar, Scopus, Google Scholar).
7. **Trámites y TUPA Virtual:** Digitalización de requisitos, aranceles en S/. y % UIT, plazos y ventanilla de Mesa de Partes.

---

## 2. Actores del Sistema y Matriz de Control de Acceso (RBAC)

El sistema implementa control de acceso basado en roles (Role-Based Access Control) mediante el paquete oficial `spatie/laravel-permission` y autorización granular a través de `Gate::before` en `AppServiceProvider`.

### 2.1. Definición de Roles
- **Director General:** Máxima autoridad institucional. Posee supervisión y permisos globales sobre todos los módulos, incluyendo la autorización de vaciado y reseteo de balances financieros (`can:gestionar-inversiones`).
- **Administrador (Admin):** Gestión operativa técnica, mantenimiento de usuarios, seguridad, reseteo de credenciales, auditoría y parametrización institucional.
- **Docente:** Gestión de asignaturas, módulos de capacitación, participación en comités curriculares y acceso al catálogo de biblioteca.
- **Coordinador de Área:** Jefaturas de programas de estudio con facultades para editar competencias, requisitos, metadatos y certificaciones modulares de su especialidad.
- **Coordinador de Calidad:** Supervisión del TUPA, Libro de Reclamaciones y auditoría de etapas de licenciamiento institucional (CBC).
- **Coordinador de Empleabilidad:** Gestión de ofertas laborales, convenios con empresas (Partners), scraping laboral y seguimiento a graduados.
- **Coordinador de Educación Continua:** Programación de cursos extracurriculares y emisión de certificados institucionales de capacitación.
- **Coordinador de Investigación:** Repositorio institucional y publicaciones científicas.
- **Personal Administrativo:** Gestión de trámites documentarios, estadísticas de transparencia y atención de reclamos.
- **Lector (Estudiante / Egresado):** Acceso al portal de Biblioteca Virtual, favoritos, visor de PDFs y postulación a vacantes de empleo con carga de CV. Dispone de campo para correo personal (`personal_email`) para comunicaciones de egresado.
- **Público General / Visitante:** Acceso no autenticado al portal informativo, consulta de carreras, verificación pública de certificados por QR/código/DNI y registro en Libro de Reclamaciones.

### 2.2. Matriz de Permisos del Sistema

| Permiso (`name`) | Descripción Operativa | Roles Asignados |
| :--- | :--- | :--- |
| `ver-dashboard` | Acceso a métricas generales y accesos rápidos en `/admin-dashboard`. | Director, Admin, Docente, Todos los Coordinadores, Administrativo |
| `gestionar-examenes` | Creación y edición de convocatorias de admisión, CEPRE y vacantes. | Director, Admin, Docente, Coord. de Área |
| `gestionar-programas` | Edición de programas de estudio, competencias, perfiles y metadatos UI. | Director, Admin, Docente, Coord. de Área, Coord. de Calidad, Coord. Educación Continua |
| `gestionar-tupa` | Mantenimiento de categorías y procedimientos TUPA. | Director, Admin, Coord. de Calidad, Administrativo |
| `gestionar-trabajos` | Publicación de ofertas, scraping y revisión de candidatos. | Director, Admin, Coord. de Empleabilidad |
| `gestionar-usuarios` | Altas, bajas, reseteo de contraseñas e importación Excel de usuarios. | Director, Admin |
| `gestionar-roles` | Creación de roles y asignación de permisos Spatie. | Director, Admin |
| `gestionar-reclamos` | Consulta, atención y cambio de estado de hojas de reclamación. | Director, Admin, Coord. de Calidad, Administrativo |
| `gestionar-empresa` | Configuración de razón social, logotipos, redes y datos institucionales. | Director, Admin |
| `gestionar-partners` | Registro de empresas e instituciones con convenio vigente. | Director, Admin, Coord. de Empleabilidad |
| `gestionar-investigacion` | Carga de artículos y gestión de repositorios académicos. | Director, Admin, Coord. de Investigación |
| `gestionar-educacion-continua` | Mantenimiento de cursos, módulos de certificación e itinerarios. | Director, Admin, Coord. Educación Continua |
| `gestionar-inversiones` | Importación de balances y operaciones destructivas (truncate de tabla/año). | Director, Administrador |

---

## 3. Diccionario de Datos y Catálogo de Modelos Eloquent

La aplicación cuenta con **45 Modelos Eloquent** activos. A continuación se agrupan por dominio de negocio con sus atributos clave y relaciones:

### 3.1. Núcleo Institucional y Parametría
1. **`Enterprise`**
   - *Tabla:* `enterprise`
   - *Campos:* `ruc`, `company_name`, `trade_name`, `legal_representative_dni`, `legal_representative`, `email`, `phone_number_1`, `whatsapp_link`, `address`, `city`, `phrase`, `description`, `mission`, `vision`, `principles`, `values`, `logo_path`, `favicon_path`, `facebook_link`, `instagram_link`, `linkedin_link`.
   - *Métodos:* `getDefault()` (provee datos predeterminados en caso de tabla vacía para uso en View Composers globales).
2. **`Area`**
   - *Tabla:* `area`
   - *Campos:* `name`, `code`, `user_id`.
   - *Relaciones:* `belongsTo(User::class)`.
3. **`Charge`**
   - *Tabla:* `charges`
   - *Campos:* `name`, `is_active`.
4. **`DocumentType`**
   - *Tabla:* `document_type`
   - *Campos:* `name`, `short_name`.
   - *Relaciones:* `hasMany(User::class)`.
5. **`HistoricalReview`**
   - *Tabla:* `historical_reviews`
   - *Campos:* `year`, `title`, `description`, `image_path`, `order`, `is_active`.
6. **`InstitutionalCarousel`**
   - *Tabla:* `institutional_carousels`
   - *Campos:* `title`, `subtitle`, `cta_text`, `cta_link`, `order`, `is_active`.
   - *Relaciones:* `morphOne(Image::class, 'imageable')`.
7. **`Partner`**
   - *Tabla:* `partners`
   - *Campos:* `name`, `logo_path`, `website_link`, `is_active`.
8. **`ExternalInstitutionalLink`**
   - *Tabla:* `external_institutional_links`
   - *Campos:* `title`, `url`, `logo_path`, `category`, `is_active`.
9. **`VisitorCounter`**
   - *Tabla:* `visitor_counters`
   - *Campos:* `ip_address`, `visited_date`, `visits_count`.
   - *Métodos:* `getTotalVisits()`, `getPaddedDigits($total, $length)`.

### 3.2. Programas de Estudio y Currícula
10. **`StudyProgram`**
    - *Tabla:* `study_programs`
    - *Campos:* `name`, `slug`, `description`, `details`, `order`, `is_active`, `enable_carousel` (boolean), `carousel_alt_text` (text).
    - *Relaciones:* `hasOne(ProgramMeta::class)`, `hasMany(ProgramCompetency::class)`, `hasMany(ProgramJobField::class)`, `hasMany(ProgramRequirement::class)`, `hasMany(Module::class)`, `hasMany(Course::class)`, `morphMany(Image::class, 'imageable')`.
11. **`ProgramMeta`**
    - *Tabla:* `program_metas`
    - *Campos:* `study_program_id`, `icon`, `accent`, `bg_badge`, `tag`, `color_bar`, `glow_class`, `badge_class`, `accent_text`, `bullet_class`, `icon_bg_class`, `border_hover_class`, `badge_module_class`, `sidebar_icon_class`, `cta_bg_class`, `bar_color_class`.
12. **`ProgramCompetency`**
    - *Tabla:* `program_competencies`
    - *Campos:* `study_program_id`, `title`, `description`, `icon`, `order`, `is_active`.
13. **`ProgramJobField`**
    - *Tabla:* `program_job_fields`
    - *Campos:* `study_program_id`, `description`, `order`, `is_active`.
14. **`ProgramRequirement`**
    - *Tabla:* `program_requirements`
    - *Campos:* `study_program_id`, `description`, `order`, `is_active`.
15. **`ModularCertification`**
    - *Tabla:* `modular_certification`
    - *Campos:* `model_type`, `model_id`, `code`, `name`, `description`, `is_active`.
16. **`Course`**
    - *Tabla:* `courses`
    - *Campos:* `study_program_id`, `name`, `description`, `code`, `certificate_type` (`capacitacion` o `modular`), `event_name` (evento u ocasión), `is_active`.
    - *Relaciones:* `belongsTo(StudyProgram::class)`, `hasMany(Module::class)`, `hasMany(Itinerary::class)`, `hasMany(Certificate::class)`.
17. **`Module`**
    - *Tabla:* `modules`
    - *Campos:* `course_id`, `name`, `hours`, `credits`, `order`, `is_active`.
    - *Relaciones:* `belongsTo(Course::class)`, `hasMany(Itinerary::class)`.
18. **`Itinerary`**
    - *Tabla:* `itineraries`
    - *Campos:* `course_id`, `module_id`, `code`, `name`, `is_active`.
    - *Relaciones:* `belongsTo(Course::class)`, `belongsTo(Module::class)`.

### 3.3. Certificación Digital y Notas
19. **`Certificate`**
    - *Tabla:* `certificates`
    - *Campos:* `code` (identificador único estandarizado), `certificate_code` (código de diploma), `user_id`, `course_id`, `study_program_id`, `certificate_type` (`capacitacion` o `modular`), `participation_type` (`ASISTENTE`, `PONENTE`, etc.), `event_name`, `institution_name`, `city`, `issue_date`, `start_date`, `end_date`, `duration`, `modality`, `qr_code_path`, `download_count`, `is_active`.
    - *Relaciones:* `belongsTo(User::class)`, `belongsTo(Course::class)`, `belongsTo(StudyProgram::class)`, `hasMany(CertificateDetail::class)`.
20. **`CertificateDetail`**
    - *Tabla:* `certificate_details`
    - *Campos:* `certificate_id`, `module_id` (nullable para capacitación), `topic_name` (tema de temario), `score` (nota vigesimal opcional), `observations`, `order`, `is_active`.
    - *Relaciones:* `belongsTo(Certificate::class)`, `belongsTo(Module::class)`.

### 3.4. Admisión, Matrícula y Becas
21. **`Admission`**
    - *Tabla:* `cepre`
    - *Campos:* `period`, `activity`, `process` (cepre/admisión/matrícula), `type` (ordinario/extraordinario), `exam_date`, `start_date`, `end_date`, `total_vacancies`, `price`, `results_url_pdf`, `is_active`.
    - *Relaciones:* `hasMany(AdmissionDetail::class, 'admission_id')`.
22. **`AdmissionDetail`**
    - *Tabla:* `cepre_details`
    - *Campos:* `admission_id`, `program_id`, `vacancies`.
    - *Relaciones:* `belongsTo(Admission::class)`, `belongsTo(StudyProgram::class, 'program_id')`.
23. **`AdmissionRequirement`**
    - *Tabla:* `admission_requirements`
    - *Campos:* `description`, `is_active`.
24. **`EnrollmentSchedule`**
    - *Tabla:* `enrollment_schedules`
    - *Campos:* `enrollment_type` (ordinaria/extraordinaria), `start_date`, `end_date`, `description`, `is_active`.
    - *Relaciones:* `hasMany(EnrollmentScheduleDetail::class)`.
25. **`EnrollmentScheduleDetail`**
    - *Tabla:* `enrollment_schedule_details`
    - *Campos:* `enrollment_schedule_id`, `study_program_id`, `requirements`, `notes`.
26. **`Scholarship`**
    - *Tabla:* `scholarships`
    - *Campos:* `name`, `type`, `description`, `vacancies`, `requirements`, `order`, `is_active`.
    - *Relaciones:* `hasMany(ScholarshipBeneficiary::class)`.
27. **`ScholarshipBeneficiary`**
    - *Tabla:* `scholarship_beneficiaries`
    - *Campos:* `scholarship_id`, `academic_period`, `title`, `document_pdf_path`, `beneficiaries_count`, `order`, `is_active`.

### 3.5. Transparencia, Indicadores y Finanzas
28. **`StudentRecord`**
    - *Tabla:* `student_records`
    - *Campos:* `document_type_id`, `document`, `names`, `last_name_father`, `last_name_mother`, `gender`, `email`, `phone`, `record_type` (MATRICULA/ADMISION), `academic_period`, `study_program`, `cycle` (I al VI), `institution_name_ie`.
29. **`DegreeRecord`**
    - *Tabla:* `degree_records`
    - *Campos:* `full_names`, `document_number`, `gender`, `study_program`, `formative_level`, `productive_family`, `diploma_issue_date`, `file_number`, `generated_title_code`, `department`.
30. **`AccountBalance`**
    - *Tabla:* `account_balances`
    - *Campos:* `date`, `concept`, `category`, `amount`, `document_reference`, `period_year`, `period_month`.
    - *Scopes:* `filterByYear()`, `filterByMonth()`, `filterByCategory()`, `search()`.
31. **`ManagementDocument`**
    - *Tabla:* `management_documents`
    - *Campos:* `title`, `description`, `file_path`, `type`, `is_active`.
32. **`LicensingPhase`**
    - *Tabla:* `licensing_phases`
    - *Campos:* `phase_number`, `title`, `description`, `status` (completed/in_progress/pending), `progress_percentage`, `is_current`, `order`, `is_active`.
33. **`Claim`**
    - *Tabla:* `claims`
    - *Campos:* `code`, `names`, `dni`, `phone`, `email`, `address`, `service_type`, `claimed_amount`, `description`, `claim_type` (queja/reclamo), `status` (pendiente/en_proceso/atendido), `file_path`.

### 3.6. Trámites y TUPA
34. **`Tupa`**
    - *Tabla:* `tupa`
    - *Campos:* `title`, `description`, `normative_base`, `effective_start_date`, `file_path`, `is_active`.
    - *Relaciones:* `hasMany(TupaProcedure::class)`.
35. **`TupaCategory`**
    - *Tabla:* `tupa_categories`
    - *Campos:* `name`, `description`, `icon`, `order`, `is_active`.
    - *Relaciones:* `hasMany(TupaProcedure::class)`.
36. **`TupaProcedure`**
    - *Tabla:* `tupa_procedures`
    - *Campos:* `tupa_id`, `tupa_category_id`, `code`, `name`, `description`, `requirements` (JSON), `cost`, `uit_percent`, `qualification` (automática/evaluación previa), `duration`, `office`, `is_active`.

### 3.7. Empleo y Bolsa de Trabajo
37. **`JobOffer`**
    - *Tabla:* `jobs`
    - *Campos:* `title`, `company`, `location`, `description`, `requirements`, `salary`, `source`, `source_url`, `deadline`, `is_active`.

### 3.8. Biblioteca Virtual
38. **`Book`**
    - *Tabla:* `books`
    - *Campos:* `study_program_id`, `title`, `slug`, `author`, `publisher`, `publication_year`, `isbn`, `edition`, `summary`, `cover_image`, `file_path`, `external_url`, `category` (Libro, Revista, Paper, Tesis, etc.), `source_type` (local/external/academic), `doi`, `views_count`, `downloads_count`, `rating`, `created_by`, `is_active`.
    - *Relaciones:* `belongsTo(StudyProgram::class)`, `belongsTo(User::class, 'created_by')`, `hasMany(BookFavorite::class)`, `hasMany(LibraryAccessLog::class)`.
    - *Scopes:* `career()`, `category()`, `search()`.
39. **`BookFavorite`**
    - *Tabla:* `book_favorites`
    - *Campos:* `user_id`, `book_id`.
40. **`LibraryAccessLog`**
    - *Tabla:* `library_access_logs`
    - *Campos:* `user_id`, `book_id`, `ip_address`, `access_type` (view/stream/download).

### 3.9. Usuarios, Roles y Seguridad
41. **`User`**
    - *Tabla:* `users`
    - *Campos:* `dni`, `personal_email` (correo personal único), `email` (correo institucional), `names`, `birthdate`, `mother_tongue`, `phone`, `address`, `sex`, `ubigeo`, `photo_profile`, `cv_file`, `role`, `job_position`, `password`, `is_active`.
    - *Relaciones:* `hasMany(UserRoleDetail::class)`, `hasOne(UserRoleDetail::class, 'primaryRoleDetail')`, `hasMany(Certificate::class)`, `belongsToMany(Book::class, 'book_favorites')`.
42. **`UserRoleDetail`**
    - *Tabla:* `user_roles_details`
    - *Campos:* `user_id`, `program_id`, `role_name`, `is_coordinator`, `academic_degree`, `specialty`, `is_active`.
43. **`StudentCouncil`**
    - *Tabla:* `student_councils`
    - *Campos:* `user_id`, `study_program_id`, `academic_period`, `position`, `is_active`.
44. **`Image`**
    - *Tabla:* `images`
    - *Campos:* `imageable_type`, `imageable_id`, `path`, `is_main`.
45. **`Blog`**
    - *Tabla:* `blogs`
    - *Campos:* `title`, `slug`, `content`, `details`, `is_published`.

---

## 4. Catálogo Completo de Rutas y Endpoints

La aplicación cuenta con **318 rutas registradas** que abarcan navegación pública, portal de lectores de biblioteca, flujos de autenticación y panel de control administrativo.

### 4.1. Rutas Públicas e Institucionales (Canónicas)
| Método | URI | Nombre de Ruta | Controlador@Método | Finalidad |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/` | `inicio` | `AppController@index` | Portada institucional, hero carousel, accesos rápidos y métricas. |
| `GET` | `/admision-y-matricula/cepre-fvc` | `cepre-fvc` | `AppController@ceprefvc` | Vacantes, costos y cronograma del ciclo CEPRE proyectado. |
| `GET` | `/admision-y-matricula/examen-de-admision` | `examen-de-admision` | `AppController@admissionExam` | Convocatoria Ordinaria/Extraordinaria y vacantes netas. |
| `GET` | `/admision-y-matricula/matriculas` | `matriculas` | `AppController@enrollments` | Cronogramas de matrícula ordinaria y extraordinaria. |
| `GET` | `/admision-y-matriculas/becas-y-creditos`| `becas-y-creditos` | `AppController@scholarshipsAndCredits` | Catálogo de becas y resoluciones de beneficiarios. |
| `ANY` | `/becas-y-creditos` | *(Redirect)* | `RedirectController` | Redirección canónica a `/admision-y-matriculas/becas-y-creditos`. |
| `GET` | `/programas-de-estudios` | `programas-de-estudio` | `AppController@studyPrograms` | Catálogo general de los 5 programas de estudio. |
| `GET` | `/programas-de-estudios/{program:slug}` | `programas-de-estudio.detalle` | `AppController@program` | Malla, competencias, campo laboral, carrusel multimedia y fotos. |
| `ANY` | `/study-programs` | *(Redirect)* | `RedirectController` | Redirección canónica a `/programas-de-estudios`. |
| `GET` | `/transparencia/documentos-de-gestion` | `documentos-de-gestion` | `AppController@documentsManagement` | Documentos oficiales normativos (PEI, PAT, PCI). |
| `GET` | `/transparencia/estadisticas` | `estadisticas` | `AppController@statistics` | Dashboards consolidados de matrícula, admisión y género. |
| `GET` | `/transparencia/inversion-y-gestion` | `inversion-y-gestion` | `AppController@managementReports` | Balances financieros mensuales y ejecución presupuestal. |
| `GET` | `/transparencia/licenciamiento` | `licenciamiento` | `AppController@licensment` | Progreso de las Condiciones Básicas de Calidad (CBC). |
| `GET` | `/transparencia/libro-de-reclamaciones` | `libro-de-reclamaciones` | `AppController@complaintsBook` | Formulario virtual de atención a quejas y reclamos. |
| `POST`| `/transparencia/libro-de-reclamaciones` | `libro-de-reclamaciones.store` | `AppController@storeClaim` | Registro seguro de reclamos (rate limit `throttle:5,1`). |
| `GET` | `/tramites/mesa-de-partes` | `mesa-de-partes` | `AppController@partsTable` | Guía de recepción de expedientes y horarios. |
| `GET` | `/tramites/tupa` | `tupa` | `AppController@tupa` | Catálogo interactivo de procedimientos y aranceles en S/. y % UIT. |
| `GET` | `/nosotros/quienes-somos` | `quienes-somos` | `AppController@whoWeAre` | Misión, visión, valores y reseña institucional. |
| `GET` | `/nosotros/historia` | `historia` | `AppController@history` | Línea de tiempo y evolución del instituto. |
| `GET` | `/nosotros/organigrama-institucional` | `organigrama-institucional` | `AppController@institutionalOrganizationChart` | Estructura jerárquica y áreas de la institución. |
| `GET` | `/nosotros/plana-jerarquica` | `plana-jerarquica` | `AppController@hierarchicalStaff` | Dirección y jefaturas de unidades de gestión. |
| `GET` | `/nosotros/plana-de-docentes` | `plana-de-docentes` | `AppController@teachersStaff` | Plana docente clasificada por carreras y formación general. |
| `GET` | `/nosotros/plana-administrativa` | `plana-administrativa` | `AppController@administrativeStaff` | Personal de soporte administrativo y operativo. |
| `GET` | `/nosotros/consejo-de-estudiantes` | `consejo-de-estudiantes` | `AppController@studentCouncil` | Representantes estudiantiles por periodo lectivo. |
| `GET` | `/nosotros/locales` | `locales` | `AppController@locales` | Sedes, talleres, campos agrícolas y laboratorios. |
| `GET` | `/servicios/bolsa-de-trabajo` | `bolsa-de-trabajo` | `AppController@offers` | Portal de convocatorias laborales y directorio de CVs. |
| `GET` | `/servicios/enlaces-institucionales` | `enlaces-institucionales` | `AppController@institutionalLinks` | Accesos rápidos a MINEDU, SUNEDU, PRONABEC. |
| `GET` | `/validar-certificado/{certificate_code?}` | `validar-certificado` | `AppController@validateCertificate` | Portal de verificación pública de certificados por QR/código/DNI. |
| `GET` | `/validar-certificado/{certificate_code}/imprimir`| `validar-certificado.print` | `AppController@printCertificate` | Hoja oficial de certificación membretada con QR y notas. |
| `GET` | `/verificar-certificado/{certificate_code?}`| `verificar.certificado` | `Closure (Redirect)` | Alias de redirección canónica hacia `/validar-certificado`. |
| `GET` | `/verify-certificate/{certificate_code?}`| `verify.certificate` | `Closure (Redirect)` | Alias internacional hacia `/validar-certificado`. |
| `GET` | `/sitemap.xml` | `sitemap` | `SitemapController@index` | Generación y entrega del mapa XML canónico para Google. |
| `ANY` | `/robot.txt` | *(Redirect)* | `RedirectController` | Redirección canónica a `/robots.txt`. |
| `GET` | `/storage/{path}` | `storage.fallback` | `Closure` | Servidor seguro de archivos multimedia con detección MIME. |

### 4.2. Portal de Biblioteca Virtual (Autenticado: `auth`)
| Método | URI | Nombre de Ruta | Controlador@Método | Finalidad |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/biblioteca` | `biblioteca.index` | `LibraryReaderController@index` | Portada de la biblioteca virtual con libros y revistas destacadas. |
| `GET` | `/biblioteca/buscar` | `biblioteca.search` | `LibraryReaderController@search` | Búsqueda facetada por palabras clave, DOI y filtros de carrera. |
| `GET` | `/biblioteca/mi-biblioteca` | `biblioteca.favorites` | `LibraryReaderController@myLibrary` | Estante personal de libros favoritos del usuario. |
| `GET` | `/biblioteca/leer/{book:slug}` | `biblioteca.read` | `LibraryReaderController@read` | Visor inmersivo de lectura dentro del navegador. |
| `GET` | `/biblioteca/stream/{book:slug}` | `biblioteca.stream` | `LibraryReaderController@stream` | Transmisión segura por bloques del flujo binario PDF sin CORS. |
| `POST`| `/biblioteca/favorito/{book}` | `biblioteca.favorite.toggle` | `LibraryReaderController@toggleFavorite` | Alternar inclusión/exclusión de favoritos. |

### 4.3. Panel de Gestión Administrativa (`auth`)
| Prefijo de URI | Nombre Base | Controlador Principal | Módulos y Acciones Clave |
| :--- | :--- | :--- | :--- |
| `/admin-dashboard` | `admin.dashboard.*` | `DashboardController` | KPIs globales, estadísticas consolidadas y accesos directos. |
| `/admin-perfil` | `admin.profile.*` | `ProfileController` | Edición de perfil de usuario, datos de contacto, avatar y password. |
| `/admin-blogs` | `admin.blogs.*` | `BlogController` | CRUD de noticias, galerías y estado de publicación institucional. |
| `/admin-exams` | `admin.exams.*` | `AdmissionsController` | Convocatorias de admisión, vacantes, cronogramas e imágenes CEPRE. |
| `/admin-scholarships` | `admin.scholarships.*` | `ScholarshipController` | Becas y resoluciones de beneficiarios (`ScholarshipBeneficiaryController`). |
| `/admin-biblioteca` | `admin.library.*` | `Admin\LibraryController` | Catálogo de libros, repositorio CRUD, importación masiva académica (`/importar-academicos`), gestión de lectores, administradores y reportes. |
| `/admin-tupa` | `admin.tupa.*` | `TupaController` | Documento matriz TUPA, categorías temáticas y procedimientos con cálculo de costos en S/. y % UIT. |
| `/admin-documentos` | `admin.documents.*` | `ManagementDocumentController` | Carga y actualización de documentos de gestión pública (PEI, PAT, PCI, RI). |
| `/admin-reglamento-interno`| `admin.regulations.*`| `ManagementDocumentController` | Vista y descarga oficial del Reglamento Interno Institucional. |
| `/admin-areas` | `admin.areas.*` | `AreaController` | Catálogo de unidades y dependencias funcionales institucionales. |
| `/admin-programas` | `admin.programs.*` | `StudyProgramsController` | Carreras, certificaciones modulares, competencias, campo laboral, metadatos UI, carrusel multimedia y selección de imagen principal. |
| `/admin-cursos` | `admin.courses.*` | `CourseController` | Cursos y talleres formativos con tipo de certificación (`capacitacion` o `modular`), programa y evento. |
| `/admin-modulos` | `admin.modules.*` | `ModuleController` | Módulos formativos, horas y créditos académicos asociados a cursos. |
| `/admin-certificados` | `admin.certificates.*` | `CertificateController` | Emisión, búsqueda de estudiantes (`/buscar-estudiantes`), descarga de plantilla (`/plantilla`), importación masiva Excel (`/importar`), exportación masiva PDF (`/exportar-pdf`) y borrado masivo (`/eliminar-masivo`, `/eliminar-todos`). |
| `/admin-itinerarios` | `admin.itineraries.*` | `ItineraryController` | Asignación de itinerarios formativos curriculares. |
| `/admin-trabajos` | `admin.works.*` | `JobsController` | Empleos, convocatorias internas (`/convocatorias-internas`), scraping automático (`/buscar-automatico`), borrado masivo y vaciado total (`/vaciar-tabla`). |
| `/admin-usuarios` | `admin.users.*` | `UsersController` | Directorio de usuarios, descarga de plantilla (`/plantilla`), importación masiva Excel (`/importar`), reseteo de contraseñas (`/password`), borrado masivo y modal de conteo (`/conteo-masivo`). |
| `/admin-docentes-roles`| `admin.teacher-roles.*`| `TeacherRoleController` | Asignación de docentes a carreras, especialidad y coordinación académica. |
| `/admin-consejo-estudiantil`| `admin.student-council.*`| `StudentCouncilController` | Registro de junta directiva estudiantil por periodos lectivos. |
| `/admin-enlaces` | `admin.links.*` | `ExternalInstitutionalLinkController` | Enlaces externos de interés gubernamental y académico (Minedu, Sunedu). |
| `/admin-roles` | `admin.roles.*` | `RoleController` | Administración de roles y permisos Spatie a nivel administrativo. |
| `/admin-reclamos` | `admin.claims.*` | `ClaimsController` | Seguimiento, resolución y estado de reclamos Indecopi. |
| `/admin-estadisticas` | `admin.statistics.*` | `StadisticController` | Carga masiva Excel y gestión de registros de matrícula/admisión para gráficos. |
| `/admin-titulos` | `admin.degree-records.*` | `DegreeRecordsController` | Padrón histórico de egresados titulados con importación Excel. |
| `/admin-licenciamiento`| `admin.licensing.*` | `LicensingController` | Fases de licenciamiento y designación de etapa vigente (CBC). |
| `/admin-socios` | `admin.partners.*` | `PartnersController` | Directorio de empresas aliadas y logotipos de convenios laborales. |
| `/admin-empresa` | `admin.enterprise.*` | `EnterpriseController` | Datos institucionales, RUC, redes, representantes y directivos. |
| `/admin-matriculas` | `admin.enrollments.*` | `EnrollmentScheduleController` | Fechas límite y requisitos de matrícula ordinaria/extraordinaria. |
| `/admin-historia` | `admin.history.*` | `HistoricalReviewController` | Hitos históricos y cronología institucional con fotografías. |
| `/admin-carrusel` | `admin.carousel.*` | `CarouselController` | Banners promocionales de la portada con orden interactivo. |
| `/admin-inversiones` | `admin.account-balances.*`| `AccountBalanceController` | Carga de balances contables, importación Excel y borrado controlado por periodos/tabla con permiso `can:gestionar-inversiones`. |

### 4.4. Rutas de Autenticación (`routes/auth.php`)
- `register` / `register.verify-dni` (`throttle:6,1`): Registro público con verificación instantánea de DNI.
- `login` (`throttle:login`): Autenticación segura con limitador por IP y cuenta.
- `forgot-password` / `reset-password` (`throttle:5,1`): Flujo de restablecimiento de contraseña.
- `verify-email` (`signed`, `throttle:6,1`): Validación de dirección electrónica.
- `password.confirm` / `password.update` / `logout`: Operaciones de sesión activa.

---

## 5. Especificación de Subsistemas Críticos

### 5.1. Motor de Certificación Digital Soberana y Código QR Autónomo (`QrCodeService`)
- **Problema Abordado:** Las dependencias de servicios externos (APIs de Google Charts, servicios SaaS) exponen datos personales de estudiantes y colapsan si el servidor no tiene salida a internet o experimenta latencias de red.
- **Implementación Arquitectural:**
  - El servicio `App\Services\QrCodeService` implementa el estándar ISO/IEC 18004 en PHP puro mediante matrices de Galois GF(256) y corrección de errores Reed-Solomon (Nivel M: 15% de recuperación).
  - Emite códigos vectoriales **SVG puros** o mapas de bits **PNG nativos** incrustando la URL canónica de verificación:
    `https://iestpfranciscovigocaballero.edu.pe/validar-certificado?code={CODIGO_UNICO}`
- **Dualidad de Formatos de Certificados:**
  - **Certificados de Capacitación:** Emitidos para talleres, seminarios y cursos libres. Incorporan `participation_type` (ASISTENTE, PONENTE, etc.), `event_name` (evento u ocasión) y temario desagregado (`topic_name`) sin requerir notas.
  - **Certificados Modulares:** Emitidos para itinerarios formativos oficiales. Incorporan notas vigesimales por módulo aprobado, desglose de horas, créditos y firmas digitales.
- **Identificador Único (`code`):** Código alfanumérico estandarizado generado de forma única, indexado y validado en base de datos.
- **Exportación Masiva a PDF:** Implementada en `CertificateController@bulkExportPdf`, compilando múltiples certificados seleccionados en una sola tirada imprimible con marcas de agua, logos institucionales y códigos QR.

### 5.2. Motor de Búsqueda Académica Multifuente y Streaming de PDFs (`AcademicLibraryService`)
- **Integración Multifuente (10 Repositorios Abiertos):**
  - **SciELO & Redalyc:** Publicaciones científicas iberoamericanas revisadas por pares.
  - **Europe PMC:** Artículos biomédicos y de salud clínica con resolución algorítmica de PMC IDs a PDFs directos (`https://europepmc.org/backend/ptpmcrender.fcgi?accid={PMC_ID}&blobtype=pdf`).
  - **Open Library & Internet Archive:** Libros digitalizados completos con enlace de descarga directa en PDF (`https://archive.org/download/{id}/{id}.pdf`).
  - **DOAJ (Directory of Open Access Journals):** Revistas arbitradas de acceso libre y texto completo.
  - **Crossref REST API & OpenAlex API:** Indexación por DOI, metadatos, citas académicas y tópicos.
  - **Semantic Scholar & Google Scholar:** Búsqueda académica avanzada y extracción semántica.
- **Resolución Inteligente y Fallback Multi-Nivel de DOIs:**
  - Si un recurso cuenta con DOI, el servicio ejecuta una cascada de resolución Open Access:
    `Unpaywall API → Crossref Works REST API → OpenAlex API`.
- **Sanitización de Consultas:** Función `sanitizeSearchTerm` que depura caracteres especiales reservados de la sintaxis Lucene (`[`, `]`, `{`, `}`, `^`, `~`, `*`, `:`, `\`, `/`).
- **Streaming Seguro de PDFs (`LibraryReaderController@stream`):**
  - Transmisión en línea mediante lectura por bloques binarios.
  - Neutraliza restricciones de CORS de servidores remotos sin descargar el archivo físicamente al disco local ni saturar la memoria RAM.
  - Entrega cabeceras `Content-Type: application/pdf` y `Content-Disposition: inline`.

### 5.3. Motor de Intermediación Laboral y Web Scraping Heurístico (`JobsController` / `ScrapeJobBoard`)
- **Mapeo Heurístico por Programa de Estudios:**
  - *Redes y TI:* "tecnico redes comunicaciones", "soporte tecnico", "administrador redes", "tecnico informatico".
  - *Asistencia Administrativa:* "asistente administrativo", "auxiliar administrativo", "secretaria asistente".
  - *Enfermería Técnica:* "tecnico enfermeria", "auxiliar enfermeria", "enfermero tecnico".
  - *Manejo Forestal:* "tecnico forestal", "manejo forestal", "recursos naturales".
  - *Producción Agropecuaria:* "tecnico agropecuario", "produccion agricola", "tecnico pecuario".
- **Filtro Geográfico Peruano:** Validación obligatoria contra las 24 regiones del Perú y ciudades principales, con exclusión terminológica de países del extranjero (Colombia, México, Chile, Argentina, etc.).
- **Comando Artisan:** `php artisan scrape:jobs {source_url} {source_name}` utilizando `Symfony\Component\DomCrawler\Crawler`.

### 5.4. Motor de Admisión con Proyección Dinámica de Ciclos
- **Cálculo Predictivo:**
  - `AppController@ceprefvc` y `AppController@admissionExam` proyectan dinámicamente el siguiente ciclo lectivo:
    ```php
    $projectedYear = now()->year + 1;
    $projectedPeriod = $projectedYear . '-I';
    ```
- **Deducción Automática de Vacantes CEPRE:** Las vacantes cubiertas por el ciclo CEPRE se descuentan automáticamente del cupo total de admisión ordinaria.

### 5.5. Arquitectura SEO e Indexación para Google Search Console
- **`robots.txt` / `robot.txt`:** Reglas en `public/robots.txt` y redirección en `routes/web.php` que protegen rutas de administración y autenticación mientras exponen recursos públicos y assets.
- **`sitemap.xml` Canónico:** Generado en `public/sitemap.xml` con 23 rutas públicas oficiales, prioridades y frecuencias W3C mediante `php artisan sitemap:generate`.
- **Verificación Google:** Metaetiqueta oficial en el layout maestro:
  ```html
  <meta name="google-site-verification" content="n-kFDeVvGTkr1sar6qNZRP1l5kFEqJzI-4PDgCCTC2s" />
  ```

### 5.6. Capa de Validación Estricta (42 Form Requests)
El sistema encapsula la validación de entrada mediante 42 clases `FormRequest` en `app/Http/Requests`:
- **Usuarios y Seguridad:** `UserValidate`, `UserImportRequest`, `LoginValidate`, `RegisterValidate`, `PasswordValidate`, `ProfileUpdateRequest`.
- **Certificación:** `CertificateRequest`, `CertificateDetailRequest`, `CertificateImportRequest`, `CourseRequest`, `ModuleRequest`, `ItineraryRequest`.
- **Biblioteca:** `BookRequest`.
- **Admisión y Matrícula:** `AdmissionRequest`, `EnrollmentScheduleRequest`, `ScholarshipRequest`, `ScholarshipBeneficiaryRequest`.
- **Transparencia y Finanzas:** `AccountBalanceImportRequest`, `ClaimValidate`, `DegreeRecordImportRequest`, `StudentRecordRequest`, `StudentRecordImportRequest`, `LicensingPhaseRequest`, `ManagementDocumentRequest`.
- **Programas de Estudio y UI:** `StudyProgramValidate`, `ProgramCompetencyRequest`, `ProgramJobFieldRequest`, `ProgramMetaRequest`, `ProgramRequirementRequest`, `ModularCertificationRequest`.
- **TUPA y Procedimientos:** `TupaRequest`, `TupaCategoryRequest`, `TupaProcedureRequest`.
- **Institucional:** `EnterpriseValidate`, `AreaRequest`, `BlogValidate`, `InstitutionalCarouselRequest`, `HistoricalReviewRequest`, `PartnerValidate`, `ExternalInstitutionalLinkValidate`, `JobValidate`, `StudentCouncilRequest`.

### 5.7. Motor de Importación y Exportación Masiva en Hojas de Cálculo
Basado en `maatwebsite/excel`:
- **5 Imports:** `AccountBalanceImport`, `CertificateImport`, `DegreeRecordImport`, `StudentRecordImport`, `UserImport`.
- **6 Exports:** `CertificateDataSheetExport`, `CertificateInstructionsSheetExport`, `CertificateTemplateExport`, `UserDataSheetExport`, `UserInstructionsSheetExport`, `UserTemplateExport`.

### 5.8. Middleware Institucional y Gestión de Tráfico
- **`CountWebsiteVisits`:** Middleware registrado en el pipeline `web` que contabiliza visitas por IP y fecha, ignorando llamadas asíncronas y rutas administrativas.
- **Políticas de Limitación de Tasa (Rate Limiting):**
  - `throttle:login`: 5 intentos por minuto por usuario/IP con bloqueo temporal progresivo.
  - `throttle:6,1`: Registro de nuevos usuarios y envío de notificaciones de correo.
  - `throttle:5,1`: Registro de reclamos virtuales y solicitudes de recuperación de contraseña.

---

## 6. Arquitectura de Controladores y Métodos

El sistema organiza su lógica en **44 Controladores** (33 Feature Controllers, 1 Base Controller, 1 Admin Controller y 9 Auth Controllers):

| Controlador | Namespace | Responsabilidad Primaria |
| :--- | :--- | :--- |
| `Controller` | `App\Http\Controllers` | Clase base abstracta de controladores de Laravel 11. |
| `AppController` | `App\Http\Controllers` | Despacho de las 27 acciones del portal público (carreras, admisión, transparencia, validación QR, impresión). |
| `SitemapController` | `App\Http\Controllers` | Construcción de XML estándar para Google Search Console y despacho dinámico. |
| `LibraryReaderController`| `App\Http\Controllers` | Experiencia de lectura en biblioteca virtual, visor inmersivo, favoritos y streaming protegido. |
| `JobsController` | `App\Http\Controllers` | Bolsa de trabajo, convocatorias internas, scraping heurístico y vaciado masivo. |
| `CertificateController` | `App\Http\Controllers` | Emisión, búsqueda de estudiantes, descarga de plantilla, importación Excel y exportación masiva PDF. |
| `StudyProgramsController`| `App\Http\Controllers`| Administración de carreras, certificaciones modulares, competencias, carrusel multimedia e imágenes. |
| `UsersController` | `App\Http\Controllers` | Directorio de usuarios, importación masiva Excel, reseteo de contraseñas y borrado masivo. |
| `AccountBalanceController`| `App\Http\Controllers`| Balances contables, reportes de inversión y reseteo/truncate con permiso `can:gestionar-inversiones`. |
| `AdmissionsController` | `App\Http\Controllers` | Convocatorias de admisión ordinaria, extraordinaria y CEPRE FVC. |
| `TupaController` | `App\Http\Controllers` | Documento matriz TUPA, categorías temáticas y procedimientos con cálculo de costos. |
| `DegreeRecordsController`| `App\Http\Controllers`| Padrón histórico de grados y títulos oficiales con importación Excel. |
| `StadisticController` | `App\Http\Controllers` | Registro masivo de estudiantes matriculados e ingresantes para estadísticas de transparencia. |
| `LicensingController` | `App\Http\Controllers` | Monitoreo del avance de las Condiciones Básicas de Calidad (CBC). |
| `BlogController` | `App\Http\Controllers` | Publicación de notas de prensa, noticias institucionales y comunicados. |
| `ClaimsController` | `App\Http\Controllers` | Gestión del Libro de Reclamaciones virtual y cambio de estado de atención Indecopi. |
| `CourseController` | `App\Http\Controllers` | Cursos y talleres formativos con tipo de certificación (`capacitacion` o `modular`). |
| `ModuleController` | `App\Http\Controllers` | Módulos formativos y distribución de horas y créditos. |
| `ItineraryController` | `App\Http\Controllers` | Itinerarios formativos curriculares. |
| `ScholarshipController` | `App\Http\Controllers` | Programas de becas estudiantiles y vacantes. |
| `ScholarshipBeneficiaryController`| `App\Http\Controllers` | Nómina de estudiantes beneficiarios de becas y resoluciones oficiales en PDF. |
| `EnrollmentScheduleController`| `App\Http\Controllers`| Cronogramas de matrícula ordinaria y extraordinaria por programa. |
| `TeacherRoleController` | `App\Http\Controllers` | Designación de docentes a carreras y cargos de coordinación académica. |
| `StudentCouncilController`| `App\Http\Controllers`| Registro de la junta directiva estudiantil por periodos lectivos. |
| `EnterpriseController` | `App\Http\Controllers` | Edición de la ficha institucional, misión, visión, logotipos y directivos. |
| `PartnersController` | `App\Http\Controllers` | Alianzas y convenios laborales con instituciones y empresas del sector productivo. |
| `ExternalInstitutionalLinkController`| `App\Http\Controllers`| Enlaces a plataformas externas del Estado (Minedu, Sunedu, Pronabec). |
| `HistoricalReviewController`| `App\Http\Controllers`| Hitos históricos para la línea de tiempo institucional. |
| `CarouselController` | `App\Http\Controllers` | Imágenes y llamadas a la acción del carrusel de la portada. |
| `AreaController` | `App\Http\Controllers` | Áreas funcionales y departamentos de la institución. |
| `RoleController` | `App\Http\Controllers` | Asignación de roles y permisos Spatie a nivel administrativo. |
| `DashboardController` | `App\Http\Controllers` | Panel central de administración con métricas consolidadas. |
| `ProfileController` | `App\Http\Controllers` | Edición del perfil de usuario y cambio de contraseña. |
| `Admin\LibraryController`| `App\Http\Controllers\Admin`| Repositorio central de biblioteca, importación masiva académica (`fetchAcademicResources`), lectores y reportes. |
| `Auth\AuthenticatedSessionController`| `App\Http\Controllers\Auth` | Inicio y cierre de sesión con rate limiting estricto (`throttle:login`). |
| `Auth\ConfirmablePasswordController`| `App\Http\Controllers\Auth` | Confirmación de contraseña para operaciones críticas. |
| `Auth\EmailVerificationNotificationController`| `App\Http\Controllers\Auth` | Emisión de notificaciones de verificación de correo. |
| `Auth\EmailVerificationPromptController`| `App\Http\Controllers\Auth` | Pantalla de aviso de verificación de correo. |
| `Auth\NewPasswordController`| `App\Http\Controllers\Auth` | Registro de nueva contraseña tras restablecimiento por token. |
| `Auth\PasswordController` | `App\Http\Controllers\Auth` | Actualización de contraseña autenticada. |
| `Auth\PasswordResetLinkController`| `App\Http\Controllers\Auth` | Envío de enlace de recuperación de contraseña con limitador. |
| `Auth\RegisteredUserController`| `App\Http\Controllers\Auth` | Registro de usuarios con validación de DNI en tiempo real (`register/verify-dni`). |
| `Auth\VerifyEmailController`| `App\Http\Controllers\Auth` | Verificación de firma criptográfica de correo. |

---

## 7. Frontend, Vistas Blade y Sistema de Diseño

El sistema contiene **147 plantillas Blade** organizadas en 36 directorios temáticos:

### 7.1. Estructura de Directorios de Vistas
- **Vistas Raíz (4):** `home.blade.php`, `aboutus.blade.php`, `dashboard.blade.php`, `study-program.blade.php`.
- **`aboutus/` (8):** Misión, historia, organigrama, plana jerárquica, plana docente, plana administrativa, consejo estudiantil y locales.
- **`admin/` (98 vistas en 25 subdirectorios):** Módulos administrativos de admisiones, áreas, blogs, carrusel, certificados, reclamos, cursos, títulos, documentos normativos, matrículas, ficha institucional, enlaces, historia, itinerarios, empleos, biblioteca (10 vistas), licenciamiento, módulos, partners, programas (con 5 subcarpetas de metadatos, competencias, campo laboral, módulos y requisitos), roles, becas, estadísticas, consejo estudiantil, TUPA y usuarios.
- **`admission/` (4):** CEPRE, examen de admisión, matrículas, becas y créditos.
- **`auth/` (6):** Login, registro con verificación de DNI, olvido de contraseña, reset, verificación de email y confirmación.
- **`errors/` (10):** Plantillas de error HTTP estilizadas (401, 402, 403, 404, 419, 429, 500, 503, layout e ilustraciones).
- **`job-board/` (1):** Portal público de convocatorias laborales.
- **`layouts/` (1):** Layout maestro institucional accesible con Schema.org y metadatos SEO.
- **`library/` (5):** Portada, catálogo/buscador, favoritos, lector inmersivo y layout de biblioteca.
- **`procedures/` (2):** Mesa de partes virtual y catálogo interactivo del TUPA.
- **`profile/` (4):** Perfil de usuario y formularios parciales de actualización.
- **`services/` (2):** Convocatorias laborales y enlaces institucionales.
- **`transparency/` (5):** Documentos de gestión, estadísticas, balances contables, licenciamiento CBC y Libro de Reclamaciones.

### 7.2. Principios de Interfaz y Estilos
- **Tipografía:** Familia tipográfica *Inter* cargada mediante `<link rel="preconnect">` para optimizar el Largest Contentful Paint (LCP).
- **Hero Header Multimedia en Programas de Estudio:** Soporte de carrusel fotográfico dinámico controlado por Alpine.js (`programHeroCarousel`) en `resources/views/study-program.blade.php`, activable mediante el atributo `enable_carousel` y texto descriptivo `carousel_alt_text`.
- **Paleta Cromática Institucional:** Azul Marino (`#0f172a`), Azul Cielo (`#38bdf8`), Blanco y temas por especialidad (Esmeralda para Agropecuaria, Rosa para Enfermería, Azul para Redes, Índigo para Administración, Verde Bosque para Forestal).
- **Marcado Estructurado JSON-LD:** Metadatos Schema.org para `EducationalOrganization` en la portada y `Course` en las páginas de carreras técnicas.

---

## 8. Seguridad, Integridad y Políticas de Red

### 8.1. Protección Contra Ataques Web
- **Inyección SQL:** Mitigada al 100% mediante PDO bindings parametrizados en Eloquent ORM.
- **Cross-Site Scripting (XSS):** Todas las salidas en vistas Blade utilizan escape automático `{{ ... }}`.
- **Cross-Site Request Forgery (CSRF):** Middleware `VerifyCsrfToken` activo en todas las mutaciones POST, PUT, PATCH y DELETE.
- **Protección de Almacenamiento Fallback (`/storage/{path}`):** Validación estricta mediante `str_starts_with($realTarget, $realPublicDisk)` y `is_file()` para evitar vulnerabilidades de Directory Traversal.
- **Rate Limiting:**
  - Registro de usuarios: `throttle:6,1`.
  - Inicio de sesión: `throttle:login` (RateLimiter en `AppServiceProvider`).
  - Envío de reclamos al Libro de Reclamaciones: `throttle:5,1`.
  - Recuperación de contraseña: `throttle:5,1`.

### 8.2. Almacenamiento y Cifrado
- Contraseñas procesadas mediante el algoritmo Bcrypt con factor de costo `rounds = 12`.
- Archivos de CVs, documentos normativos y fotos almacenados en disco `public` o `private` según el nivel de sensibilidad.

---

## 9. Estrategia de Pruebas y Aseguramiento de Calidad (QA)

### 9.1. Catálogo de Pruebas Automatizadas (33 Suites)
Ubicadas en `tests/Feature/` (31 pruebas) y `tests/Unit/` (2 pruebas), ejecutadas sobre PHPUnit 11:

1. **Biblioteca y Servicio Académico:**
   - `tests/Feature/LibraryAcademicBulkImportTest.php`: Verifica autorización por roles en importación académica, vista de modal con iframe visor, almacenamiento de libros externos, deduplicación y registro en `library_access_logs`.
   - `tests/Feature/LibraryModuleTest.php`: Pruebas de CRUD de libros, asignación a programas y streaming.
   - `tests/Unit/AcademicLibraryServiceTest.php`: Pruebas unitarias de conexión con Europe PMC, Open Library/Internet Archive, SciELO, Redalyc, DOAJ, OpenAlex, Crossref, sanitización de sintaxis Lucene y resolución multi-tier de DOIs.
2. **Certificación Digital y Validación QR:**
   - `tests/Feature/CertificateFormatAndValidationTest.php`: Verifica el formato de certificados (capacitación y modular), generación de códigos QR, endpoint público de validación y generación de PDF.
3. **Bolsa de Trabajo y Scraping:**
   - `tests/Feature/AdminJobsBulkActionsTest.php`: Pruebas de borrado masivo de empleos, vaciado de tabla y filtros heurísticos.
4. **SEO e Indexación:**
   - `tests/Feature/SeoIndexingTest.php`: Verifica sintaxis de `robots.txt`, redirección 301 de `robot.txt`, validez XML de `sitemap.xml`, inclusión de las 5 carreras y comando `php artisan sitemap:generate`.
5. **Transparencia, Balances y Licenciamiento:**
   - `tests/Feature/AccountBalanceManagementTest.php`: Balances financieros, importación Excel y permiso restrictivo `can:gestionar-inversiones`.
   - `tests/Feature/TransparencyPublicTest.php`: Visualización pública de estadísticas, grados, documentos y balances.
   - `tests/Feature/LicensingPublicTest.php` y `tests/Feature/AdminLicensingTest.php`: Fases de licenciamiento y fijación de etapa actual.
6. **Programas de Estudio y UI:**
   - `tests/Feature/StudyProgramPublicTest.php`: Renderizado de carreras técnicas, competencias y perfiles.
   - `tests/Feature/StudyProgramImagesTest.php` y `tests/Feature/AdminStudyProgramTest.php`: Gestión de carrusel fotográfico, orden e imagen principal.
7. **Admisión, CEPRE y Becas:**
   - `tests/Feature/CeprePublicTest.php` y `tests/Feature/AdminAdmissionTest.php`: Cálculo proyectado de ciclos y convocatorias.
   - `tests/Feature/ScholarshipBeneficiariesFeatureTest.php`: Gestión de becas y padrón de beneficiarios con PDFs.
8. **Usuarios, Roles y Autenticación:**
   - `tests/Feature/UserCreateTest.php`, `tests/Feature/UserBulkImportTest.php`, `tests/Feature/UserBulkDeleteTest.php`, `tests/Feature/UserPasswordUpdateTest.php`: Ciclo de vida completo del usuario.
   - `tests/Feature/StudentLoginTest.php`, `tests/Feature/StudentSidebarTest.php`, `tests/Feature/ProfileTest.php`, `tests/Feature/AdminTeacherRoleTest.php`: Autenticación, perfiles y permisos docentes.
9. **Institucional y Trámites:**
   - `tests/Feature/AboutUsPublicTest.php`, `tests/Feature/HistoryPublicTest.php`, `tests/Feature/AdminHistoryTest.php`: Identidad institucional.
   - `tests/Feature/ProceduresPublicTest.php`: Procedimientos TUPA y mesa de partes.
   - `tests/Feature/ClaimTest.php` y `tests/Feature/AdminClaimTest.php`: Libro de Reclamaciones virtual y atención.
   - `tests/Feature/EnterpriseTest.php`: Ficha institucional.
   - `tests/Feature/ExampleTest.php` y `tests/Unit/ExampleTest.php`: Pruebas base de humo.

### 9.2. Estándar de Código (Laravel Pint)
El proyecto cuenta con configuración para el linter/formateador oficial de Laravel:
```bash
vendor/bin/pint --dirty --format agent
```
Garantiza el cumplimiento uniforme de las convenciones de la comunidad Laravel.

---

## 10. Procedimiento de Puesta en Producción

1. **Compilación de Dependencias:**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   ```
2. **Migración y Estructuración de Datos:**
   ```bash
   php artisan migrate --force
   php artisan storage:link
   ```
3. **Optimización de Rendimiento en Caché:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
4. **Regeneración de Mapa del Sitio (SEO):**
   ```bash
   php artisan sitemap:generate
   ```
5. **Configuración del Servidor Web:**
   Apuntar el `DocumentRoot` exclusivamente a la carpeta `public/` del proyecto, habilitando `mod_rewrite` en Apache o la directiva `try_files $uri $uri/ /index.php?$query_string;` en Nginx.
