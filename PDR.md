# PDR — Product Definition Report & Architectural Specification
## Plataforma Integral Web, Bolsa de Trabajo, Biblioteca Virtual y Certificación Digital
### IESTP "Francisco Vigo Caballero" — Uchiza, San Martín

---

## Control del Documento

| Campo | Detalle |
| :--- | :--- |
| **Documento** | Product Definition Report (PDR) / Technical Architecture Document |
| **Proyecto** | Plataforma Integral IESTP Francisco Vigo Caballero (Bolsa FVC) |
| **Versión** | 3.1.0-PROD (Arquitectura 100% Single-Page Application Homologada) |
| **Entorno de Referencia** | PHP 8.3 / Laravel 11.31 / Inertia.js 3.5 / Vue 3.5 / Tailwind CSS 3.4 / Vite 8.3 / MySQL 8.0+ |
| **URL Oficial de Producción** | `https://iestpfranciscovigocaballero.edu.pe/` |
| **Estado** | Aprobado para Producción, Homologación y Auditoría Técnica — 100% SPA Certificada |
| **Última Actualización** | Noviembre 2026 |
| **Inventario Técnico Exhaustivo** | 45 Modelos • 44 Controladores • 42 FormRequests • 2 Servicios de Dominio • 319 Rutas • 116 Vistas Vue SPA • 2 Layouts Vue • 17 Plantillas Blade Esenciales • 55 Migraciones • 30 Seeders • 33 Tests • 2 Comandos CLI • 5 Imports • 6 Exports |

---

## 1. Visión del Producto y Objetivos Estratégicos

### 1.1. Contexto Institucional
El **Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero"** (IESTP FVC), ubicado en el distrito de Uchiza, provincia de Tocache, departamento de San Martín, Perú, es una institución pública de educación superior licenciada por el Ministerio de Educación (MINEDU) que brinda formación técnica profesional a Nombre de la Nación.

### 1.2. Propósito y Transformación Digital del Sistema
El presente sistema unifica y digitaliza los procesos misionales y de soporte de la institución a través de una arquitectura web reactiva **Single-Page Application (SPA)** impulsada por el protocolo **Inertia.js**, el motor frontend **Vue 3** y el backend **Laravel 11**:
1. **Presencia Institucional y Transparencia Activa:** Cumplimiento de la Ley de Transparencia y Acceso a la Información Pública, publicación de documentos de gestión (PEI, PAT, PCI, RI), padrón histórico de egresados titulados, estadísticas de admisión y matrícula por género/carrera, balances contables mensuales y seguimiento público de las Condiciones Básicas de Calidad (CBC) para el licenciamiento institucional.
2. **Promoción de Carreras Profesionales:** Difusión de los 5 programas de estudio oficiales (*Producción Agropecuaria, Enfermería Técnica, Administración de Redes y Comunicaciones, Asistencia Administrativa, Manejo Forestal*), perfiles de egreso, competencias técnicas de empleabilidad, mallas curriculares, galerías fotográficas y cabecera interactiva con carrusel multimedia dinámico.
3. **Admisión y Matrícula con Proyección Predictiva:** Gestión integral de convocatorias de examen ordinario, extraordinario y ciclo CEPRE FVC, con cálculo automático de periodos lectivos futuros y deducción inteligente de vacantes reservadas.
4. **Intermediación Laboral (Bolsa de Trabajo):** Ecosistema de empleabilidad para postulaciones de egresados con CV, publicación de convocatorias internas y motor de extracción automatizada (*scraping*) de ofertas laborales peruanas con filtro geográfico estricto.
5. **Certificación Digital Soberana con Código QR:** Emisión, consulta y validación instantánea de certificados de capacitación y modulares mediante códigos QR generados directamente en el servidor sin dependencias de APIs externas, complementado con calificación vigesimal y exportación masiva a PDF.
6. **Biblioteca Virtual e Investigación Abierta:** Repositorio institucional con estante de favoritos, lector inmersivo integrado, streaming seguro de PDFs binarios y sincronización multi-índice con 10 repositorios científicos mundiales (*Europe PMC, Open Library/Internet Archive, SciELO, Redalyc, DOAJ, OpenAlex, Crossref, Semantic Scholar, Scopus, Google Scholar*).
7. **Trámites y TUPA Virtual:** Digitalización de procedimientos administrativos, cálculo dinámico de aranceles en Soles (S/.) y porcentaje de la UIT vigente, plazos de atención y guía de recepción documental para Mesa de Partes.
8. **Libro de Reclamaciones Virtual:** Plataforma de registro de quejas y reclamos conforme a la normativa de Indecopi, asignación de código correlativo y limitación estricta de tráfico (*rate limiting*).

---

## 2. Actores del Sistema y Matriz de Control de Acceso (RBAC)

El sistema implementa control de acceso basado en roles (*Role-Based Access Control*) mediante el paquete oficial `spatie/laravel-permission`, autorización granular mediante directivas de `Gate` y el interceptor global `HandleInertiaRequests` que distribuye el estado de permisos a todos los componentes Vue.

### 2.1. Definición de Roles del Sistema
- **Director General:** Máxima autoridad institucional. Posee supervisión global sobre todos los módulos, incluyendo la autorización exclusiva de vaciado y reseteo de balances financieros (`can:gestionar-inversiones`).
- **Administrador (Admin):** Gestión operativa integral, mantenimiento de usuarios, seguridad, auditoría, parametrización institucional y configuración de módulos.
- **Docente:** Gestión de asignaturas, módulos de capacitación, participación en comités curriculares y consulta del catálogo de biblioteca.
- **Coordinador de Área:** Jefatura de programa de estudio con facultades para editar competencias, requisitos, metadatos, itinerarios y certificaciones modulares de su especialidad.
- **Coordinador de Calidad:** Supervisión del TUPA, Libro de Reclamaciones y auditoría de etapas de licenciamiento institucional (Condiciones Básicas de Calidad - CBC).
- **Coordinador de Empleabilidad:** Gestión de ofertas laborales, convenios con empresas aliadas (*Partners*), scraping de empleo y bolsa de trabajo.
- **Coordinador de Educación Continua:** Programación de cursos extracurriculares y emisión de certificados institucionales de capacitación y talleres.
- **Coordinador de Investigación:** Repositorio digital institucional y publicación de artículos académicos.
- **Personal Administrativo:** Gestión de trámites documentarios, estadísticas de transparencia y atención de reclamos de mesa de partes.
- **Lector (Estudiante / Egresado):** Acceso al portal de Biblioteca Virtual, favoritos, visor inmersivo de lectura, descarga de material y postulación a vacantes con carga de CV. Cuenta con soporte de correo personal (`personal_email`) para comunicaciones de graduados.
- **Público General / Visitante:** Acceso sin autenticación al portal informativo, consulta de carreras, verificación pública de certificados por código/DNI y registro en el Libro de Reclamaciones.

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

## 3. Diccionario de Datos y Catálogo Exhaustivo de Modelos Eloquent

La plataforma cuenta con **45 Modelos Eloquent** activos. A continuación se documenta su arquitectura de tablas, claves, atributos, relaciones y scopes:

### 3.1. Núcleo Institucional y Parametría
1. **`Enterprise`** (`app/Models/Enterprise.php`)
   - *Tabla:* `enterprise` (Clave primaria: `id`)
   - *Campos:* `ruc`, `company_name`, `trade_name`, `legal_representative_dni`, `legal_representative`, `email`, `phone_number_1`, `whatsapp_link`, `address`, `city`, `phrase`, `description`, `mission`, `vision`, `principles`, `values`, `logo_path`, `favicon_path`, `facebook_link`, `instagram_link`, `linkedin_link`.
   - *Métodos:* `getDefault()`: Provee un objeto DTO institucional predeterminado en caso de tabla vacía para uso en el middleware global `HandleInertiaRequests`.
2. **`Area`** (`app/Models/Area.php`)
   - *Tabla:* `area`
   - *Campos:* `name`, `code`, `user_id`.
   - *Relaciones:* `belongsTo(User::class)`.
3. **`Charge`** (`app/Models/Charge.php`)
   - *Tabla:* `charges`
   - *Campos:* `name`, `is_active`.
4. **`DocumentType`** (`app/Models/DocumentType.php`)
   - *Tabla:* `document_type`
   - *Campos:* `name`, `short_name`.
   - *Relaciones:* `hasMany(User::class)`.
5. **`HistoricalReview`** (`app/Models/HistoricalReview.php`)
   - *Tabla:* `historical_reviews`
   - *Campos:* `year`, `title`, `description`, `image_path`, `order`, `is_active`.
6. **`InstitutionalCarousel`** (`app/Models/InstitutionalCarousel.php`)
   - *Tabla:* `institutional_carousels`
   - *Campos:* `title`, `subtitle`, `cta_text`, `cta_link`, `order`, `is_active`.
   - *Relaciones:* `morphOne(Image::class, 'imageable')`.
