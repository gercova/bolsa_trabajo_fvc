# PDR — Product Definition Report & Architectural Specification
## Plataforma Integral Web, Bolsa de Trabajo, Biblioteca Virtual y Certificación Digital
### IESTP "Francisco Vigo Caballero" — Uchiza, San Martín

---

## Control del Documento

| Campo | Detalle |
| :--- | :--- |
| **Documento** | Product Definition Report (PDR) / Technical Architecture Document |
| **Proyecto** | Plataforma Integral IESTP Francisco Vigo Caballero (Bolsa FVC) |
| **Versión** | 2.5.0-PROD |
| **Entorno de Referencia** | PHP 8.3 / Laravel 11.31 / MySQL 8.0+ / Tailwind CSS 3.4 |
| **URL Oficial de Producción** | `https://iestpfranciscovigocaballero.edu.pe/` |
| **Estado** | Aprobado para Producción y Auditoría Técnica |
| **Última Actualización** | Octubre 2026 |

---

## 1. Visión del Producto y Objetivos Estratégicos

### 1.1. Contexto Institucional
El **Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero"** (IESTP FVC), ubicado en el distrito de Uchiza, provincia de Tocache, departamento de San Martín, Perú, es una institución pública de educación superior licenciada por el Ministerio de Educación (MINEDU) que brinda formación técnica profesional a Nombre de la Nación.

### 1.2. Propósito del Sistema
El presente sistema unifica y digitaliza los procesos clave de la institución a través de una arquitectura web modular y resiliente:
1. **Presencia Institucional y Transparencia Activa:** Cumplimiento de la Ley de Transparencia y Acceso a la Información Pública, publicación de documentos de gestión, estadísticas de admisión y matrícula, balances contables y avance de licenciamiento (Condiciones Básicas de Calidad - CBC).
2. **Promoción de Carreras Profesionales:** Difusión de los 5 programas de estudio oficiales, mallas curriculares, competencias técnicas y perfiles laborales.
3. **Admisión y Matrícula Predictiva:** Gestión integral de ciclos formativos ordinarios, extraordinarios y CEPRE, con proyección dinámica de periodos futuros.
4. **Intermediación Laboral (Bolsa de Trabajo):** Conexión entre estudiantes/egresados y empresas del sector productivo local, regional y nacional, complementada con un motor automatizado de scraping de empleo.
5. **Certificación Digital Soberana con QR:** Emisión y validación instantánea de certificados de capacitación y modulares mediante códigos QR generados directamente en el servidor sin dependencias externas.
6. **Biblioteca Virtual e Investigación:** Repositorio digital con lector integrado, streaming protegido de PDFs y sincronización con repositorios de investigación de acceso abierto a nivel mundial.
7. **Trámites y TUPA Virtual:** Digitalización de requisitos, tasas, plazos y ventanilla de Mesa de Partes.

---

## 2. Actores del Sistema y Matriz de Control de Acceso (RBAC)

El sistema implementa control de acceso basado en roles (Role-Based Access Control) mediante el paquete oficial `spatie/laravel-permission`.

### 2.1. Definición de Roles
- **Director General:** Máxima autoridad institucional. Posee supervisión y permisos globales sobre todos los módulos, incluyendo la autorización de vaciado y reseteo de balances financieros.
- **Administrador (Admin):** Gestión operativa técnica, mantenimiento de usuarios, seguridad, auditoría y parametrización institucional.
- **Docente:** Gestión de asignaturas, módulos de capacitación, participación en comités curriculares y acceso al catálogo de biblioteca.
- **Coordinador de Área:** Jefaturas de programas de estudio con facultades para editar competencias, requisitos y certificaciones modulares de su especialidad.
- **Coordinador de Calidad:** Supervisión del TUPA, Libro de Reclamaciones y auditoría de etapas de licenciamiento institucional.
- **Coordinador de Empleabilidad:** Gestión de ofertas laborales, convenios con empresas (Partners) y seguimiento a graduados.
- **Coordinador de Educación Continua:** Programación de cursos extracurriculares y emisión de certificados institucionales.
- **Coordinador de Investigación:** Repositorio institucional y publicaciones científicas.
- **Personal Administrativo:** Gestión de trámites documentarios, estadísticas y atención de reclamos.
- **Lector (Estudiante / Egresado):** Acceso al portal de Biblioteca Virtual, favoritos, visor de PDFs y postulación a vacantes de empleo con carga de CV.
- **Público General / Visitante:** Acceso no autenticado al portal informativo, consulta de carreras, verificación pública de certificados por QR/código y registro en Libro de Reclamaciones.

