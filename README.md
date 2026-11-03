# IESTP "Francisco Vigo Caballero" — Plataforma Web Institucional & Sistema Integrado

<p align="center">
  <img src="public/images/logo.png" alt="Logo IESTP Francisco Vigo Caballero" width="130" onerror="this.style.display='none'">
</p>

<p align="center">
  <strong>Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero"</strong><br>
  <em>Uchiza, Tocache, Región San Martín, Perú</em><br>
  Licenciamiento Institucional en Proceso • Formación Técnica Superior a Nombre de la Nación
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.31-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Inertia.js-3.5-9553E9?style=for-the-badge&logo=inertia&logoColor=white" alt="Inertia.js">
  <img src="https://img.shields.io/badge/Vue.js-3.5-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white" alt="Vue 3">
  <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/TailwindCSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Vite-8.3-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/SPA-Single--Page_App-success?style=for-the-badge" alt="Single-Page Application">
</p>

---

## 📌 1. Descripción del Proyecto

El sistema integrado del **IESTP "Francisco Vigo Caballero"** es una solución web de vanguardia desarrollada sobre una arquitectura **Single-Page Application (SPA)** híbrida con **Inertia.js (Vue 3)** y **Laravel 11**. Centraliza la presencia digital, la gestión académica, la rendición de cuentas, la inserción laboral, los servicios digitales y la certificación soberana de la institución en Uchiza, San Martín.

La plataforma abarca desde la difusión de las **5 carreras profesionales técnicas**, procesos de **admisión y matrículas con proyección dinámica de ciclos**, **portal de transparencia y licenciamiento CBC**, **libro de reclamaciones virtual**, **bolsa de trabajo automatizada con scraping**, **biblioteca virtual con streaming de PDFs y búsqueda académica**, hasta un **sistema de certificación digital verificable mediante códigos QR nativos**, todo operando bajo un modelo de control de acceso basado en roles (**RBAC**).

---

## 🏗️ 2. Arquitectura Tecnológica

```
┌────────────────────────────────────────────────────────────────────────┐
│                   Cliente / Navegador (Browser Client)                 │
│              Inertia.js Client + Vue 3 (Composition API)               │
│               Tailwind CSS 3.4 + Vite 8 Asset Bundling                 │
└────────────────────────────────────┬───────────────────────────────────┘
                                     │ JSON / Inertia Protocol
                                     ▼
┌────────────────────────────────────────────────────────────────────────┐
│                      Pipeline HTTP (Laravel 11)                        │
│   HandleInertiaRequests • CountWebsiteVisits • RateLimiter (Throttle)  │
│          RBAC: Spatie Permission Middleware (Role/Permission)          │
└────────────────────────────────────┬───────────────────────────────────┘
                                     │
         ┌───────────────────────────┼───────────────────────────┐
         ▼                           ▼                           ▼
┌─────────────────┐         ┌─────────────────┐         ┌─────────────────┐
│ 44 Controladores│         │ 2 Servicios     │         │ 42 FormRequests │
│ HTTP (Inertia/  │         │ Especializados  │         │ Validación      │
│ Stream/Download)│         │ (Academic / QR) │         │ Estricta DTO    │
└────────┬────────┘         └────────┬────────┘         └─────────────────┘
         │                           │
         └───────────────────────────┼───────────────────────────┐
                                     ▼                           ▼
                            ┌─────────────────┐         ┌─────────────────┐
                            │ 45 Modelos      │         │ 2 Comandos CLI  │
                            │ Eloquent ORM    │         │ ScrapeJobBoard  │
                            │ MySQL 8.0+      │         │ GenerateSitemap │
                            └─────────────────┘         └─────────────────┘
```

- **Arquitectura 100% SPA (Single-Page Application):** Navegación ultra-rápida cliente-servidor mediante `@inertiajs/vue3` sin re-cargar la página ni perder el estado, con registro global de componentes `<Link>` y `<Head>`, indicador visual de progreso (`#2563eb`) y gestor dinámico de títulos institucionales.
- **Navegación e Interacción Pura en Cliente:** Enrutamiento reactivo sin enlaces que provoquen recargas de página completas (`<Link>` en lugar de `<a>`), envío de formularios mediante `@submit.prevent` e Inertia Forms, y modales integrados en memoria con desenfoque envolvente de pantalla completa (`backdrop-blur-md`).
- **Manejo Centralizado de Errores SPA:** Códigos 400, 401, 402, 403, 404, 419, 429, 500 y 503 interceptados globalmente en `bootstrap/app.php` y renderizados dentro del ciclo de vida SPA en `resources/js/Pages/Error.vue`.
- **Visualización de Datos y Tablas Densas:** Rejilla equilibrada y adaptable en Transparencia Estadística con scrollbar estilizada y cabeceras/pies fijos (`sticky`) para consultar más de 28 años de registros históricos continuos sin desbordamientos.
- **Lector Embebido con Respaldo *Cross-Origin*:** Streaming seguro inline de PDFs y vista de respaldo institucional `library.external-fallback.blade.php` para repositorios remotos protegidos por políticas `X-Frame-Options: SAMEORIGIN` (Europe PMC, PubMed, SciELO, etc.).