7. **`Partner`** (`app/Models/Partner.php`)
   - *Tabla:* `partners`
   - *Campos:* `name`, `logo_path`, `website_link`, `is_active`.
8. **`ExternalInstitutionalLink`** (`app/Models/ExternalInstitutionalLink.php`)
   - *Tabla:* `external_institutional_links`
   - *Campos:* `title`, `url`, `logo_path`, `category`, `is_active`.
9. **`VisitorCounter`** (`app/Models/VisitorCounter.php`)
   - *Tabla:* `visitor_counters`
   - *Campos:* `ip_address`, `visited_date`, `visits_count`.
   - *Métodos:* `getTotalVisits()`, `getPaddedDigits($total, $length)`, `incrementViews($metricKey)`.

### 3.2. Programas de Estudio y Currícula
10. **`StudyProgram`** (`app/Models/StudyProgram.php`)
    - *Tabla:* `study_programs`
    - *Campos:* `name`, `slug`, `description`, `details`, `order`, `is_active`, `enable_carousel` (boolean), `carousel_alt_text` (text).
    - *Relaciones:* `hasOne(ProgramMeta::class)`, `hasMany(ProgramCompetency::class)`, `hasMany(ProgramJobField::class)`, `hasMany(ProgramRequirement::class)`, `hasMany(Module::class)`, `hasMany(Course::class)`, `morphMany(Image::class, 'imageable')`.
11. **`ProgramMeta`** (`app/Models/ProgramMeta.php`)
    - *Tabla:* `program_metas`
    - *Campos:* `study_program_id`, `icon`, `accent`, `bg_badge`, `tag`, `color_bar`, `glow_class`, `badge_class`, `accent_text`, `bullet_class`, `icon_bg_class`, `border_hover_class`, `badge_module_class`, `sidebar_icon_class`, `cta_bg_class`, `bar_color_class`.
    - *Relaciones:* `belongsTo(StudyProgram::class)`.
12. **`ProgramCompetency`** (`app/Models/ProgramCompetency.php`)
    - *Tabla:* `program_competencies`
    - *Campos:* `study_program_id`, `title`, `description`, `icon`, `order`, `is_active`.
    - *Relaciones:* `belongsTo(StudyProgram::class)`.
13. **`ProgramJobField`** (`app/Models/ProgramJobField.php`)
    - *Tabla:* `program_job_fields`
    - *Campos:* `study_program_id`, `description`, `order`, `is_active`.
    - *Relaciones:* `belongsTo(StudyProgram::class)`.
14. **`ProgramRequirement`** (`app/Models/ProgramRequirement.php`)
    - *Tabla:* `program_requirements`
    - *Campos:* `study_program_id`, `description`, `order`, `is_active`.
    - *Relaciones:* `belongsTo(StudyProgram::class)`.
15. **`ModularCertification`** (`app/Models/ModularCertification.php`)
    - *Tabla:* `modular_certification`
    - *Campos:* `model_type`, `model_id`, `code`, `name`, `description`, `is_active`.
16. **`Course`** (`app/Models/Course.php`)
    - *Tabla:* `courses`
    - *Campos:* `study_program_id`, `name`, `description`, `code`, `certificate_type` (`capacitacion` o `modular`), `event_name`, `is_active`.
    - *Relaciones:* `belongsTo(StudyProgram::class)`, `hasMany(Module::class)`, `hasMany(Itinerary::class)`, `hasMany(Certificate::class)`.
17. **`Module`** (`app/Models/Module.php`)
    - *Tabla:* `modules`
    - *Campos:* `course_id`, `name`, `hours`, `credits`, `order`, `is_active`.
    - *Relaciones:* `belongsTo(Course::class)`, `hasMany(Itinerary::class)`.
18. **`Itinerary`** (`app/Models/Itinerary.php`)
    - *Tabla:* `itineraries`
    - *Campos:* `course_id`, `module_id`, `code`, `name`, `is_active`.
    - *Relaciones:* `belongsTo(Course::class)`, `belongsTo(Module::class)`.

### 3.3. Certificación Digital y Notas
19. **`Certificate`** (`app/Models/Certificate.php`)
    - *Tabla:* `certificates`
    - *Campos:* `code` (UUID/código alfanumérico único para validación pública QR), `certificate_code` (código de folio interno), `user_id`, `course_id`, `study_program_id`, `certificate_type` (`capacitacion` o `modular`), `participation_type` (`ASISTENTE`, `PONENTE`, `ORGANIZADOR`), `event_name`, `institution_name`, `city`, `issue_date`, `start_date`, `end_date`, `duration`, `modality`, `qr_code_path`, `download_count`, `is_active`.
    - *Relaciones:* `belongsTo(User::class)`, `belongsTo(Course::class)`, `belongsTo(StudyProgram::class)`, `hasMany(CertificateDetail::class)`.
20. **`CertificateDetail`** (`app/Models/CertificateDetail.php`)
    - *Tabla:* `certificate_details`
    - *Campos:* `certificate_id`, `module_id` (nullable), `topic_name` (tema específico del temario), `score` (calificación vigesimal opcional 00-20), `observations`, `order`, `is_active`.
    - *Relaciones:* `belongsTo(Certificate::class)`, `belongsTo(Module::class)`.

### 3.4. Admisión, Matrícula y Becas
21. **`Admission`** (`app/Models/Admission.php`)
    - *Tabla:* `cepre`
    - *Campos:* `period`, `activity`, `process` (`cepre`, `admision`, `matricula`), `type` (`ordinario`, `extraordinario`), `exam_date`, `start_date`, `end_date`, `total_vacancies`, `price`, `results_url_pdf`, `is_active`.
    - *Relaciones:* `hasMany(AdmissionDetail::class, 'admission_id')`.
22. **`AdmissionDetail`** (`app/Models/AdmissionDetail.php`)
    - *Tabla:* `cepre_details`
    - *Campos:* `admission_id`, `program_id`, `vacancies`.
    - *Relaciones:* `belongsTo(Admission::class)`, `belongsTo(StudyProgram::class, 'program_id')`.
23. **`AdmissionRequirement`** (`app/Models/AdmissionRequirement.php`)
    - *Tabla:* `admission_requirements`
    - *Campos:* `description`, `is_active`.
24. **`EnrollmentSchedule`** (`app/Models/EnrollmentSchedule.php`)
    - *Tabla:* `enrollment_schedules`
    - *Campos:* `enrollment_type` (`ordinaria`, `extraordinaria`), `start_date`, `end_date`, `description`, `is_active`.
    - *Relaciones:* `hasMany(EnrollmentScheduleDetail::class)`.
25. **`EnrollmentScheduleDetail`** (`app/Models/EnrollmentScheduleDetail.php`)
    - *Tabla:* `enrollment_schedule_details`
    - *Campos:* `enrollment_schedule_id`, `study_program_id`, `requirements`, `notes`.
    - *Relaciones:* `belongsTo(EnrollmentSchedule::class)`, `belongsTo(StudyProgram::class)`.
26. **`Scholarship`** (`app/Models/Scholarship.php`)
    - *Tabla:* `scholarships`
    - *Campos:* `name`, `type`, `description`, `vacancies`, `requirements`, `order`, `is_active`.
    - *Relaciones:* `hasMany(ScholarshipBeneficiary::class)`.
27. **`ScholarshipBeneficiary`** (`app/Models/ScholarshipBeneficiary.php`)
    - *Tabla:* `scholarship_beneficiaries`
    - *Campos:* `scholarship_id`, `academic_period`, `title`, `document_pdf_path`, `beneficiaries_count`, `order`, `is_active`.
    - *Relaciones:* `belongsTo(Scholarship::class)`.

### 3.5. Transparencia, Indicadores y Finanzas
28. **`StudentRecord`** (`app/Models/StudentRecord.php`)
    - *Tabla:* `student_records`
    - *Campos:* `document_type_id`, `document`, `names`, `last_name_father`, `last_name_mother`, `gender`, `email`, `phone`, `record_type` (`MATRICULA`, `ADMISION`), `academic_period`, `study_program`, `cycle` (I al VI), `institution_name_ie`.
29. **`DegreeRecord`** (`app/Models/DegreeRecord.php`)
    - *Tabla:* `degree_records`
    - *Campos:* `full_names`, `document_number`, `gender`, `study_program`, `formative_level`, `productive_family`, `diploma_issue_date`, `file_number`, `generated_title_code`, `department`.
30. **`AccountBalance`** (`app/Models/AccountBalance.php`)
    - *Tabla:* `account_balances`
    - *Campos:* `date`, `concept`, `category`, `amount`, `document_reference`, `period_year`, `period_month`.
    - *Scopes:* `filterByYear()`, `filterByMonth()`, `filterByCategory()`, `search()`.
31. **`ManagementDocument`** (`app/Models/ManagementDocument.php`)
    - *Tabla:* `management_documents`
    - *Campos:* `title`, `description`, `file_path`, `type`, `is_active`.