### 2.2. Matriz de Permisos del Sistema

| Permiso (`name`) | Descripción Operativa | Roles Asignados |
| :--- | :--- | :--- |
| `ver-dashboard` | Acceso a métricas generales y accesos rápidos en `/admin-dashboard`. | Director, Admin, Docente, Todos los Coordinadores, Administrativo |
| `gestionar-examenes` | Creación y edición de convocatorias de admisión, CEPRE y vacantes. | Director, Admin, Docente, Coord. de Área |
| `gestionar-programas` | Edición de programas de estudio, competencias, perfiles y metadatos. | Director, Admin, Docente, Coord. de Área, Coord. de Calidad, Coord. Educación Continua |
| `gestionar-tupa` | Mantenimiento de categorías y procedimientos TUPA. | Director, Admin, Coord. de Calidad, Administrativo |
| `gestionar-trabajos` | Publicación de ofertas, scraping y revisión de candidatos. | Director, Admin, Coord. de Empleabilidad |
| `gestionar-usuarios` | Altas, bajas, reseteo de credenciales e importación Excel de usuarios. | Director, Admin |
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
   - *Métodos:* `getDefault()` (provee datos predeterminados en caso de tabla vacía).
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
    - *Campos:* `name`, `slug`, `description`, `details`, `order`, `is_active`.
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
    - *Campos:* `study_program_id`, `name`, `description`, `code`, `is_active`.
    - *Relaciones:* `belongsTo(StudyProgram::class)`, `hasMany(Module::class)`, `hasMany(Itinerary::class)`, `hasMany(Certificate::class)`.
17. **`Module`**
    - *Tabla:* `modules`
    - *Campos:* `course_id`, `name`, `hours`, `credits`, `order`, `is_active`.
    - *Relaciones:* `belongsTo(Course::class)`, `hasMany(Itinerary::class)`.
18. **`Itinerary`**
    - *Tabla:* `itineraries`
    - *Campos:* `course_id`, `module_id`, `code`, `name`, `is_active`.

### 3.3. Certificación Digital y Notas
19. **`Certificate`**
    - *Tabla:* `certificates`
    - *Campos:* `code`, `certificate_code`, `user_id`, `course_id`, `issue_date`, `start_date`, `end_date`, `duration`, `modality`, `qr_code_path`, `download_count`, `is_active`.
    - *Relaciones:* `belongsTo(User::class)`, `belongsTo(Course::class)`, `hasMany(CertificateDetail::class)`.
20. **`CertificateDetail`**
    - *Tabla:* `certificate_details`
    - *Campos:* `certificate_id`, `module_id`, `score`, `observations`.
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
    - *Campos:* `study_program_id`, `title`, `slug`, `author`, `publisher`, `publication_year`, `isbn`, `edition`, `summary`, `cover_image_path`, `pdf_file_path`, `external_pdf_url`, `views_count`, `downloads_count`, `source_type` (local/external/academic), `doi`, `is_active`.
    - *Relaciones:* `belongsTo(StudyProgram::class)`, `hasMany(BookFavorite::class)`, `hasMany(LibraryAccessLog::class)`.
39. **`BookFavorite`**
    - *Tabla:* `book_favorites`
    - *Campos:* `user_id`, `book_id`.