---

## 🚀 3. Resumen de Módulos Principales

| Módulo | Descripción Funcional | Tecnologías Clave |
|---|---|---|
| **Carreras Profesionales** | Presentación de los 5 programas de estudio: *Producción Agropecuaria, Enfermería Técnica, Administración de Redes, Asistencia Administrativa y Manejo Forestal*. Perfiles, competencias, mallas y carrusel multimedia. | Vue 3, Schema.org `Course`, SEO semántico. |
| **Admisión y Matrícula** | Convocatorias Ordinarias/Extraordinarias, CEPRE FVC, proyección dinámica de periodos lectivos y cronograma de matrículas. | Eloquent Scopes, Inertia Form. |
| **Bolsa de Trabajo** | Publicación de ofertas, postulación con CV y motor de extracción automatizada de convocatorias peruanas. | `Symfony DomCrawler`, CLI `jobs:scrape`. |
| **Certificación QR** | Emisión y validación instantánea de certificados de capacitación y modulares con código alfanumérico único. | `QrCodeService` (Reed-Solomon GF-256 nativo), Maatwebsite Excel. |
| **Biblioteca Virtual** | Repositorio académico con streaming de PDFs y conexión multi-índice a 10 repositorios científicos mundiales. | `AcademicLibraryService`, StreamedResponse. |
| **Transparencia y Balances** | Cuadros estadísticos históricos, grados y títulos, licenciamiento institucional CBC y balances financieros con importación Excel. | Laravel Gates, Charting, Excel Import. |
| **Trámites y TUPA** | Procedimientos administrativos digitalizados, aranceles en S/. y % UIT, plazos y formulario de Mesa de Partes Virtual. | Inertia Forms, Validación DNI. |
| **Libro de Reclamaciones** | Registro conforme al estándar de Indecopi con código de atención correlativo y limitación de tasa `throttle:5,1`. | Precognition, RateLimiter. |
| **Administración (RBAC)** | Gestión granular de usuarios, roles, permisos y bitácoras institucionales con `spatie/laravel-permission`. | Spatie RBAC, Inertia Datatables. |

---

## 📊 4. Inventario de Componentes del Sistema

### 🛣️ 4.1. Rutas del Sistema (319 Rutas Registradas)
El enrutamiento está centralizado en `routes/web.php` y `routes/auth.php`:
- **Rutas Públicas Institucionales:** Portada (`/`), nosotros (`/nosotros/*`: 8 rutas), carreras técnicas (`/programas-de-estudios/*`: 2 rutas), admisión (`/admision-y-matricula/*`: 4 rutas), transparencia (`/transparencia/*`: 5 rutas), trámites (`/tramites/*`: 2 rutas), servicios (`/servicios/*`: 2 rutas), sitemap (`/sitemap.xml`) y fallback de archivos (`/storage/{path}`).
- **Validación de Certificados:** `/validar-certificado`, `/validar-certificado/{code}`, `/validar-certificado/{code}/imprimir`, más alias canónicos `/verificar-certificado` y `/verify-certificate`.
- **Biblioteca Virtual:** Portada (`/biblioteca`), catálogo avanzado (`/biblioteca/buscar`), favoritos (`/biblioteca/mi-biblioteca`), lector SPA (`/biblioteca/leer/{slug}`), streaming de binarios (`/biblioteca/stream/{slug}`) y toggle de favoritos (`/biblioteca/favorito/{book}`).
- **Autenticación (routes/auth.php):** Login, registro con verificación censal DNI (`register/verify-dni`), recuperación y reseteo de contraseña, confirmación y cierre de sesión.
- **Panel de Administración (`/admin-*`):**
  - Dashboard (`/admin-dashboard`) y Perfil (`/admin-perfil`).
  - Contenido: Blogs (`/admin-blogs`), Carrusel (`/admin-carrusel`), Reseña Histórica (`/admin-reseña-historica`), Aliados (`/admin-aliados`), Enlaces (`/admin-enlaces-institucionales`).
  - Académico: Carreras (`/admin-programas` y sub-rutas `/competencias`, `/campo-laboral`, `/metadatos`, `/modulos`, `/requisitos`), Cursos (`/admin-cursos`), Módulos (`/admin-modulos`), Itinerarios (`/admin-itinerarios`), Roles Docentes (`/admin-asistencias-tecnicas`).
  - Estudiantes y Admisión: Admisión (`/admin-admision`), Matrículas (`/admin-cronograma-matricula`), Becas (`/admin-becas`), Consejo Estudiantil (`/admin-consejo-estudiantil`).
  - Gestión: Usuarios (`/admin-usuarios` con importación, exportación, conteo y contraseñas), Roles (`/admin-roles`), Áreas (`/admin-areas`), Empresa (`/admin-empresa`), TUPA (`/admin-tupa` con `/categorias` y `/procedimientos`), Documentos (`/admin-documentos`), Reglamento Interno (`/admin-reglamento-interno`).
  - Transparencia: Balances (`/admin-inversion-gestion`), Estadísticas (`/admin-estadisticas`), Grados y Títulos (`/admin-grados-titulos`), Licenciamiento (`/admin-licenciamiento`), Reclamos (`/admin-reclamos`), Empleos (`/admin-trabajos`).
  - Certificados (`/admin-certificados`): Búsqueda de alumnos, calificación modular, exportación masiva PDF, borrado masivo e importación Excel.
  - Biblioteca Admin (`/admin-biblioteca`): Repositorio, libros asignados, monitoreo de lectores, reportes e importación académica masiva.

