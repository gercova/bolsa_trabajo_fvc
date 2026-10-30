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
  <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/TailwindCSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Status-Producci%C3%B3n-success?style=for-the-badge" alt="Production Ready">
</p>

---

## 📌 1. Descripción del Proyecto

El sistema integrado del **IESTP "Francisco Vigo Caballero"** es una solución web integral diseñada para centralizar la presencia digital, la gestión académica, la rendición de cuentas, la inserción laboral y los servicios digitales de la institución educativa en Uchiza, San Martín.

La plataforma abarca desde la difusión de las **5 carreras profesionales técnicas**, procesos de **admisión y matrículas con proyección dinámica de ciclos**, **portal de transparencia y licenciamiento CBC**, **libro de reclamaciones virtual**, **bolsa de trabajo automatizada con scraping**, **biblioteca virtual con streaming de PDFs y búsqueda académica**, hasta un **sistema de certificación digital verificable mediante códigos QR nativos** y gestión administrativa centralizada bajo control de acceso basado en roles (RBAC).

---

## 🚀 2. Módulos y Capacidades Principales

### 🎓 2.1. Carreras Profesionales Técnicas
Presentación interactiva de los 5 programas de estudio aprobados:
1. **Producción Agropecuaria**
2. **Enfermería Técnica**
3. **Administración de Redes y Comunicaciones**
4. **Asistencia Administrativa**
5. **Manejo Forestal**
- Perfiles de egreso, competencias técnicas y transversales, campo laboral, certificaciones modulares progresivas, requisitos y galerías de imágenes.
- Hero header interactivo con carrusel multimedia (`enable_carousel`, `carousel_alt_text`), selector de imagen principal y marcado semántico estructurado Schema.org (`Course`).

### 📝 2.2. Admisión, Matrícula y Becas
- **CEPRE FVC:** Cronogramas de preparación, vacantes y cálculo proyectado de periodos lectivos (ej. ciclo proyectado 2027-I en vigencia 2026).
- **Examen de Admisión:** Modalidades Ordinaria y Extraordinaria, deducción automática de vacantes cubiertas por CEPRE y publicación de resultados en PDF.
- **Matrículas:** Cronograma de matrículas ordinarias y extraordinarias por carrera con desglose de requisitos.
- **Becas y Créditos:** Registro de beneficiarios de Beca 18, becas socioeconómicas y descargas de resoluciones oficiales en PDF.

### 💼 2.3. Bolsa de Trabajo y Egresados
- **Ofertas Laborales:** Convocatorias internas y externas con filtros por ubicación, fuente y carrera técnica.
- **Scraper Automatizado:** Comando de consola y controlador con integración de `Symfony DomCrawler` para extracción periódica de ofertas vigentes en el mercado laboral peruano, con filtros geográficos y exclusión estricta de países extranjeros.
- **Directorio de Talento:** Banco de perfiles de estudiantes, egresados y titulados con CV descargable para empresas reclutadoras.

### 🔏 2.4. Certificación Digital y Validación QR
- **Soporte Dual de Certificados:**
  - **Certificados de Capacitación:** Orientados a talleres y seminarios con temario desagregado (`topic_name`), evento u ocasión, y calidad de participación (`ASISTENTE`, `PONENTE`, etc.).
  - **Certificados Modulares:** Orientados a itinerarios curriculares con notas vigesimales por módulo aprobado.
- **Identificador Único (`code`):** Código alfanumérico estandarizado y verificable en toda la plataforma.
- **Servicio QR Nativo (`QrCodeService`):** Generación vectorial en SVG/PNG en PHP puro mediante matrices Reed-Solomon (GF-256) sin APIs de terceros, garantizando funcionamiento 100% offline.
- **Portal de Validación Pública (`/validar-certificado` y aliases `/verificar-certificado`, `/verify-certificate`):** Verificación instantánea por lectura de código QR, código de certificado o DNI del estudiante.
- **Impresión y Exportación:** Vista de impresión oficial membretada y exportación masiva a PDF de múltiples certificados en un solo documento.
- **Importación/Exportación Masiva:** Carga y descarga de miles de certificados en hojas Excel estructuradas mediante `Maatwebsite/Excel`.