40. **`LibraryAccessLog`**
    - *Tabla:* `library_access_logs`
    - *Campos:* `user_id`, `book_id`, `ip_address`, `action` (view/stream/download).

### 3.9. Usuarios, Roles y Seguridad
41. **`User`**
    - *Tabla:* `users`
    - *Campos:* `dni`, `names`, `birthdate`, `mother_tongue`, `phone`, `address`, `sex`, `ubigeo`, `email`, `photo_profile`, `cv_file`, `role`, `job_position`, `password`, `is_active`.
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

### 4.1. Rutas Públicas e Institucionales (Canónicas)
| Método | URI | Nombre de Ruta | Controlador@Método | Finalidad |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/` | `inicio` | `AppController@index` | Portada institucional, hero carousel, accesos y métricas. |
| `GET` | `/admision-y-matricula/cepre-fvc` | `cepre-fvc` | `AppController@ceprefvc` | Vacantes, costos y cronograma del ciclo CEPRE proyectado. |
| `GET` | `/admision-y-matricula/examen-de-admision` | `examen-de-admision` | `AppController@admissionExam` | Convocatoria Ordinaria/Extraordinaria y vacantes netas. |
| `GET` | `/admision-y-matricula/matriculas` | `matriculas` | `AppController@enrollments` | Cronogramas de matrícula ordinaria y extraordinaria. |
| `GET` | `/admision-y-matriculas/becas-y-creditos`| `becas-y-creditos` | `AppController@scholarshipsAndCredits` | Catálogo de becas y resoluciones de beneficiarios. |
| `GET` | `/programas-de-estudios` | `programas-de-estudio` | `AppController@studyPrograms` | Catálogo general de los 5 programas de estudio. |
| `GET` | `/programas-de-estudios/{program:slug}` | `programas-de-estudio.detalle` | `AppController@program` | Malla, competencias, campo laboral y fotos de carrera. |
| `GET` | `/transparencia/documentos-de-gestion` | `documentos-de-gestion` | `AppController@documentsManagement` | Documentos oficiales normativos (PEI, PAT, PCI). |
| `GET` | `/transparencia/estadisticas` | `estadisticas` | `AppController@statistics` | Dashboards consolidados de matrícula, admisión y género. |
| `GET` | `/transparencia/inversion-y-gestion` | `inversion-y-gestion` | `AppController@managementReports` | Balances financieros mensuales y ejecución presupuestal. |
| `GET` | `/transparencia/licenciamiento` | `licenciamiento` | `AppController@licensment` | Progreso de las Condiciones Básicas de Calidad (CBC). |
| `GET` | `/transparencia/libro-de-reclamaciones` | `libro-de-reclamaciones` | `AppController@complaintsBook` | Formulario virtual de atención a quejas y reclamos. |
| `POST`| `/transparencia/libro-de-reclamaciones` | `libro-de-reclamaciones.store` | `AppController@storeClaim` | Registro seguro de reclamos (rate limit `throttle:5,1`). |
| `GET` | `/tramites/mesa-de-partes` | `mesa-de-partes` | `AppController@partsTable` | Guía de recepción de expedientes y horarios. |
| `GET` | `/tramites/tupa` | `tupa` | `AppController@tupa` | Catálogo interactivo de procedimientos y aranceles. |
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
| `GET` | `/validar-certificado/{code?}` | `validar-certificado` | `AppController@validateCertificate` | Portal de verificación pública de certificados por QR/DNI. |
| `GET` | `/validar-certificado/{code}/imprimir`| `validar-certificado.print` | `AppController@printCertificate` | Hoja oficial de certificación con sellos y notas. |
| `GET` | `/sitemap.xml` | `sitemap` | `SitemapController@index` | Generación y entrega del mapa XML canónico para Google. |
| `ANY` | `/robot.txt` | *(Redirect)* | `RedirectController` | Redirección canónica a `/robots.txt`. |
| `GET` | `/storage/{path}` | `storage.fallback` | `Closure` | Servidor seguro de archivos multimedia con mime detection. |

### 4.2. Portal de Biblioteca Virtual (Autenticado: `auth`)
| Método | URI | Nombre de Ruta | Controlador@Método | Finalidad |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/biblioteca` | `biblioteca.index` | `LibraryReaderController@index` | Portada de la biblioteca virtual con libros destacados. |
| `GET` | `/biblioteca/buscar` | `biblioteca.search` | `LibraryReaderController@search` | Búsqueda por palabras clave, DOI y filtros de carrera. |
| `GET` | `/biblioteca/mi-biblioteca` | `biblioteca.favorites` | `LibraryReaderController@myLibrary` | Estante personal de libros favoritos del usuario. |
| `GET` | `/biblioteca/leer/{book:slug}` | `biblioteca.read` | `LibraryReaderController@read` | Visor inmersivo de lectura dentro del navegador. |
| `GET` | `/biblioteca/stream/{book:slug}` | `biblioteca.stream` | `LibraryReaderController@stream` | Transmisión segura del flujo de bytes del documento PDF. |
| `POST`| `/biblioteca/favorito/{book}` | `biblioteca.favorite.toggle` | `LibraryReaderController@toggleFavorite` | Alternar inclusión/exclusión de favoritos. |