### 🛡️ 4.2. Middleware
- **Globales en Pipeline Web (`bootstrap/app.php`):**
  - `App\Http\Middleware\HandleInertiaRequests`: Inyecta datos compartidos a la SPA (usuario autenticado, roles, permisos Spatie, empresa, mensajes flash de sesión).
  - `App\Http\Middleware\CountWebsiteVisits`: Registro anónimo y cálculo de visitantes en tiempo real para el portal de transparencia.
- **Alias de Autorización:**
  - `role` ➔ `Spatie\Permission\Middleware\RoleMiddleware`
  - `permission` ➔ `Spatie\Permission\Middleware\PermissionMiddleware`
  - `role_or_permission` ➔ `Spatie\Permission\Middleware\RoleOrPermissionMiddleware`
- **Limitadores de Tasa (Rate Limiting):**
  - `throttle:login`: 5 intentos por minuto por combinación de email e IP.
  - `throttle:6,1`: Máximo 6 peticiones por minuto para endpoints sensibles de registro.
  - `throttle:5,1`: Máximo 5 peticiones por minuto en Libro de Reclamaciones y recuperación de contraseñas.

### 🎮 4.3. Controladores (44 Controladores)
- **Núcleo y Servicios:** `AppController`, `DashboardController`, `ProfileController`, `EnterpriseController`, `SitemapController`.
- **Autenticación (app/Http/Controllers/Auth/):** `AuthenticatedSessionController`, `RegisteredUserController`, `PasswordResetLinkController`, `NewPasswordController`, `PasswordController`, `ConfirmablePasswordController`, `EmailVerificationPromptController`, `EmailVerificationNotificationController`, `VerifyEmailController`.
- **Certificación y Empleo:** `CertificateController`, `JobsController`.
- **Biblioteca Virtual:** `LibraryReaderController`, `Admin\LibraryController`.
- **Académico y Carreras:** `StudyProgramsController`, `CourseController`, `ModuleController`, `ItineraryController`, `TeacherRoleController`, `AdmissionsController`, `EnrollmentScheduleController`, `ScholarshipController`, `ScholarshipBeneficiaryController`.
- **Gestión Institucional y Transparencia:** `AreaController`, `BlogController`, `CarouselController`, `ClaimsController`, `DegreeRecordsController`, `ExternalInstitutionalLinkController`, `HistoricalReviewController`, `LicensingController`, `ManagementDocumentController`, `PartnersController`, `RoleController`, `StadisticController`, `StudentCouncilController`, `TupaController`, `UsersController`, `AccountBalanceController`.

### ⚙️ 4.4. Servicios de Dominio (app/Services/)
1. **`AcademicLibraryService`:**
   - Motor de integración científica con 10 repositorios internacionales (*SciELO, Redalyc, Europe PMC, Open Library / Internet Archive, DOAJ, Crossref, OpenAlex, Semantic Scholar, Scopus, Google Scholar*).
   - Sanitización de consultas Lucene, resolución recursiva de galleys OJS y extracción de metadatos de acceso abierto.