### 📚 2.5. Biblioteca Virtual y Repositorio Académico
- **Catálogo de Lectura:** Libros, revistas, tesis y recursos clasificados por programa de estudios y categorías editoriales.
- **Motor Académico Integrado (`AcademicLibraryService`):** Búsqueda y sincronización masiva con 10 índices científicos de acceso abierto:
  - **SciELO & Redalyc:** Publicaciones científicas iberoamericanas arbitradas.
  - **Europe PMC:** Artículos biomédicos y de salud clínica con enlaces directos a PDF.
  - **Open Library & Internet Archive:** Libros digitalizados completos con enlace de descarga directa en PDF (`archive.org/download/...`).
  - **DOAJ (Directory of Open Access Journals):** Revistas de acceso abierto de texto completo.
  - **Crossref REST API & OpenAlex API:** Indexación por DOI, metadatos, citas y análisis temático.
  - **Semantic Scholar & Google Scholar:** Búsqueda académica avanzada y extracción semántica.
- **Resolución Inteligente de Documentos:** Fallback multi-nivel de DOIs a PDFs Open Access (Unpaywall → Crossref → OpenAlex), detector de galleys OJS y sanitización de caracteres especiales Lucene.
- **Streaming Seguro de PDFs (`/biblioteca/stream/{slug}`):** Proxy de transmisión en línea por bloques que solventa restricciones CORS y permite lectura inmersiva protegida en el navegador.
- **Espacio Personal:** Marcador de lecturas favoritas (`BookFavorite`) y registro de auditoría de accesos (`LibraryAccessLog`).

### 📊 2.6. Transparencia y Rendición de Cuentas
- **Estadísticas Dinámicas:** Cuadros e indicadores históricos de matrícula y admisión por ciclo lectivo (I al VI), programa de estudios y distribución por género.
- **Grados y Títulos:** Registro consolidado de egresados titulados por año, familia productiva y nivel formativo.
- **Inversión y Gastos:** Balances financieros mensuales y anuales con importación Excel y permiso especial de reseteo (`can:gestionar-inversiones`).
- **Licenciamiento Institucional:** Monitor de cumplimiento de las Condiciones Básicas de Calidad (CBC) por etapas.
- **Documentos de Gestión:** Repositorio digital de PEI, PAT, PCI, RI y manuales oficiales descargables.
- **Libro de Reclamaciones:** Formulario virtual con generación de número de atención y cumplimiento de la normativa de Indecopi con rate limiting (`throttle:5,1`).

### 🏛️ 2.7. Trámites y TUPA
- **TUPA Institucional:** Procedimientos clasificados con código oficial, requisitos en formato estructurado, costos expresados en soles y % UIT, plazos de atención y oficina responsable.
- **Mesa de Partes Virtual:** Guía de presentación de solicitudes, formularios y canales oficiales de atención.

### 🔒 2.8. Administración y Control de Acceso (RBAC)
- Autenticación segura mediante Laravel Breeze con verificación de DNI (`register/verify-dni`) y rate limiting.
- Gestión granular de roles y permisos con `spatie/laravel-permission`:
  - **Director:** Acceso total y supervisión institucional.
  - **Administrador / Admin:** Gestión operativa y mantenimiento global.
  - **Docente / Coordinador:** Asignación por carreras, carga académica y gestión curricular.
  - **Coordinadores de Calidad, Empleabilidad, Educación Continua e Investigación.**
  - **Personal Administrativo.**
  - **Estudiante / Lector:** Acceso a biblioteca virtual, favoritos y postulación a vacantes.

### 🌐 2.9. Optimización SEO y Google Search Console
- **`robots.txt` y `robot.txt`:** Reglas optimizadas que permiten la indexación de páginas públicas y recursos estáticos, bloqueando paneles administrativos y rutas de autenticación.
- **`sitemap.xml`:** Generación de mapa del sitio XML bajo estándar W3C con las 23 rutas canónicas y actualización automática mediante el comando `php artisan sitemap:generate`.
- **Verificación Google:** Metaetiqueta oficial `google-site-verification` integrada en la cabecera del layout maestro.
- **URL Canónica Oficial:** `https://iestpfranciscovigocaballero.edu.pe/`.

---

## 🛠️ 3. Stack Tecnológico