### 4.3. Panel de Gestión Administrativa (`auth`)
| Prefijo de URI | Nombre Base | Controlador Principal | Módulos y Acciones |
| :--- | :--- | :--- | :--- |
| `/admin-dashboard` | `admin.dashboard.*` | `DashboardController` | KPIs globales, accesos directos y estado institucional. |
| `/admin-perfil` | `admin.profile.*` | `ProfileController` | Edición de credenciales, datos de contacto y avatar. |
| `/admin-blogs` | `admin.blogs.*` | `BlogController` | CRUD de noticias, galerías y estado de publicación. |
| `/admin-exams` | `admin.exams.*` | `AdmissionsController` | Procesos de admisión, vacantes por carrera e imágenes. |
| `/admin-scholarships` | `admin.scholarships.*` | `ScholarshipController` | Becas y resoluciones de beneficiarios (`ScholarshipBeneficiaryController`). |
| `/admin-biblioteca` | `admin.library.*` | `Admin\LibraryController` | Catálogo de libros, repositorios, reportes y sincronización académica. |
| `/admin-tupa` | `admin.tupa.*` | `TupaController` | Documentos TUPA, categorías temáticas y procedimientos. |
| `/admin-documentos` | `admin.documents.*` | `ManagementDocumentController` | Carga y actualización de documentos de gestión (PEI, PAT). |
| `/admin-areas` | `admin.areas.*` | `AreaController` | Catálogo de unidades y dependencias institucionales. |
| `/admin-programas` | `admin.programs.*` | `StudyProgramsController` | Carreras, certificaciones modulares, competencias, perfiles y requisitos. |
| `/admin-cursos` | `admin.courses.*` | `CourseController` | Cursos y talleres de formación y educación continua. |
| `/admin-modulos` | `admin.modules.*` | `ModuleController` | Módulos formativos asociados a cursos. |
| `/admin-certificados` | `admin.certificates.*` | `CertificateController` | Emisión, importación masiva Excel, notas y exportación PDF. |
| `/admin-itinerarios` | `admin.itineraries.*` | `ItineraryController` | Asignación de itinerarios formativos. |
| `/admin-trabajos` | `admin.works.*` | `JobsController` | Empleos, convocatorias internas, scraping y vaciado masivo. |
| `/admin-usuarios` | `admin.users.*` | `UsersController` | Directorio de usuarios, importación masiva Excel y asignaciones. |
| `/admin-docentes-roles`| `admin.teacher-roles.*`| `TeacherRoleController` | Asignación de docentes a carreras y cargos de coordinación. |
| `/admin-consejo-estudiantil`| `admin.student-council.*`| `StudentCouncilController` | Cargos y directiva del consejo de estudiantes por periodo. |
| `/admin-enlaces` | `admin.links.*` | `ExternalInstitutionalLinkController` | Enlaces externos de interés gubernamental y académico. |
| `/admin-roles` | `admin.roles.*` | `RoleController` | Administración de roles y permisos Spatie. |
| `/admin-reclamos` | `admin.claims.*` | `ClaimsController` | Seguimiento, resolución y estado de reclamos Indecopi. |
| `/admin-estadisticas` | `admin.statistics.*` | `StadisticController` | Carga masiva Excel y gestión de registros de matrícula/admisión. |
| `/admin-titulos` | `admin.degree-records.*` | `DegreeRecordsController` | Padrón histórico de egresados titulados y certificados. |
| `/admin-licenciamiento`| `admin.licensing.*` | `LicensingController` | Fases de licenciamiento y designación de etapa vigente. |
| `/admin-socios` | `admin.partners.*` | `PartnersController` | Directorio de empresas aliadas y logotipos de convenios. |
| `/admin-empresa` | `admin.enterprise.*` | `EnterpriseController` | Datos institucionales, RUC, redes y directivos. |
| `/admin-matriculas` | `admin.enrollments.*` | `EnrollmentScheduleController` | Fechas límite y requisitos de matrícula ordinaria/extraordinaria. |
| `/admin-historia` | `admin.history.*` | `HistoricalReviewController` | Hitos históricos y cronología institucional. |
| `/admin-carrusel` | `admin.carousel.*` | `CarouselController` | Banners promocionales de la portada con orden interactivo. |
| `/admin-inversiones` | `admin.account-balances.*`| `AccountBalanceController` | Carga de balances contables, gastos y borrado de periodos. |