2. **`QrCodeService`:**
   - Generación soberana de códigos QR en formato SVG vectorial y Data URIs base64 en PHP puro (algoritmo Reed-Solomon en campo de Galois GF-256), sin dependencias ni llamadas a APIs externas.

### 🗃️ 4.5. Modelos Eloquent (45 Modelos)
- **Institucional:** `Enterprise`, `Area`, `Charge`, `DocumentType`, `HistoricalReview`, `InstitutionalCarousel`, `ExternalInstitutionalLink`, `Partner`, `VisitorCounter`.
- **Académico:** `StudyProgram`, `ProgramCompetency`, `ProgramJobField`, `ProgramMeta`, `ProgramRequirement`, `Course`, `Module`, `Itinerary`, `ModularCertification`, `TeacherRoleDetail` / `UserRoleDetail`.
- **Admisión y Matrícula:** `Admission`, `AdmissionDetail`, `AdmissionRequirement`, `EnrollmentSchedule`, `EnrollmentScheduleDetail`, `Scholarship`, `ScholarshipBeneficiary`.
- **Certificación Digital:** `Certificate`, `CertificateDetail`.
- **Biblioteca:** `Book`, `BookFavorite`, `LibraryAccessLog`.
- **Transparencia y Trámites:** `AccountBalance`, `Claim`, `DegreeRecord`, `StudentRecord`, `LicensingPhase`, `ManagementDocument`, `StudentCouncil`, `Tupa`, `TupaCategory`, `TupaProcedure`.
- **Usuarios y Seguridad:** `User`, `Image`, `Blog`, `JobOffer`.

### 🖥️ 4.6. Vistas y Componentes Frontend

#### A. Páginas Vue SPA (`resources/js/Pages/` — 116 Componentes)
- **Portal Público:** `Home.vue`, `Error.vue`, `Errors/Index.vue`.
- **Nosotros (`AboutUs/`):** `WhoWeAre.vue`, `OrganizationChart.vue`, `HierarchicalStaff.vue`, `TeachersStaff.vue`, `AdministrativeStaff.vue`, `StudentCouncil.vue`, `Locals.vue`, `History.vue`.
- **Carreras (`StudyPrograms/`):** `Index.vue`, `Show.vue`.
- **Admisión (`Admission/`):** `AdmissionExam.vue`, `CepreFvc.vue`, `Enrollments.vue`, `ScholarshipsAndCredits.vue`.
- **Transparencia (`Transparency/`):** `ComplaintsBook.vue`, `InvestmentAndManagement.vue`, `Licensment.vue`, `ManagementDocuments.vue`, `Statistics.vue`.
- **Trámites y Servicios (`Procedures/`, `Services/`):** `PartsTable.vue`, `Tupa.vue`, `JobBoard.vue`, `InstitutionalLinks.vue`, `ValidateCertificate.vue`.
- **Biblioteca Virtual (`Library/`):** `Home.vue`, `Search.vue`, `Favorites.vue`, `Reader.vue`, `Layout.vue`.
- **Autenticación (`Auth/`):** `Login.vue`, `Register.vue`.
- **Certificados (`Certificates/`):** `PrintCertificate.vue`.
- **Administración (`Admin/`):** 76 páginas Vue que cubren la totalidad de CRUDs y tableros de control administrativo (*Dashboard, Usuarios, Roles, Carreras, Admisión, Becas, Balances, Certificados, Biblioteca, Licenciamiento, TUPA, Documentos, etc.*).

#### B. Layouts Vue (`resources/js/Layouts/` — 2 Componentes)
- **`PublicLayout.vue`:** Layout público de alta fidelidad estética con barra superior institucional, menú interactivo responsivo, mega-menú de programas de estudio, pie de página institucional y selector de temas.
- **`AdminLayout.vue`:** Entorno administrativo profesional con menú lateral (*Sidebar*) colapsable, control de acceso contextual por roles, selector de atajos rápidos, barra de perfil de usuario y notificaciones.