| Capa | Tecnologías | Métricas del Sistema |
| :--- | :--- | :--- |
| **Backend** | PHP 8.3, Laravel 11.31, Laravel Breeze | 44 Controladores (33 root, 1 admin, 9 auth, 1 base) |
| **Base de Datos** | MySQL 8.0+ / MariaDB 10.11+ (SQLite para testing) | 45 Modelos Eloquent, 55 Migraciones, 30 Seeders |
| **Frontend** | Blade Templates, Tailwind CSS 3.4 (CDN optimizado), Alpine.js, Vanilla JS | 147 Vistas Blade organizadas en 36 directorios |
| **Rutas & Endpoints** | Laravel Routing con grupos modulares y aliases | 318 Rutas registradas (web + auth + consola) |
| **Validación** | Form Requests dedicados | 42 Form Requests de validación estricta |
| **Excel & Reportes** | `maatwebsite/excel` 3.1+ | 5 Imports masivos, 6 Exports de datos/plantillas |
| **Permisos & Roles** | `spatie/laravel-permission` 6.25+ | RBAC multinivel con Gate::before para Directivos |
| **Web Scraping** | `symfony/dom-crawler` 7.4+, `guzzlehttp/guzzle` 7.8+ | Scraper heurístico con filtros geográficos peruanos |
| **Código QR** | `QrCodeService` (Generador de SVG/PNG matricial en PHP puro) | Reed-Solomon GF(256), disponibilidad 100% offline |
| **Pruebas & Calidad** | PHPUnit 11, Laravel Pint (Agent format), Laravel Pail | 33 Suites de pruebas (31 Feature + 2 Unit) |

---

## 📂 4. Estructura de Directorios

```text
bolsa-fvc/
├── app/
│   ├── Console/Commands/        # Comandos Artisan (sitemap:generate, scrape:jobs)
│   ├── Exports/                 # 6 Exportadores Excel (plantillas y datos estructurados)
│   ├── Http/
│   │   ├── Controllers/         # 44 Controladores (33 root, 1 base, 1 admin, 9 auth)
│   │   │   ├── Admin/           # Admin\LibraryController (repositorio, sync académico, lectores)
│   │   │   └── Auth/            # 9 Controladores Breeze (login, registro DNI, password, email)
│   │   ├── Middleware/          # Middleware institucional (CountWebsiteVisits)
│   │   └── Requests/            # 42 FormRequests para validación estricta
│   ├── Imports/                 # 5 Importadores masivos Excel (usuarios, notas, balances, etc.)
│   ├── Models/                  # 45 Modelos Eloquent con relaciones, scopes y casts
│   ├── Providers/               # Proveedores de servicios (AppServiceProvider con Gate::before)
│   └── Services/                # Lógica desacoplada (AcademicLibraryService, QrCodeService)
├── bootstrap/                   # Inicialización declarativa Laravel 11 (app.php, providers.php)
├── config/                      # Archivos de configuración institucional y paquetes
├── database/
│   ├── factories/               # Model factories para pruebas y generación de datos
│   ├── migrations/              # 55 migraciones de base de datos
│   └── seeders/                 # 30 seeders con datos iniciales, ubigeo nacional e institucional
├── public/                      # Raíz pública del servidor web
│   ├── build/                   # Assets compilados por Vite
│   ├── images/                  # Imágenes del sistema y logotipos institucionales
│   ├── robots.txt               # Directivas para motores de búsqueda y crawlers
│   └── sitemap.xml              # Mapa del sitio canónico para Google Search Console
├── resources/
│   ├── css/ & js/               # Fuentes frontend y scripts de Alpine.js
│   └── views/                   # 147 vistas Blade (aboutus, admin, admission, library, etc.)
├── routes/
│   ├── auth.php                 # Rutas de autenticación con rate limiting
│   ├── console.php              # Comandos interactivos de consola
│   └── web.php                  # 318 rutas públicas, de lectores y panel administrativo
├── tests/
│   ├── Feature/                 # 31 Pruebas de integración, endpoints, SEO y seguridad
│   └── Unit/                    # 2 Pruebas unitarias de servicios (AcademicLibraryService)
├── PDR.md                       # Product Definition Report (Especificación Técnica Detallada)
└── README.md                    # Documentación principal del proyecto
```

---

## 💻 5. Instalación y Configuración Local

### 5.1. Requisitos Previos
- **PHP** >= 8.2 (Recomendado PHP 8.3) con extensiones: `pdo_mysql`, `mbstring`, `openssl`, `xml`, `curl`, `zip`, `gd`.
- **Composer** >= 2.6
- **Node.js** >= 18.x y **NPM**
- **MySQL** >= 8.0 o **MariaDB** >= 10.5