---

## 5. Especificación de Subsistemas Críticos

### 5.1. Motor de Certificación Digital y Código QR Autónomo (`QrCodeService`)
- **Problema Abordado:** Depender de APIs de terceros (como Google Charts o generadores externos) genera vulnerabilidades de privacidad, lentitud y riesgo de caída si el servidor pierde conexión a internet.
- **Implementación Arquitectural:**
  - El servicio `App\Services\QrCodeService` implementa el estándar ISO/IEC 18004 en PHP puro.
  - Genera matrices de datos binarios y realiza la corrección de errores mediante el algoritmo Reed-Solomon (Nivel L / M).
  - Emite el código QR como vector **SVG puro** o **PNG nativo**, incrustando la URL canónica de verificación:
    `https://iestpfranciscovigocaballero.edu.pe/validar-certificado?code={CODIGO_UNICO}`
  - El validador público (`AppController@validateCertificate`) permite la búsqueda por QR, por código alfanumérico o por DNI del estudiante, mostrando el historial completo de certificaciones, módulos aprobados, notas y botón de impresión legalizada.

### 5.2. Motor de Búsqueda Académica y Streaming de PDFs (`AcademicLibraryService`)
- **Integración Multifuente:**
  - `AcademicLibraryService` se conecta mediante HTTP asíncrono con los principales índices de investigación:
    - **Crossref REST API:** Indexación por DOI, metadatos y enlaces de texto completo.
    - **OpenAlex API:** Análisis de citas, tópicos y producción científica.
    - **DOAJ (Directory of Open Access Journals):** Revistas arbitradas de acceso libre.
    - **arXiv & PubMed:** Ciencias exactas, computación y ciencias de la salud.
- **Transmisión de Documentos (PDF Streaming):**
  - Implementado en `LibraryReaderController@stream`.
  - Resuelve restricciones de CORS cuando los documentos se alojan en repositorios remotos.
  - Entrega el flujo con cabeceras `Content-Type: application/pdf` y `Content-Disposition: inline`, permitiendo la lectura fluida en el navegador sin exponer la URL fuente ni saturar la memoria RAM del servidor mediante streaming por bloques.