#### C. Vistas Blade Remanentes (`resources/views/` — 17 Archivos Esenciales)
- `inertia-app.blade.php`: Plantilla anfitriona raíz de montaje para Inertia.js y scripts de Vite.
- `library/external-fallback.blade.php`: Vista de contingencia institucional en iframe para repositorios protegidos contra incrustación cruzada.
- `admin/certificates/print.blade.php`: Maqueta de alta resolución para impresión individual de certificados.
- `admin/certificates/bulk-print.blade.php`: Maqueta de alta densidad para exportación masiva a PDF de múltiples certificados.
- `admin/certificates/student-index.blade.php`: Portal específico de consulta de certificados del estudiante.
- `admin/documents/student-regulations.blade.php`: Lector web del Reglamento Interno institucional.
- `admin/tupa/categories/create.blade.php`, `edit.blade.php`: Formularios auxiliares de categorías TUPA.
- `admin/tupa/procedures/create.blade.php`, `edit.blade.php`: Formularios auxiliares de procedimientos TUPA.
- `layouts/app.blade.php`, `admin/components/nav-header.blade.php`, `admin/components/aside.blade.php`: Layout base para soporte de módulos de impresión.
- `auth/{forgot-password,reset-password,confirm-password,verify-email}.blade.php`: Vistas de soporte para flujos auxiliares de contraseña por correo.

---

## ⚙️ 5. Configuraciones del Sistema

| Parámetro / Servicio | Configuración Implementada | Ubicación |
|---|---|---|
| **Motor Web & Routing** | Laravel 11.x con configuración declarativa moderna | `bootstrap/app.php` |
| **Pila Frontend** | Inertia.js 3.5 + Vue 3.5 (Composition API) | `resources/js/app.js` |
| **Empaquetador de Assets** | Vite 8 + Laravel Vite Plugin + Autoprefixer | `vite.config.js`, `package.json` |
| **Estilos y Diseño** | Tailwind CSS 3.4 con `@tailwindcss/forms` | `tailwind.config.js`, `resources/css/app.css` |
| **Base de Datos** | MySQL 8.0+ / MariaDB con codificación `utf8mb4_unicode_ci` | `config/database.php` |
| **Control de Acceso (RBAC)** | `spatie/laravel-permission` con caché de permisos | `config/permission.php` |
| **Almacenamiento (Storage)** | Discos `public` y `local` con enlace simbólico y fallback seguro | `config/filesystems.php` |
| **Importación / Exportación** | `maatwebsite/excel` (XLSX, CSV con formato peruano) | `app/Imports/`, `app/Exports/` |
| **Lector PDF & CORS** | Streaming seguro mediante `BinaryFileResponse` y `StreamedResponse` | `app/Http/Controllers/LibraryReaderController.php` |

---

## 💻 6. Instalación y Puesta en Marcha

### 6.1. Requisitos del Servidor
- **PHP:** Versión 8.3 o superior (extensiones obligatorias: `pdo_mysql`, `mbstring`, `gd`, `xml`, `curl`, `zip`, `bcmath`).
- **Node.js:** Versión 20.x o superior con npm.
- **Gestor de Base de Datos:** MySQL 8.0+ o MariaDB 10.5+.
- **Servidor Web:** Apache 2.4+ (con `mod_rewrite`) o Nginx.

### 6.2. Instalación en Desarrollo
```bash
# 1. Clonar el repositorio
git clone <url-del-repositorio> bolsa-fvc
cd bolsa-fvc

# 2. Instalar dependencias de backend y frontend
composer install
npm install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Configurar base de datos en .env y ejecutar migraciones con datos iniciales
php artisan migrate --seed

# 5. Generar enlace simbólico de almacenamiento público
php artisan storage:link

# 6. Iniciar servidores de desarrollo concurrentes
composer run dev
```

### 6.3. Despliegue en Producción
```bash
# Optimización de dependencias
composer install --no-dev --optimize-autoloader
npm run build

# Caché de configuración, rutas y vistas
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Regeneración de sitemap XML para Google Search Console
php artisan sitemap:generate
```

---

## 🔒 7. Seguridad y Buenas Prácticas

- **Prevención de Inyección SQL:** Mitigado al 100% mediante el uso exclusivo de sentencias preparadas en Eloquent ORM.
- **Protección contra Clickjacking:** Bloqueo de redirecciones externas en el `<iframe>` del lector y uso estricto de `X-Frame-Options: SAMEORIGIN`.
- **Prevención de Directory Traversal:** Sanitización rigurosa en el fallback `/storage/{path}` mediante `realpath` y comprobación estricta de límites de disco.
- **Formateo Automatizado de Código:** Cumplimiento continuo de los estándares de la comunidad mediante Laravel Pint:
  ```bash
  vendor/bin/pint --dirty --format agent
  ```

---

<p align="center">
  <strong>© 2026 IESTP "Francisco Vigo Caballero" — Uchiza, Perú. Todos los derechos reservados.</strong>
</p>