### 5.2. Pasos de Instalación

1. **Clonar el repositorio:**
   ```bash
   git clone <url-del-repositorio> bolsa-fvc
   cd bolsa-fvc
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Configurar el archivo de entorno:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar credenciales en `.env`:**
   ```env
   APP_NAME="IESTP Francisco Vigo Caballero"
   APP_ENV=local
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bolsa_de_trabajo_fvc
   DB_USERNAME=root
   DB_PASSWORD=tu_password
   ```

5. **Ejecutar migraciones y seeders:**
   ```bash
   php artisan migrate --seed
   ```

6. **Crear el enlace simbólico para almacenamiento público:**
   ```bash
   php artisan storage:link
   ```

7. **Instalar dependencias frontend y compilar:**
   ```bash
   npm install
   npm run build
   # o para desarrollo interactivo:
   npm run dev
   ```

8. **Generar el mapa del sitio inicial:**
   ```bash
   php artisan sitemap:generate
   ```

9. **Iniciar el servidor local:**
   ```bash
   php artisan serve
   ```
   Accede a: `http://localhost:8000`

---

## ⚡ 6. Comandos Artisan Personalizados

| Comando | Descripción |
| :--- | :--- |
| `php artisan sitemap:generate` | Regenera `public/sitemap.xml` con todas las rutas públicas oficiales basadas en la URL de producción o el parámetro `--url`. |
| `php artisan sitemap:generate --url=https://midominio.com` | Genera el sitemap utilizando un dominio personalizado. |
| `php artisan scrape:jobs {source_url} {source_name}` | Ejecuta el extractor web sobre un portal de empleo externo para mapear convocatorias afines a las 5 carreras técnicas con validación geográfica. |

---

## 🧪 7. Pruebas y Calidad de Código

### Ejecución de Pruebas Automatizadas
La suite consta de **33 archivos de prueba** (31 Feature Tests y 2 Unit Tests) que cubren flujos de autenticación, portal público, licenciamiento, procedimientos, biblioteca, validación QR, importación masiva y verificación SEO:
```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar prueba específica de SEO e indexación
php artisan test --filter=SeoIndexingTest

# Ejecutar pruebas del motor académico de biblioteca
php artisan test --filter=LibraryAcademicBulkImportTest
php artisan test --filter=AcademicLibraryServiceTest
```

### Formateador de Código (Laravel Pint)
Para asegurar el cumplimiento de los estándares PSR-12 y directrices de Laravel:
```bash
vendor/bin/pint --dirty --format agent
```

---

## 🌐 8. Despliegue en Producción

### 8.1. Configuración de Entorno (`.env`)
```env
APP_NAME="IESTP Francisco Vigo Caballero"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://iestpfranciscovigocaballero.edu.pe
```

### 8.2. Optimización para Producción
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
php artisan sitemap:generate
```

### 8.3. Configuración de Apache (`public/.htaccess`)
El archivo `.htaccess` provisto incluye redirección canónica, gestión de Authorization headers y entrega estática directa de `robots.txt` y `sitemap.xml` sin sobrecarga de PHP.

### 8.4. Verificación en Google Search Console
1. Acceder a [Google Search Console](https://search.google.com/search-console).
2. Agregar la propiedad con prefijo de URL: `https://iestpfranciscovigocaballero.edu.pe/`.
3. Seleccionar método de verificación **Etiqueta HTML** (ya incluida en el `<head>`):
   ```html
   <meta name="google-site-verification" content="n-kFDeVvGTkr1sar6qNZRP1l5kFEqJzI-4PDgCCTC2s" />
   ```
4. En la barra lateral, dirigirse a **Sitemaps** e ingresar: `sitemap.xml`.

---

## 📖 9. Documentación Detallada (PDR)
Para consultar la especificación completa del sistema, diccionario de los 45 modelos de datos, arquitectura de controladores, flujos de negocio y matriz de permisos, revise el documento [PDR.md](file:///media/gercova/DATA1/PROYECTOS%20WEB/bolsa-fvc/PDR.md).

---

## 📄 10. Licencia y Créditos
- **Institución:** Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero" — Uchiza.
- **Framework:** [Laravel](https://laravel.com) (Licencia MIT).
- **Desarrollo y Mantenimiento:** Equipo de Desarrollo de Software Institucional.