### 5.3. Motor de Intermediación Laboral y Web Scraping (`JobsController` / `ScrapeJobBoard`)
- **Filtros Heurísticos de Empleo:**
  - Mapeo algorítmico específico para las 5 carreras técnicas del instituto:
    - *Redes y TI:* "tecnico redes comunicaciones", "soporte tecnico", "administrador redes", "tecnico informatico".
    - *Asistencia Administrativa:* "asistente administrativo", "auxiliar administrativo", "secretaria asistente".
    - *Enfermería Técnica:* "tecnico enfermeria", "auxiliar enfermeria", "enfermero tecnico".
    - *Manejo Forestal:* "tecnico forestal", "manejo forestal", "recursos naturales".
    - *Producción Agropecuaria:* "tecnico agropecuario", "produccion agricola", "tecnico pecuario".
  - **Filtro Geográfico Estricto:** Exclusión de términos de países extranjeros (Colombia, México, Chile, España, etc.) y validación obligatoria contra las 24 regiones del Perú y ciudades principales.
- **Extracción Automática:**
  - Comando `php artisan scrape:jobs {source_url} {source_name}` utilizando `Symfony\Component\DomCrawler\Crawler`.

### 5.4. Motor de Admisión con Proyección Dinámica de Ciclos
- **Cálculo Predictivo:**
  - En `AppController@ceprefvc` y `AppController@admissionExam`, el sistema detecta dinámicamente el año en curso y proyecta el siguiente periodo académico:
    ```php
    $projectedYear = now()->year + 1;
    $projectedPeriod = $projectedYear . '-I';
    ```
- **Deducción de Vacantes CEPRE:**
  - Las vacantes asignadas al CEPRE se descuentan automáticamente de la meta global de admisión ordinaria para garantizar que no se sobrepase la capacidad instalada autorizada por el MINEDU.

### 5.5. Arquitectura SEO e Indexación para Google Search Console
- **`robots.txt` / `robot.txt`:**
  - Ubicación física en `public/robots.txt` y redirección 301 en `routes/web.php`.
  - Protege rutas privadas de administración y autenticación.
  - Habilita la indexación de recursos estáticos (`/build/`, `/css/`, `/js/`, `/images/`, `/storage/`).
  - Declara el mapa canónico: `Sitemap: https://iestpfranciscovigocaballero.edu.pe/sitemap.xml`.
- **`sitemap.xml` Canónico:**
  - Generado en `public/sitemap.xml` con las 23 rutas públicas oficiales.
  - Prioridades asignadas estratégicamente: Portada (1.0), Admisión y Bolsa de Trabajo (0.9), Carreras Técnicas (0.8), Transparencia y Trámites (0.7/0.6).
  - Comando de regeneración automatizada: `php artisan sitemap:generate`.
- **Verificación Google Search Console:**
  - Metaetiqueta oficial inyectada en el `<head>` de `resources/views/layouts/app.blade.php`:
    ```html
    <meta name="google-site-verification" content="n-kFDeVvGTkr1sar6qNZRP1l5kFEqJzI-4PDgCCTC2s" />
    ```

---

## 6. Arquitectura de Controladores y Métodos

El sistema organiza su lógica en **36 Controladores**:

| Controlador | Namespace | Responsabilidad Primaria |
| :--- | :--- | :--- |
| `AppController` | `App\Http\Controllers` | Despacho de todas las páginas del portal público (26 métodos: inicio, carreras, admisión, transparencia, validación QR). |
| `SitemapController` | `App\Http\Controllers` | Construcción de XML estándar para Google Search Console y despacho dinámico. |
| `LibraryReaderController`| `App\Http\Controllers` | Experiencia de lectura en biblioteca virtual, streaming seguro y favoritos. |
| `JobsController` | `App\Http\Controllers` | Administración de empleos, convocatorias internas, scraping y limpieza masiva. |
| `CertificateController` | `App\Http\Controllers` | Gestión de certificados, notas, plantillas Excel y exportaciones en PDF. |
| `StudyProgramsController`| `App\Http\Controllers`| Mantenimiento de las 5 carreras, módulos, competencias y metadatos UI. |
| `UsersController` | `App\Http\Controllers` | Gestión de cuentas de usuario, asignación de roles e importación/exportación Excel. |
| `AccountBalanceController`| `App\Http\Controllers`| Balances contables, reportes de inversión y borrado controlado por periodos. |
| `AdmissionsController` | `App\Http\Controllers` | Convocatorias de admisión ordinaria, extraordinaria y CEPRE FVC. |
| `TupaController` | `App\Http\Controllers` | Mantenimiento de documentos normativos, categorías y procedimientos TUPA. |
| `DegreeRecordsController`| `App\Http\Controllers`| Padrón histórico de grados y títulos oficiales con importación Excel. |
| `StadisticController` | `App\Http\Controllers` | Registro masivo de estudiantes matriculados e ingresantes para estadísticas. |
| `LicensingController` | `App\Http\Controllers` | Monitoreo del avance de las Condiciones Básicas de Calidad (CBC). |
| `BlogController` | `App\Http\Controllers` | Publicación de notas de prensa, noticias institucionales y comunicados. |
| `ClaimsController` | `App\Http\Controllers` | Gestión del Libro de Reclamaciones virtual y cambio de estado de atención. |
| `CourseController` | `App\Http\Controllers` | Cursos modulares de capacitación y educación continua. |
| `ModuleController` | `App\Http\Controllers` | Módulos formativos y carga horaria por curso. |
| `ItineraryController` | `App\Http\Controllers` | Itinerarios formativos oficiales. |
| `ScholarshipController` | `App\Http\Controllers` | Programas de becas estudiantiles y vacantes. |
| `ScholarshipBeneficiaryController`| `App\Http\Controllers` | Nómina de estudiantes beneficiarios de becas por periodo. |
| `EnrollmentScheduleController`| `App\Http\Controllers`| Cronogramas de matrícula ordinaria y extraordinaria. |
| `TeacherRoleController` | `App\Http\Controllers` | Designación de docentes a carreras y cargos de coordinación académica. |
| `StudentCouncilController`| `App\Http\Controllers`| Registro de la junta directiva estudiantil por periodos lectivos. |
| `EnterpriseController` | `App\Http\Controllers` | Edición de la ficha institucional, misión, visión y directivos. |
| `PartnersController` | `App\Http\Controllers` | Alianzas y convenios con instituciones y empresas del sector productivo. |
| `ExternalInstitutionalLinkController`| `App\Http\Controllers`| Enlaces a plataformas externas del Estado (Minedu, Sunedu, Siseve). |
| `HistoricalReviewController`| `App\Http\Controllers`| Hitos históricos para la línea de tiempo institucional. |
| `CarouselController` | `App\Http\Controllers` | Imágenes y llamadas a la acción del carrusel de la portada. |
| `AreaController` | `App\Http\Controllers` | Áreas funcionales y departamentos de la institución. |
| `RoleController` | `App\Http\Controllers` | Asignación de roles y permisos Spatie a nivel administrativo. |
| `DashboardController` | `App\Http\Controllers` | Panel central de administración con métricas consolidadas. |
| `ProfileController` | `App\Http\Controllers` | Edición del perfil de usuario y cambio de contraseña. |
| `Admin\LibraryController`| `App\Http\Controllers\Admin`| Repositorio central de biblioteca, lectores, estadísticas y sync académico. |
| `Auth\*` (9 controladores)| `App\Http\Controllers\Auth` | Flujo de autenticación completo (Login, Registro, Recuperación de Password). |

---

## 7. Frontend, Vistas Blade y Sistema de Diseño

El sistema contiene **145 plantillas Blade** estructuradas en jerarquía de layouts:

### 7.1. Jerarquía de Plantillas Maestras
1. **`layouts.app` (`resources/views/layouts/app.blade.php`):**
   - Layout maestro del portal público.
   - Integra cabecera SEO, metadatos OpenGraph, Twitter Cards, Schema.org (JSON-LD para EducationalOrganization), etiqueta de verificación de Google Search Console, fuentes de Google Fonts (Inter), CDN de Tailwind CSS 3.4 y barra de navegación accesible.