32. **`LicensingPhase`** (`app/Models/LicensingPhase.php`)
    - *Tabla:* `licensing_phases`
    - *Campos:* `phase_number`, `title`, `description`, `status` (`completed`, `in_progress`, `pending`), `progress_percentage`, `is_current`, `order`, `is_active`.
33. **`Claim`** (`app/Models/Claim.php`)
    - *Tabla:* `claims`
    - *Campos:* `code`, `names`, `dni`, `phone`, `email`, `address`, `service_type`, `claimed_amount`, `description`, `claim_type` (`queja`, `reclamo`), `status` (`pendiente`, `en_proceso`, `atendido`), `file_path`.

### 3.6. Trámites y TUPA
34. **`Tupa`** (`app/Models/Tupa.php`)
    - *Tabla:* `tupa`
    - *Campos:* `title`, `description`, `normative_base`, `effective_start_date`, `file_path`, `is_active`.
    - *Relaciones:* `hasMany(TupaProcedure::class)`.
35. **`TupaCategory`** (`app/Models/TupaCategory.php`)
    - *Tabla:* `tupa_categories`
    - *Campos:* `name`, `description`, `icon`, `order`, `is_active`.
    - *Relaciones:* `hasMany(TupaProcedure::class)`.
36. **`TupaProcedure`** (`app/Models/TupaProcedure.php`)
    - *Tabla:* `tupa_procedures`
    - *Campos:* `tupa_id`, `tupa_category_id`, `code`, `name`, `description`, `requirements` (JSON), `cost`, `uit_percent`, `qualification` (`automatica`, `evaluacion_previa`), `duration`, `office`, `is_active`.
    - *Relaciones:* `belongsTo(Tupa::class)`, `belongsTo(TupaCategory::class)`.

### 3.7. Empleo y Bolsa de Trabajo
37. **`JobOffer`** (`app/Models/JobOffer.php`)
    - *Tabla:* `jobs`
    - *Campos:* `title`, `company`, `location`, `description`, `requirements`, `salary`, `source`, `source_url`, `deadline`, `is_active`.

### 3.8. Biblioteca Virtual
38. **`Book`** (`app/Models/Book.php`)
    - *Tabla:* `books`
    - *Campos:* `study_program_id`, `title`, `slug`, `author`, `publisher`, `publication_year`, `isbn`, `edition`, `summary`, `cover_image`, `file_path`, `external_url`, `category` (Libro, Revista, Paper, Tesis), `source_type` (`local`, `external`, `academic`), `doi`, `views_count`, `downloads_count`, `rating`, `created_by`, `is_active`.
    - *Relaciones:* `belongsTo(StudyProgram::class)`, `belongsTo(User::class, 'created_by')`, `hasMany(BookFavorite::class)`, `hasMany(LibraryAccessLog::class)`.
    - *Scopes:* `career()`, `category()`, `search()`.
39. **`BookFavorite`** (`app/Models/BookFavorite.php`)
    - *Tabla:* `book_favorites`
    - *Campos:* `user_id`, `book_id`.
    - *Relaciones:* `belongsTo(User::class)`, `belongsTo(Book::class)`.
40. **`LibraryAccessLog`** (`app/Models/LibraryAccessLog.php`)
    - *Tabla:* `library_access_logs`
    - *Campos:* `user_id`, `book_id`, `ip_address`, `access_type` (`view`, `stream`, `download`).
    - *Relaciones:* `belongsTo(User::class)`, `belongsTo(Book::class)`.

### 3.9. Usuarios, Roles y Seguridad
41. **`User`** (`app/Models/User.php`)
    - *Tabla:* `users`
    - *Campos:* `dni`, `personal_email` (correo personal único), `email` (correo institucional), `names`, `birthdate`, `mother_tongue`, `phone`, `address`, `sex`, `ubigeo`, `photo_profile`, `cv_file`, `role`, `job_position`, `password`, `is_active`.
    - *Relaciones:* `hasMany(UserRoleDetail::class)`, `hasOne(UserRoleDetail::class, 'primaryRoleDetail')`, `hasMany(Certificate::class)`, `belongsToMany(Book::class, 'book_favorites')`.
    - *Métodos:* `isStudent()`, `getPhotoProfileUrlAttribute()`.
42. **`UserRoleDetail`** (`app/Models/UserRoleDetail.php`)
    - *Tabla:* `user_roles_details`
    - *Campos:* `user_id`, `program_id`, `role_name`, `is_coordinator`, `academic_degree`, `specialty`, `is_active`.
    - *Relaciones:* `belongsTo(User::class)`, `belongsTo(StudyProgram::class, 'program_id')`.
43. **`StudentCouncil`** (`app/Models/StudentCouncil.php`)
    - *Tabla:* `student_councils`
    - *Campos:* `user_id`, `study_program_id`, `academic_period`, `position`, `is_active`.
    - *Relaciones:* `belongsTo(User::class)`, `belongsTo(StudyProgram::class)`.
44. **`Image`** (`app/Models/Image.php`)
    - *Tabla:* `images`
    - *Campos:* `imageable_type`, `imageable_id`, `path`, `is_main`.
    - *Relaciones:* `morphTo()`.
45. **`Blog`** (`app/Models/Blog.php`)
    - *Tabla:* `blogs`
    - *Campos:* `title`, `slug`, `content`, `details`, `is_published`.

---

## 4. Arquitectura de Controladores y Métodos (44 Controladores)

El sistema organiza toda la lógica de presentación y orquestación de servicios en **44 Controladores** estructurados por dominios de negocio:

| # | Controlador | Namespace | Tipo de Respuesta Principal | Responsabilidad Funcional y Métodos Clave |
|---|---|---|---|---|
| 1 | `Controller` | `App\Http\Controllers` | Base Abstract | Clase abstracta base de controladores de Laravel 11. |
| 2 | `AppController` | `App\Http\Controllers` | Inertia / BinaryFile | Portal público institucional: `index`, `whoWeAre`, `history`, `organizationChart`, `hierarchicalStaff`, `teachersStaff`, `administrativeStaff`, `studentCouncil`, `locales`, `studyPrograms`, `program`, `ceprefvc`, `admissionExam`, `enrollments`, `scholarshipsAndCredits`, `documentsManagement`, `statistics`, `managementReports`, `licensment`, `complaintsBook`, `storeClaim`, `partsTable`, `tupa`, `offers`, `institutionalLinks`, `validateCertificate`, `printCertificate`. |
| 3 | `DashboardController` | `App\Http\Controllers` | Inertia | Tablero administrativo central: `index` (cálculo de KPIs de usuarios, libros, certificados, ofertas y balances). |
| 4 | `ProfileController` | `App\Http\Controllers` | Inertia / Redirect | Gestión del perfil de usuario autenticado: `edit`, `update`, `destroy`. |
| 5 | `EnterpriseController` | `App\Http\Controllers` | Inertia / Redirect | Datos institucionales y parámetros de la organización: `edit`, `update`. |
| 6 | `SitemapController` | `App\Http\Controllers` | XML Response | Generador dinámico en tiempo de ejecución del mapa canónico XML del sitio (`index`). |
| 7 | `LibraryReaderController` | `App\Http\Controllers` | Inertia / Stream / JSON | Experiencia de lectura en biblioteca virtual: `index`, `search`, `myLibrary`, `read`, `stream`, `toggleFavorite`. |
| 8 | `JobsController` | `App\Http\Controllers` | Inertia / Redirect | Gestión de bolsa de trabajo y scraping: `index`, `create`, `store`, `edit`, `update`, `destroy`, `toggleStatus`, `internalCalls`, `autoScrape`, `bulkDelete`, `clearAll`. |
| 9 | `CertificateController` | `App\Http\Controllers` | Inertia / PDF / Excel | Emisión y gestión de certificados: `index`, `store`, `update`, `destroy`, `searchStudents`, `downloadTemplate`, `import`, `bulkExportPdf`, `bulkDelete`, `clearAll`. |
| 10 | `StudyProgramsController` | `App\Http\Controllers` | Inertia / Redirect | Gestión de carreras profesionales: `index`, `create`, `store`, `edit`, `update`, `destroy`, `uploadImage`, `deleteImage`, `setMainImage`, `toggleStatus`. |
| 11 | `UsersController` | `App\Http\Controllers` | Inertia / Excel / Redirect | Administración de usuarios: `index`, `create`, `store`, `edit`, `update`, `destroy`, `toggleStatus`, `downloadTemplate`, `import`, `updatePassword`, `bulkDelete`, `countBulkDelete`. |
| 12 | `AccountBalanceController` | `App\Http\Controllers` | Inertia / Excel / Redirect | Balances contables y reportes de gestión: `index`, `store`, `import`, `destroy`, `truncateYear`, `truncateTable` (protegido con `can:gestionar-inversiones`). |
| 13 | `AdmissionsController` | `App\Http\Controllers` | Inertia / Redirect | Convocatorias de admisión y CEPRE: `index`, `create`, `store`, `edit`, `update`, `destroy`, `toggleStatus`. |
| 14 | `TupaController` | `App\Http\Controllers` | Inertia / Redirect | Matriz TUPA, categorías y procedimientos: `index`, `create`, `store`, `edit`, `update`, `destroy`, `toggleStatus`, `categories*`, `procedures*`. |
| 15 | `DegreeRecordsController` | `App\Http\Controllers` | Inertia / Excel / Redirect | Padrón de grados y títulos: `index`, `create`, `store`, `edit`, `update`, `destroy`, `import`. |
| 16 | `StadisticController` | `App\Http\Controllers` | Inertia / Excel / Redirect | Registros de estudiantes y estadísticas: `index`, `create`, `store`, `edit`, `update`, `destroy`, `import`. |
| 17 | `LicensingController` | `App\Http\Controllers` | Inertia / Redirect | Fases y progreso de licenciamiento institucional: `index`, `create`, `store`, `edit`, `update`, `destroy`, `setCurrentPhase`. |
| 18 | `BlogController` | `App\Http\Controllers` | Inertia / Redirect | Publicación de noticias y blogs: `index`, `create`, `store`, `edit`, `update`, `destroy`, `togglePublish`. |
| 19 | `ClaimsController` | `App\Http\Controllers` | Inertia / Redirect | Libro de reclamaciones administrativo: `index`, `updateStatus`, `destroy`. |
| 20 | `CourseController` | `App\Http\Controllers` | Inertia / Redirect | Cursos y talleres formativos: `index`, `store`, `update`, `destroy`, `toggleStatus`. |
| 21 | `ModuleController` | `App\Http\Controllers` | Inertia / Redirect | Módulos formativos curriculares: `index`, `store`, `update`, `destroy`, `toggleStatus`. |
| 22 | `ItineraryController` | `App\Http\Controllers` | Inertia / Redirect | Itinerarios formativos: `index`, `store`, `update`, `destroy`, `toggleStatus`. |
| 23 | `ScholarshipController` | `App\Http\Controllers` | Inertia / Redirect | Programas de becas: `index`, `create`, `store`, `edit`, `update`, `destroy`, `toggleStatus`. |
| 24 | `ScholarshipBeneficiaryController` | `App\Http\Controllers` | Inertia / Redirect | Nómina de beneficiarios y resoluciones: `store`, `update`, `destroy`. |
| 25 | `EnrollmentScheduleController` | `App\Http\Controllers` | Inertia / Redirect | Cronogramas de matrícula ordinaria y extraordinaria: `index`, `create`, `store`, `edit`, `update`, `destroy`. |
| 26 | `TeacherRoleController` | `App\Http\Controllers` | Inertia / Redirect | Asignación de docentes a especialidades: `index`, `store`, `update`, `destroy`. |
| 27 | `StudentCouncilController` | `App\Http\Controllers` | Inertia / Redirect | Representantes estudiantiles por periodo: `index`, `store`, `update`, `destroy`. |
| 28 | `PartnersController` | `App\Http\Controllers` | Inertia / Redirect | Directorio de empresas aliadas y convenios: `index`, `create`, `store`, `edit`, `update`, `destroy`, `toggleStatus`. |
| 29 | `ExternalInstitutionalLinkController` | `App\Http\Controllers` | Inertia / Redirect | Enlaces externos de interés gubernamental: `index`, `create`, `store`, `edit`, `update`, `destroy`, `toggleStatus`. |
| 30 | `HistoricalReviewController` | `App\Http\Controllers` | Inertia / Redirect | Reseña cronológica institucional: `index`, `create`, `store`, `edit`, `update`, `destroy`, `toggleStatus`. |
| 31 | `CarouselController` | `App\Http\Controllers` | Inertia / Redirect | Banners promocionales de la portada: `index`, `create`, `store`, `edit`, `update`, `destroy`, `toggleStatus`. |
| 32 | `AreaController` | `App\Http\Controllers` | Inertia / Redirect | Áreas y departamentos institucionales: `index`, `create`, `store`, `edit`, `update`, `destroy`. |
| 33 | `RoleController` | `App\Http\Controllers` | Inertia / Redirect | Administración de roles y permisos Spatie: `index`, `create`, `store`, `edit`, `update`, `destroy`. |
| 34 | `ManagementDocumentController` | `App\Http\Controllers` | Inertia / BinaryFile | Documentos normativos y reglamento interno: `index`, `create`, `store`, `edit`, `update`, `destroy`, `regulations`. |
| 35 | `Admin\LibraryController` | `App\Http\Controllers\Admin` | Inertia / JSON / Redirect | Repositorio central de biblioteca: `index`, `create`, `store`, `edit`, `update`, `destroy`, `fetchAcademicResources`, `administrators`, `readers`, `reports`, `assignedBooks`. |
| 36 | `Auth\AuthenticatedSessionController` | `App\Http\Controllers\Auth` | Inertia / Redirect | Autenticación de usuarios con rate limiting (`create`, `store`, `destroy`). |
| 37 | `Auth\ConfirmablePasswordController` | `App\Http\Controllers\Auth` | Inertia / Redirect | Confirmación de clave para operaciones sensibles (`show`, `store`). |
| 38 | `Auth\EmailVerificationNotificationController` | `App\Http\Controllers\Auth` | Redirect | Reenvío de enlace de verificación por correo (`store`). |
| 39 | `Auth\EmailVerificationPromptController` | `App\Http\Controllers\Auth` | Inertia / Redirect | Aviso de verificación de correo electrónico requerida (`__invoke`). |
| 40 | `Auth\NewPasswordController` | `App\Http\Controllers\Auth` | Inertia / Redirect | Registro de nueva contraseña tras reset por token (`create`, `store`). |
| 41 | `Auth\PasswordController` | `App\Http\Controllers\Auth` | Redirect | Actualización de contraseña por usuario autenticado (`update`). |
| 42 | `Auth\PasswordResetLinkController` | `App\Http\Controllers\Auth` | Inertia / Redirect | Envío de token de recuperación de contraseña (`create`, `store`). |
| 43 | `Auth\RegisteredUserController` | `App\Http\Controllers\Auth` | Inertia / JSON / Redirect | Registro público de lectores con verificación de DNI (`create`, `store`, `verifyDni`). |
| 44 | `Auth\VerifyEmailController` | `App\Http\Controllers\Auth` | Redirect | Verificación de firma criptográfica de correo (`__invoke`). |

---

## 5. Servicios Especializados de Dominio (`app/Services/`)

### 5.1. Motor de Certificación Digital Soberana y Código QR Autónomo (`QrCodeService`)
- **Problema de Arquitectura Resuelto:** Las soluciones tradicionales que dependen de APIs externas (Google Chart API, servicios SaaS en la nube) fallan si la infraestructura pierde conexión a internet y vulneran la privacidad de los datos personales de estudiantes y graduados.
- **Implementación Técnica:**
  - El servicio `App\Services\QrCodeService` implementa el estándar oficial **ISO/IEC 18004** en PHP 8.3 puro.
  - Utiliza aritmética de matrices en el campo finito de Galois **GF(256)** y corrección de errores polinómica **Reed-Solomon** (Nivel M: 15% de capacidad de restauración).
  - Genera códigos vectoriales **SVG puros** y mapas de bits **PNG nativos en Data URIs Base64** incrustando la URL canónica de validación:
    `https://iestpfranciscovigocaballero.edu.pe/validar-certificado?code={CODIGO_UNICO}`
- **Dualidad de Formatos Oficiales:**
  - **Certificados de Capacitación:** Emitidos para talleres, eventos y cursos libres. Incorporan `participation_type` (`ASISTENTE`, `PONENTE`, `ORGANIZADOR`), `event_name` (ocasión o evento) y temario desagregado (`topic_name`) sin requerir notas.
  - **Certificados Modulares:** Emitidos para itinerarios formativos oficiales. Incorporan notas vigesimales (00-20) por módulo aprobado, desglose de horas académicas, créditos y firmas institucionales.
- **Identificador Único (`code`):** Código alfanumérico estandarizado generado de forma única, indexado y validado en base de datos.

### 5.2. Motor de Búsqueda Académica Multifuente y Streaming de PDFs (`AcademicLibraryService`)
- **Integración Multifuente (10 Repositorios Científicos Mundiales):**
  - **SciELO & Redalyc:** Publicaciones científicas iberoamericanas de acceso abierto revisadas por pares.
  - **Europe PMC:** Artículos biomédicos y de salud clínica con resolución algorítmica de PMC IDs a PDFs directos (`https://europepmc.org/backend/ptpmcrender.fcgi?accid={PMC_ID}&blobtype=pdf`).
  - **Open Library & Internet Archive:** Libros digitalizados completos con enlace de descarga directa en PDF (`https://archive.org/download/{id}/{id}.pdf`).
  - **DOAJ (Directory of Open Access Journals):** Revistas arbitradas de acceso libre y texto completo.
  - **Crossref REST API & OpenAlex API:** Indexación por DOI, metadatos, citas académicas y tópicos.
  - **Semantic Scholar & Google Scholar:** Búsqueda académica avanzada y extracción semántica.