2. **`library.layout` (`resources/views/library/layout.blade.php`):**
   - Layout especializado para el lector de Biblioteca Virtual con barra de búsqueda rápida, selector de especialidad y acceso al estante personal de favoritos.
3. **`admin` (`resources/views/admin/...`):**
   - Vistas administrativas provistas de barra lateral colapsable, breadcrumbs, tablas de datos paginadas con orden interactivo, modales de confirmación para borrado masivo y formularios de importación/exportación Excel.

### 7.2. Principios de Interfaz y Estilos
- **Tipografía:** Familia tipográfica *Inter* cargada mediante `<link rel="preconnect">` para optimizar el Largest Contentful Paint (LCP).
- **Paleta de Colores:** Basada en tonos corporativos institucionales (Azul Marino profundo `#0f172a`, Azul Cielo `#38bdf8`, Blanco y acentos por especialidad técnica: Esmeralda para Agropecuaria, Rosa para Enfermería, Azul para Redes, Índigo para Administración, Verde Bosque para Forestal).
- **Diseño Responsivo:** Soporte fluido para dispositivos móviles, tablets y pantallas de escritorio de alta resolución.
- **Micro-animaciones e Interactividad:** Clases de transición suave, elevación de tarjetas (`card-lift`) y revelado progresivo al scroll (`reveal`).

---

## 8. Seguridad, Integridad y Políticas de Red

### 8.1. Protección Contra Ataques Web
- **Inyección SQL:** Mitigada al 100% mediante el uso de PDO bindings parametrizados en Eloquent ORM.
- **Cross-Site Scripting (XSS):** Todas las salidas en vistas Blade utilizan escape automático `{{ ... }}`. Las pocas áreas que renderizan HTML estructurado están restringidas a personal administrativo verificado.
- **Cross-Site Request Forgery (CSRF):** Middleware `VerifyCsrfToken` activo en todas las rutas POST, PUT, PATCH y DELETE.
- **Protección de Almacenamiento Fallback (`/storage/{path}`):** Validación estricta mediante `str_starts_with($realTarget, $realPublicDisk)` y `is_file()` para evitar vulnerabilidades de Directory Traversal.
- **Rate Limiting (Limitador de Tasa):**
  - Registro de usuarios: `throttle:6,1` (6 intentos por minuto).
  - Inicio de sesión: `throttle:login`.
  - Envío de reclamos al Libro de Reclamaciones: `throttle:5,1`.
  - Recuperación de contraseña: `throttle:5,1`.

### 8.2. Almacenamiento y Cifrado
- Contraseñas procesadas mediante el algoritmo Bcrypt con factor de costo `rounds = 12`.
- Archivos de CVs, documentos normativos y fotos almacenados en disco `public` o `private` según el nivel de sensibilidad.

---

## 9. Estrategia de Pruebas y Aseguramiento de Calidad (QA)

### 9.1. Suite Automatizada de Pruebas
Ubicada en `tests/Feature/` y `tests/Unit/`, ejecutada sobre PHPUnit 11:
- `SeoIndexingTest.php`: Verifica la sintaxis de `robots.txt`, la redirección de `robot.txt`, la respuesta XML válida de `sitemap.xml`, la inclusión de las 5 carreras y la ejecución del comando `php artisan sitemap:generate`.
- `AboutUsPublicTest.php`: Verifica la disponibilidad de la página institucional, misión, visión y metaetiquetas SEO.
- `CertificateFormatAndValidationTest.php`: Prueba unitaria y funcional del formato de certificados, notas, código alfanumérico y validación por QR.
- `AdminJobsBulkActionsTest.php`: Pruebas de borrado masivo y filtros de empleo.
- `LibraryAcademicBulkImportTest.php`: Pruebas del servicio de conexión académica con repositorios de investigación.

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