- **Cascada de Resolución Inteligente de DOIs:**
  - Si un recurso cuenta con DOI, el servicio ejecuta una cascada de resolución Open Access:
    `Unpaywall API → Crossref Works REST API → OpenAlex API`.
- **Sanitización de Consultas:** Función `sanitizeSearchTerm` que depura caracteres especiales reservados de la sintaxis Lucene (`[`, `]`, `{`, `}`, `^`, `~`, `*`, `:`, `\`, `/`).
- **Streaming Seguro de PDFs (`LibraryReaderController@stream`):**
  - Transmisión en línea mediante lectura por bloques binarios.
  - Neutraliza restricciones de CORS de servidores remotos sin descargar el archivo físicamente al disco local ni saturar la memoria RAM.
  - Entrega cabeceras `Content-Type: application/pdf` y `Content-Disposition: inline`.

---

## 6. Pipeline HTTP, Middleware y Manejo Global de Excepciones

### 6.1. Pipeline Web (`bootstrap/app.php`)
1. **`App\Http\Middleware\HandleInertiaRequests`:**
   - Define `inertia-app` como vista raíz de montaje.
   - Distribuye props globales a toda la SPA:
     - `enterprise`: Datos institucionales cargados en caché.
     - `headerPrograms`: Listado activo de los 5 programas de estudio para el menú de navegación.
     - `auth.user`: Objeto del usuario autenticado con DNI, nombres, email, rol, avatar y flag `is_student`.
     - `flash.success` / `flash.error`: Mensajes de notificación de sesión.
2. **`App\Http\Middleware\CountWebsiteVisits`:**
   - Contabiliza visitas reales agrupadas por IP y fecha en la tabla `visitor_counters`.
   - Ignora automáticamente peticiones AJAX, prefetchings de Inertia, endpoints API, consola, debugbar y todas las rutas administrativas `/admin*`.

### 6.2. Alias de Middleware Registrados
- `role` ➔ `Spatie\Permission\Middleware\RoleMiddleware`
- `permission` ➔ `Spatie\Permission\Middleware\PermissionMiddleware`
- `role_or_permission` ➔ `Spatie\Permission\Middleware\RoleOrPermissionMiddleware`

### 6.3. Políticas de Rate Limiting (Limitación de Tasa)
- `throttle:login`: 5 intentos por minuto por combinación de email e IP con bloqueo temporal progresivo.
- `throttle:6,1`: Máximo 6 peticiones por minuto en registro público de usuarios y verificación censal de DNI.
- `throttle:5,1`: Máximo 5 peticiones por minuto en el Libro de Reclamaciones y en las solicitudes de restablecimiento de contraseña.

### 6.4. Manejo Global de Excepciones SPA
Configurado declarativamente en `bootstrap/app.php`:
- Intercepta los códigos de estado HTTP **400, 401, 402, 403, 404, 419, 429, 500 y 503**.
- En lugar de devolver pantallas planas HTML o páginas en blanco, fija la vista raíz en `inertia-app` y renderiza el componente reactivo `resources/js/Pages/Error.vue` con el código de error y mensaje amigable de recuperación.

---

## 7. Catálogo Completo de Rutas y Endpoints (319 Rutas)

El enrutamiento está centralizado en `routes/web.php` y `routes/auth.php`. A continuación se detallan los bloques estructurales:

### 7.1. Portal Público Institucional (Canónicas)
- `GET /` (`inicio`): Portada institucional, hero carousel, accesos rápidos y estadísticas.
- `GET /nosotros/quienes-somos` (`quienes-somos`): Misión, visión, principios y reseña.
- `GET /nosotros/historia` (`historia`): Cronología e hitos históricos del instituto.
- `GET /nosotros/organigrama-institucional` (`organigrama-institucional`): Estructura funcional.
- `GET /nosotros/plana-jerarquica` (`plana-jerarquica`): Dirección y jefaturas.
- `GET /nosotros/plana-de-docentes` (`plana-de-docentes`): Docentes clasificados por carrera.
- `GET /nosotros/plana-administrativa` (`plana-administrativa`): Personal administrativo.
- `GET /nosotros/consejo-de-estudiantes` (`consejo-de-estudiantes`): Junta directiva estudiantil.
- `GET /nosotros/locales` (`locales`): Sedes, talleres, campos de práctica y laboratorios.
- `GET /programas-de-estudios` (`programas-de-estudio`): Catálogo general de carreras.
- `GET /programas-de-estudios/{program:slug}` (`programas-de-estudio.detalle`): Malla, competencias, campo laboral y fotos.
- `GET /admision-y-matricula/cepre-fvc` (`cepre-fvc`): Convocatoria CEPRE, vacantes y costos.
- `GET /admision-y-matricula/examen-de-admision` (`examen-de-admision`): Examen ordinario y extraordinario.
- `GET /admision-y-matricula/matriculas` (`matriculas`): Cronograma de matrícula ordinaria y extraordinaria.
- `GET /admision-y-matriculas/becas-y-creditos` (`becas-y-creditos`): Programas de becas y beneficiarios.
- `GET /transparencia/documentos-de-gestion` (`documentos-de-gestion`): Documentos oficiales normativos.
- `GET /transparencia/estadisticas` (`estadisticas`): Métricas de matrícula y admisión por género.
- `GET /transparencia/inversion-y-gestion` (`inversion-y-gestion`): Balances financieros mensuales.
- `GET /transparencia/licenciamiento` (`licenciamiento`): Progreso de Condiciones Básicas de Calidad (CBC).
- `GET /transparencia/libro-de-reclamaciones` (`libro-de-reclamaciones`): Libro de reclamaciones virtual.
- `POST /transparencia/libro-de-reclamaciones` (`libro-de-reclamaciones.store`): Registro de reclamo (`throttle:5,1`).
- `GET /tramites/mesa-de-partes` (`mesa-de-partes`): Guía de recepción de expedientes y horarios.
- `GET /tramites/tupa` (`tupa`): Catálogo interactivo de procedimientos y aranceles.
- `GET /servicios/bolsa-de-trabajo` (`bolsa-de-trabajo`): Ofertas de empleo y postulación.
- `GET /servicios/enlaces-institucionales` (`enlaces-institucionales`): Enlaces gubernamentales oficiales.
- `GET /validar-certificado/{certificate_code?}` (`validar-certificado`): Validación pública QR/DNI.
- `GET /validar-certificado/{certificate_code}/imprimir` (`validar-certificado.print`): Hoja membretada oficial.
- `GET /sitemap.xml` (`sitemap`): Entrega del sitemap canónico indexable.
- `GET /storage/{path}` (`storage.fallback`): Servidor protegido de archivos multimedia.
- *Redirecciones Canónicas:* `/study-programs`, `/becas-y-creditos`, `/robot.txt`, `/verificar-certificado`, `/verify-certificate`.

### 7.2. Portal de Biblioteca Virtual (`auth`)
- `GET /biblioteca` (`biblioteca.index`): Portada con libros y revistas destacadas.
- `GET /biblioteca/buscar` (`biblioteca.search`): Búsqueda facetada por palabras clave, DOI y filtros de carrera.
- `GET /biblioteca/mi-biblioteca` (`biblioteca.favorites`): Estante de libros favoritos del estudiante.
- `GET /biblioteca/leer/{book:slug}` (`biblioteca.read`): Visor inmersivo de lectura dentro del navegador.
- `GET /biblioteca/stream/{book:slug}` (`biblioteca.stream`): Transmisión segura por bloques de PDF sin CORS.
- `POST /biblioteca/favorito/{book}` (`biblioteca.favorite.toggle`): Alternar inclusión en favoritos.

### 7.3. Flujo de Autenticación (`routes/auth.php`)
- `GET|POST /register`: Registro de nuevos usuarios lectores.
- `GET|POST /register/verify-dni` (`throttle:6,1`): Verificación instantánea censal de DNI.
- `GET|POST /login` (`throttle:login`): Autenticación con limitador estricto.
- `POST /logout`: Cierre seguro de sesión.
- `GET|POST /forgot-password` (`throttle:5,1`): Solicitud de restablecimiento de contraseña.
- `GET|POST /reset-password` (`throttle:5,1`): Confirmación de cambio de contraseña por token.
- `GET|POST /confirm-password`: Confirmación de contraseña para operaciones administrativas.
- `GET|POST /verify-email`: Notificación y verificación de correo electrónico.
- `PUT /password`: Actualización de contraseña autenticada.

### 7.4. Panel de Gestión Administrativa (`/admin-*`)
Más de 270 rutas operativas protegidas con autenticación y permisos Spatie:
- **Tablero y Perfil:** `/admin-dashboard`, `/admin-perfil`.
- **Contenido y Difusión:** `/admin-blogs` (CRUD, publicación), `/admin-carrusel` (CRUD, orden), `/admin-historia` (CRUD, años), `/admin-socios` (CRUD, logos), `/admin-enlaces` (CRUD, categorías).
- **Gestión Académica:** `/admin-programas` (CRUD, competencias, campo laboral, metadatos UI, carrusel, imagen principal), `/admin-cursos` (CRUD, tipo de certificación), `/admin-modulos` (CRUD, horas, créditos), `/admin-itinerarios` (CRUD), `/admin-docentes-roles` (asignación docente y coordinación).
- **Estudiantes y Matrícula:** `/admin-exams` (CRUD admisión y CEPRE), `/admin-matriculas` (cronogramas y requisitos), `/admin-scholarships` (becas y beneficiarios), `/admin-consejo-estudiantil` (junta directiva).
- **Certificación Digital:** `/admin-certificados` (búsqueda de alumnos, calificación modular, exportación masiva PDF, borrado masivo, vaciado e importación Excel).
- **Biblioteca Admin:** `/admin-biblioteca` (repositorio, libros asignados, monitoreo de lectores, reportes de uso, sincronización e importación masiva académica `/importar-academicos`).
- **Transparencia y Normatividad:** `/admin-inversiones` (balances contables, importación Excel, truncate de tabla/año), `/admin-estadisticas` (alumnos matriculados e ingresantes para gráficos), `/admin-titulos` (padrón oficial de graduados y titulados), `/admin-licenciamiento` (fases CBC y fase vigente), `/admin-reclamos` (atención Indecopi), `/admin-documentos` (PEI, PAT, PCI, RI), `/admin-reglamento-interno` (visor de reglamento).
- **Trámites y TUPA:** `/admin-tupa` (matriz, `/categorias` y `/procedimientos` con cálculo en S/. y % UIT).
- **Bolsa de Trabajo:** `/admin-trabajos` (ofertas, convocatorias internas, scraping automático, borrado masivo y vaciado total).
- **Seguridad y Control:** `/admin-usuarios` (CRUD, importación masiva Excel, reseteo de claves, borrado masivo, conteo de bajas), `/admin-roles` (roles y permisos Spatie), `/admin-areas` (departamentos), `/admin-empresa` (ficha institucional).

---

## 8. Arquitectura Frontend, Páginas Vue SPA y Plantillas Blade

### 8.1. Especificación y Configuración 100% Single-Page Application (SPA)

La plataforma opera bajo una arquitectura **100% Single-Page Application (SPA)** impulsada por `@inertiajs/vue3` y Vue 3 (Composition API), eliminando recargas completas del navegador en todas las transiciones internas y preservando el estado global de la aplicación.

```
┌────────────────────────────────────────────────────────────────────────┐
│                        FLUJO DE NAVEGACIÓN SPA                         │
├────────────────────────────────────────────────────────────────────────┤
│ 1. Carga Inicial:                                                      │
│    GET /  ───► Blade Mount (inertia-app.blade.php) ──► Vue 3 App       │
│                                                                        │
│ 2. Navegación Interna (100% en Cliente):                               │
│    <Link :href="route(...)"> ───► X-Inertia Request (AJAX)             │
│    Servidor responde JSON (Component + Props)                          │
│    Inertia intercambia componente Vue dinámicamente                    │
│    Indicador de Progreso: Barra activa superior (#2563eb)              │
│    Título Dinámico: title: (t) => `${t} — IESTP Francisco Vigo...`    │
│    CERO Parpadeos • CERO Recargas de Página • Preservación de Estado   │
│                                                                        │
│ 3. Envíos y Formularios Reactivos:                                     │
│    @submit.prevent="submit" ───► useForm() / router.post/put/delete    │
│    Mensajes Flash en Props Globales • Validaciones Precognition        │
└────────────────────────────────────────────────────────────────────────┘
```

#### Estándares de Implementación SPA Homologados:
1. **Configuración Centralizada en `resources/js/app.js`:**
   - **Registro Global de Primitivas:** Los componentes `<Link>` y `<Head>` de `@inertiajs/vue3` se encuentran registrados globalmente en `app.component()`, permitiendo su uso universal en todos los módulos sin riesgo de recurrir a etiquetas `<a>` no reactivas.
   - **Barra de Progreso Nativa de Navegación:** Configuración integrada `progress: { color: '#2563eb', includeCSS: true, showSpinner: false }` que provee retroalimentación visual inmediata durante cualquier navegación asíncrona.
   - **Gestor Dinámico de Títulos:** Callback `title: (title) => title ? `${title} — IESTP Francisco Vigo Caballero` : 'IESTP Francisco Vigo Caballero'`, estandarizando la pestaña del navegador de forma reactiva.
2. **Hipervínculos y Transiciones:**
   - Todo enlace interno (menú principal, sidebar administrativo, breadcrumbs, botones de acción y paginadores) utiliza `<Link>` de Inertia.
   - Los enlaces a protocolos externos (`tel:`, `mailto:`), maquetas de impresión (`/imprimir`, `/bulk-print`), descargas de documentos (`download`) y URLs externas utilizan estrictamente atributos `target="_blank"` y `rel="noopener noreferrer"`.
3. **Formularios e Interacciones Asíncronas:**
   - El 100% de los formularios utiliza `@submit.prevent` combinado con el composable `useForm()` o llamadas a `router` de Inertia (`router.post`, `router.put`, `router.delete`), gestionando cargas de archivos (archivos PDF, imágenes, Excels) mediante `FormData` sin abandonar la aplicación.
4. **Modales y Diálogos en Memoria:**
   - Los diálogos de confirmación, visores de detalle, actualización de credenciales e importaciones masivas se operan mediante estado reactivo local (`ref()`), con fondo envolvente de pantalla completa (`backdrop-blur-md fixed inset-0 z-50`) que previene desajustes visuales o recortes en ventanas flotantes.
5. **Interceptación Global de Errores HTTP:**
   - Configurada en `bootstrap/app.php`, intercepta estados HTTP `400, 401, 402, 403, 404, 419, 429, 500, 503` y los canaliza hacia `resources/js/Pages/Error.vue` con `Inertia::render('Error')`, manteniendo la estética institucional y el ciclo de vida SPA incluso ante fallos de servidor o de autorización.
6. **Optimización de Grids y Tablas de Alta Densidad:**
   - En `resources/js/Pages/Transparency/Statistics.vue`, la rejilla de tarjetas comparativas (Matrícula y Títulos Otorgados) está balanceada milimétricamente en altura (`709px` en escritorio), con scrollbar personalizada (`custom-scrollbar`) y fijación estricta (`sticky`) de encabezados y sumatorias históricas (`thead`, `tfoot`) para explorar 28 años de historial ininterrumpidamente.

### 8.2. Páginas Vue SPA (`resources/js/Pages/` — 116 Componentes)

#### A. Portal Público e Institucional (18 Componentes)
- `Home.vue`: Portada principal, hero carousel, accesos rápidos y estadísticas.
- `Error.vue`: Componente maestro de gestión de errores HTTP (400, 401, 403, 404, 419, 429, 500, 503).
- `Errors/Index.vue`: Panel de errores alternativo.
- `AboutUs/WhoWeAre.vue`: Misión, visión, valores y reseña institucional.
- `AboutUs/History.vue`: Línea de tiempo y reseña cronológica.
- `AboutUs/OrganizationChart.vue`: Estructura orgánica jerárquica.
- `AboutUs/HierarchicalStaff.vue`: Plana directiva y jefaturas.
- `AboutUs/TeachersStaff.vue`: Plana docente por carreras y formación general.
- `AboutUs/AdministrativeStaff.vue`: Personal de soporte administrativo.
- `AboutUs/StudentCouncil.vue`: Representantes de la junta directiva estudiantil.
- `AboutUs/Locals.vue`: Sedes, talleres, campos de práctica y laboratorios.
- `StudyPrograms/Index.vue`: Directorio general de los 5 programas de estudio.
- `StudyPrograms/Show.vue`: Detalle de carrera con carrusel multimedia, competencias y campo laboral.
- `Admission/CepreFvc.vue`: Vacantes, costos y cronograma del ciclo CEPRE.
- `Admission/AdmissionExam.vue`: Convocatoria ordinaria/extraordinaria y deducción de vacantes.
- `Admission/Enrollments.vue`: Cronogramas de matrícula ordinaria y extraordinaria.
- `Admission/ScholarshipsAndCredits.vue`: Catálogo de becas y beneficiarios.
- `Certificates/PrintCertificate.vue`: Visor web de certificado digital para impresión.

#### B. Transparencia, Trámites y Servicios (9 Componentes)
- `Transparency/ManagementDocuments.vue`: Repositorio de documentos de gestión pública.
- `Transparency/Statistics.vue`: Cuadros estadísticos consolidados de matrícula y admisión.
- `Transparency/InvestmentAndManagement.vue`: Balances contables y ejecución presupuestal.
- `Transparency/Licensment.vue`: Progreso de las Condiciones Básicas de Calidad (CBC).
- `Transparency/ComplaintsBook.vue`: Formulario virtual de Libro de Reclamaciones Indecopi.
- `Procedures/PartsTable.vue`: Mesa de partes virtual y guía de expedientes.
- `Procedures/Tupa.vue`: Catálogo interactivo de procedimientos y aranceles TUPA.
- `Services/JobBoard.vue`: Portal de convocatorias laborales con filtros de especialidad.
- `Services/InstitutionalLinks.vue`: Accesos rápidos a portales del Estado (Minedu, Sunedu).
- `Services/ValidateCertificate.vue`: Portal de validación pública de certificados por código o DNI.

#### C. Biblioteca Virtual (5 Componentes)
- `Library/Home.vue`: Portada de la biblioteca virtual con libros y revistas destacadas.
- `Library/Search.vue`: Búsqueda facetada por palabras clave, DOI y filtros de carrera.
- `Library/Favorites.vue`: Estante de libros favoritos del usuario.
- `Library/Reader.vue`: Visor inmersivo de lectura dentro del navegador con soporte multi-fuente.
- `Library/Layout.vue`: Sub-layout de navegación interna de la biblioteca virtual.

#### D. Autenticación (2 Componentes)
- `Auth/Login.vue`: Formulario de inicio de sesión reactivo con limitación de intentos.
- `Auth/Register.vue`: Formulario de registro público de lectores con verificación de DNI.

#### E. Módulo de Administración (`resources/js/Pages/Admin/` — 76 Componentes)
- `Dashboard.vue`: Panel central de control con métricas consolidadas.
- `Profile/Edit.vue`: Edición de datos de usuario, contraseña y foto de perfil.
- `Enterprise/Edit.vue`: Configuración de razón social, logotipos y redes institucionales.
- `AccountBalances/Index.vue`: Balances contables, importación Excel y reseteo de ejercicios.
- `Admission/Index.vue`, `Create.vue`, `Edit.vue`: Convocatorias de admisión y CEPRE.
- `Areas/Index.vue`, `Create.vue`, `Edit.vue`: Áreas funcionales y departamentos.
- `Blogs/Index.vue`, `Create.vue`, `Edit.vue`: Noticias, comunicados y publicación.
- `Carousel/Index.vue`, `Create.vue`, `Edit.vue`: Banners publicitarios de la portada.
- `Certificates/Index.vue`: Emisión, calificación modular, exportación masiva PDF y borrado.
- `Claims/Index.vue`: Seguimiento y atención de hojas de reclamación.
- `Courses/Index.vue`: Cursos y talleres formativos.
- `DegreeRecords/Index.vue`, `Create.vue`, `Edit.vue`: Padrón histórico de egresados titulados.
- `Documents/Index.vue`, `Create.vue`, `Edit.vue`: Documentos normativos de gestión institucional.
- `EnrollmentSchedules/Index.vue`, `Create.vue`, `Edit.vue`: Cronogramas y requisitos de matrícula.
- `History/Index.vue`, `Create.vue`, `Edit.vue`: Hitos cronológicos con fotografías.
- `Itineraries/Index.vue`: Asignación de itinerarios formativos curriculares.
- `Jobs/Index.vue`, `Create.vue`, `Edit.vue`: Bolsa de trabajo, convocatorias y scraping.
- `Library/Repository.vue`, `AssignedBooks.vue`, `Readers.vue`, `Reports.vue`, `Administrators.vue`, `Layout.vue`: Repositorio digital, monitoreo e importación académica masiva.
- `Licensing/Index.vue`, `Create.vue`, `Edit.vue`: Fases de licenciamiento CBC.
- `Links/Index.vue`, `Create.vue`, `Edit.vue`: Enlaces institucionales externos.
- `Modules/Index.vue`: Módulos formativos, horas y créditos.
- `Partners/Index.vue`, `Create.vue`, `Edit.vue`: Empresas aliadas y convenios vigentes.
- `Programs/Index.vue`, `Create.vue`, `Edit.vue`: Gestión de carreras técnicas.
  - `Programs/Competencies/Create.vue`, `Edit.vue`: Competencias técnicas.
  - `Programs/JobFields/Create.vue`, `Edit.vue`: Campo laboral y perfil ocupacional.
  - `Programs/Meta/Create.vue`, `Edit.vue`: Metadatos visuales, colores y banners.
  - `Programs/Modules/Create.vue`, `Edit.vue`: Módulos curriculares asignados.
  - `Programs/Requirements/Create.vue`, `Edit.vue`: Requisitos de ingreso específicos.
- `Roles/Index.vue`, `Create.vue`, `Edit.vue`: Gestión de roles y permisos Spatie.
- `Scholarships/Index.vue`, `Create.vue`, `Edit.vue`: Becas estudiantiles y resoluciones.
- `Statistics/Index.vue`, `Create.vue`, `Edit.vue`: Estadísticas históricas de admisión y matrícula.
- `StudentCouncil/Index.vue`: Junta directiva del consejo estudiantil.
- `TeacherRoles/Index.vue`: Asignación de especialidades a docentes.
- `Tupa/Index.vue`, `Create.vue`, `Edit.vue`: Matriz TUPA institucional.
- `Users/Index.vue`, `Create.vue`, `Edit.vue`: Directorio de usuarios, importación Excel y reseteo de claves.

### 8.3. Layouts Vue (`resources/js/Layouts/` — 2 Componentes)
1. **`PublicLayout.vue`:**
   - Barra superior institucional con accesos rápidos, teléfono, correo y redes sociales.
   - Navegación principal con menú desplegable e interactivo para Nosotros, Programas de Estudio, Admisión, Transparencia, Trámites y Servicios.
   - Mega-menú dinámico alimentado por el prop global `headerPrograms`.
   - Menú de perfil de usuario autenticado con avatar y enlace a biblioteca/panel de control.
   - Pie de página institucional estructurado con datos del RUC, ubicación, enlaces normativos y créditos.
2. **`AdminLayout.vue`:**
   - Menú lateral (*Sidebar*) colapsable con clasificación temática por grupos operativos (Académico, Estudiantes, Contenido, Transparencia, Gestión, Seguridad).
   - Control contextual de visibilidad de ítems basado en la matriz de permisos Spatie (`$page.props.auth.user`).
   - Barra superior con botón de alternancia móvil, atajos directos al portal público y menú de cierre de sesión.

### 8.4. Plantillas Blade Remanentes (`resources/views/` — 17 Archivos Esenciales)

| Archivo Blade | Justificación de Retención Arquitectural |
|---|---|
| `resources/views/inertia-app.blade.php` | **Vista raíz de montaje obligatoria:** Punto de entrada para Inertia.js, directivas `@inertiaHead`, `@inertia` y carga de bundles compilados de Vite. |
| `resources/views/library/external-fallback.blade.php` | **Vista de contingencia institucional en iframe:** Se despliega cuando un repositorio científico externo (Europe PMC, SciELO, PubMed) rechaza la conexión embebida por cabeceras `X-Frame-Options: SAMEORIGIN`. Proporciona botón de apertura segura en nueva pestaña, visor de metadatos y aviso amigable. |
| `resources/views/admin/certificates/print.blade.php` | **Maqueta de impresión individual de alta resolución:** Estilos CSS `@page` y `@media print` milimétricamente calibrados para generar el diploma en PDF con código QR y firmas. |
| `resources/views/admin/certificates/bulk-print.blade.php` | **Maqueta de exportación masiva:** Compilador continuo en PDF para imprimir múltiples diplomas de estudiantes en una sola corrida sin cortes de página. |
| `resources/views/admin/certificates/student-index.blade.php` | Portal específico de consulta y descarga de certificados para el rol estudiante. |
| `resources/views/admin/documents/student-regulations.blade.php` | Visor institucional del Reglamento Interno para consulta directa del estudiante. |
| `resources/views/admin/tupa/categories/create.blade.php`, `edit.blade.php` | Formularios auxiliares tradicionales de soporte para categorías TUPA. |
| `resources/views/admin/tupa/procedures/create.blade.php`, `edit.blade.php` | Formularios auxiliares tradicionales de soporte para procedimientos TUPA. |
| `resources/views/layouts/app.blade.php` | Layout maestro tradicional de soporte para maquetas de impresión y descargas. |
| `resources/views/admin/components/nav-header.blade.php`, `aside.blade.php` | Componentes parciales de soporte para las vistas tradicionales remanentes. |
| `resources/views/auth/forgot-password.blade.php`, `reset-password.blade.php`, `confirm-password.blade.php`, `verify-email.blade.php` | Vistas tradicionales de soporte para flujos auxiliares de notificación y restablecimiento de contraseña vía email. |

---

## 9. Comandos de Consola, Validadores (FormRequests) y Procesamiento Masivo (ETL)

### 9.1. Comandos de Consola Artisan (`app/Console/Commands/`)
1. **`scrape:jobs` (`App\Console\Commands\ScrapeJobBoard`):**
   - Extrae automáticamente convocatorias laborales desde bolsas de trabajo públicas utilizando `Symfony\Component\DomCrawler\Crawler`.
   - Aplica filtros heurísticos clasificados por los 5 programas de estudio y garantiza coincidencia geográfica estricta con las 24 regiones del Perú.
2. **`sitemap:generate` (`App\Console\Commands\GenerateSitemap`):**
   - Recorre las 28 rutas públicas oficiales canónicas y genera físicamente el archivo `public/sitemap.xml` con estándares de fecha W3C, frecuencias y prioridades de rastreo para Google Search Console.

### 9.2. Capa de Validación Estricta (42 FormRequests)
Ubicadas en `app/Http/Requests/`, garantizan la integridad de los datos antes de alcanzar los controladores:
- **Seguridad y Usuarios:** `LoginRequest`, `LoginValidate`, `RegisterValidate`, `PasswordValidate`, `ProfileUpdateRequest`, `UserValidate`, `UserImportRequest`.
- **Certificación y Cursos:** `CertificateRequest`, `CertificateDetailRequest`, `CertificateImportRequest`, `CourseRequest`, `ModuleRequest`, `ItineraryRequest`.
- **Biblioteca Virtual:** `BookRequest`.
- **Admisión y Matrícula:** `AdmissionRequest`, `EnrollmentScheduleRequest`, `ScholarshipRequest`, `ScholarshipBeneficiaryRequest`.
- **Transparencia y Gestión Pública:** `AccountBalanceImportRequest`, `ClaimValidate`, `DegreeRecordImportRequest`, `StudentRecordRequest`, `StudentRecordImportRequest`, `LicensingPhaseRequest`, `ManagementDocumentRequest`.
- **Programas de Estudio:** `StudyProgramValidate`, `ProgramCompetencyRequest`, `ProgramJobFieldRequest`, `ProgramMetaRequest`, `ProgramRequirementRequest`, `ModularCertificationRequest`.
- **TUPA y Procedimientos:** `TupaRequest`, `TupaCategoryRequest`, `TupaProcedureRequest`.
- **Institucional:** `EnterpriseValidate`, `AreaRequest`, `BlogValidate`, `InstitutionalCarouselRequest`, `HistoricalReviewRequest`, `PartnerValidate`, `ExternalInstitutionalLinkValidate`, `JobValidate`, `StudentCouncilRequest`.

### 9.3. Procesamiento Masivo de Datos en Hojas de Cálculo (Maatwebsite Excel)
- **5 Clases de Importación (`app/Imports/`):**
  - `AccountBalanceImport`: Carga masiva de asientos financieros, validando campos de fecha, concepto y montos en moneda nacional.
  - `CertificateImport`: Carga de nóminas de egresados para emisión masiva de diplomas con código alfanumérico correlativo.
  - `DegreeRecordImport`: Padrón histórico de grados y títulos con códigos MINEDU.
  - `StudentRecordImport`: Padrón de ingresantes y matriculados para el observatorio de transparencia.
  - `UserImport`: Alta masiva de cuentas de usuario con validación de DNI y hashing automático de contraseñas seguras.
- **6 Clases de Exportación (`app/Exports/`):**
  - `CertificateTemplateExport`, `CertificateDataSheetExport`, `CertificateInstructionsSheetExport`: Plantillas oficiales normalizadas con instrucciones de llenado y validaciones de datos para certificados.
  - `UserTemplateExport`, `UserDataSheetExport`, `UserInstructionsSheetExport`: Plantillas oficiales normalizadas con manual operativo para importación de usuarios.

---

## 10. Configuraciones del Sistema, Variables de Entorno y Despliegue

### 10.1. Matriz de Configuración del Framework (`config/`)
- **`config/app.php`:** Configuración de zona horaria (`America/Lima`), idioma base español (`es`), nombre institucional y proveedores de servicio.
- **`config/database.php`:** Conexión por defecto `mysql` con codificación `utf8mb4_unicode_ci`, soporte estricto de transacciones ACID y pool de conexiones.
- **`config/filesystems.php`:** Configuración de discos `local` y `public`, almacenamiento de certificados en `app/public/certificates` y fallback dinámico en `/storage/{path}`.
- **`config/inertia.php`:** Configuración del protocolo Inertia.js y vista raíz `inertia-app`.
- **`config/permission.php`:** Configuración de tablas Spatie (`roles`, `permissions`, `model_has_roles`), expiración de caché de permisos y asignación a modelo `App\Models\User`.
- **`config/session.php`:** Driver de sesión `database` o `file` con tiempo de expiración de 120 minutos, cifrado y cookie segura en producción (`SameSite=Lax`).
- **`config/cache.php`:** Almacenamiento en `database` o `file` con prefijo institucional para optimizar el rendimiento de consultas a `Enterprise` y menús.
- **`config/mail.php`:** Configuración SMTP para notificaciones de verificación de cuenta y recuperación de contraseñas.

### 10.2. Diccionario de Variables de Entorno Clave (`.env`)

```ini
APP_NAME="IESTP Francisco Vigo Caballero"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://iestpfranciscovigocaballero.edu.pe

BCRYPT_ROUNDS=12

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bolsa_fvc
DB_USERNAME=usuario_seguro
DB_PASSWORD=clave_robusta

SESSION_DRIVER=database
SESSION_LIFETIME=120
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@iestpfranciscovigocaballero.edu.pe
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@iestpfranciscovigocaballero.edu.pe"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

### 10.3. Pipeline de Despliegue en Servidores de Producción
1. **Instalación y Compilación de Assets:**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   ```
2. **Estructura de Base de Datos y Almacenamiento:**
   ```bash
   php artisan migrate --force
   php artisan storage:link
   ```
3. **Optimización de Cachés:**
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

---

## 11. Seguridad, Integridad y Aseguramiento de Calidad (QA)

### 11.1. Mitigación de Vulnerabilidades Web (OWASP Top 10)
- **Inyección SQL:** Mitigada al 100% mediante el uso de sentencias preparadas y enlaces PDO parametrizados en Eloquent ORM.
- **Cross-Site Scripting (XSS):** Todos los componentes Vue escapan automáticamente la salida de datos. Los contenidos HTML enriquecidos solo se procesan tras rigurosa sanitización.
- **Cross-Site Request Forgery (CSRF):** Middleware `VerifyCsrfToken` activo en todas las mutaciones POST, PUT, PATCH y DELETE vía cabecera `X-XSRF-TOKEN` gestionada automáticamente por Axios e Inertia.js.
- **Protección contra Directory Traversal:** Validación estricta en el controlador de streaming y fallback `/storage/{path}` mediante `realpath` y comprobación de límites de disco `str_starts_with($realTarget, $realPublicDisk)`.
- **Protección contra Clickjacking:** Bloqueo de redirecciones cruzadas en iframes y cabeceras de respuesta `X-Frame-Options: SAMEORIGIN`.

### 11.2. Catálogo de Pruebas Automatizadas (33 Suites PHPUnit)
Ubicadas en `tests/Feature/` (31 pruebas) y `tests/Unit/` (2 pruebas), ejecutadas sobre PHPUnit 11:
1. **Biblioteca y Servicio Académico:** `LibraryAcademicBulkImportTest`, `LibraryModuleTest`, `AcademicLibraryServiceTest` (conexión a 10 repositorios, sanitización Lucene, resolución DOI).
2. **Certificación Digital y Validación QR:** `CertificateFormatAndValidationTest` (capacitación vs modular, generación QR GF-256 nativo, validación pública, PDF).
3. **Bolsa de Trabajo y Scraping:** `AdminJobsBulkActionsTest` (borrado masivo, vaciado, filtros heurísticos).
4. **SEO e Indexación:** `SeoIndexingTest` (sintaxis robots.txt, redirección robot.txt, sitemap XML).
5. **Transparencia, Balances y Licenciamiento:** `AccountBalanceManagementTest`, `TransparencyPublicTest`, `LicensingPublicTest`, `AdminLicensingTest`.
6. **Programas de Estudio y UI:** `StudyProgramPublicTest`, `StudyProgramImagesTest`, `AdminStudyProgramTest`.
7. **Admisión, CEPRE y Becas:** `CeprePublicTest`, `AdminAdmissionTest`, `ScholarshipBeneficiariesFeatureTest`.
8. **Usuarios, Roles y Autenticación:** `UserCreateTest`, `UserBulkImportTest`, `UserBulkDeleteTest`, `UserPasswordUpdateTest`, `StudentLoginTest`, `StudentSidebarTest`, `ProfileTest`, `AdminTeacherRoleTest`.
9. **Institucional y Trámites:** `AboutUsPublicTest`, `HistoryPublicTest`, `AdminHistoryTest`, `ProceduresPublicTest`, `ClaimTest`, `AdminClaimTest`, `EnterpriseTest`, `ExampleTest`.

### 11.3. Estándar de Código (Laravel Pint)
El proyecto se formatea y valida continuamente con el estándar oficial de la comunidad Laravel:
```bash
vendor/bin/pint --dirty --format agent
```

---

<p align="center">
  <strong>Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero"</strong><br>
  <em>Sistema Integral Institucional — Documento Técnico de Arquitectura de Software</em><br>
  <strong>Aprobado para Producción y Auditoría Técnica Gubernamental</strong>
</p>
