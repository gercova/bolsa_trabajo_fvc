<?php

namespace App\Http\Controllers;

use App\Exports\CertificateTemplateExport;
use App\Http\Requests\CertificateDetailRequest;
use App\Http\Requests\CertificateImportRequest;
use App\Http\Requests\CertificateRequest;
use App\Imports\CertificateImport;
use App\Models\Certificate;
use App\Models\CertificateDetail;
use App\Models\Course;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificateController extends Controller
{
    /**
     * Display a listing of certificates.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $courseId = $request->input('course_id');
        $userId = $request->input('user_id');
        $modality = $request->input('modality');
        $status = $request->input('status');

        $query = Certificate::with([
            'user' => fn ($q) => $q->withCount('certificates'),
            'course',
            'details.module',
        ])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('code', 'LIKE', "%{$search}%")
                        ->orWhere('certificate_code', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        ->orWhere('duration', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('names', 'LIKE', "%{$search}%")
                                ->orWhere('dni', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('course', function ($cq) use ($search) {
                            $cq->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('description', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($courseId, fn ($q) => $q->where('course_id', $courseId))
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when($modality, fn ($q) => $q->where('modality', $modality))
            ->when($status !== null && $status !== '', function ($q) use ($status) {
                $q->where('is_active', (bool) $status);
            })
            ->orderByDesc('issue_date')
            ->orderByDesc('id');

        $certificates = $query->paginate(10)->appends($request->only(['search', 'course_id', 'user_id', 'modality', 'status']));
        $courses = Course::where('is_active', true)->with('modules')->orderBy('name')->get();
        $users = User::where('is_active', true)->withCount('certificates')->orderBy('names')->get(['id', 'names', 'dni', 'email']);

        // Stat counters
        $totalCertificates = Certificate::count();
        $activeCertificates = Certificate::where('is_active', true)->count();
        $presencialCount = Certificate::where('modality', 'Presencial')->count();
        $virtualSemipresCount = Certificate::whereIn('modality', ['Virtual', 'Semipresencial'])->count();
        $totalDownloads = (int) Certificate::sum('download_count');
        $issuedCoursesCount = Certificate::distinct('course_id')->count('course_id');

        return view('admin.certificates.index', compact(
            'certificates',
            'courses',
            'users',
            'totalCertificates',
            'activeCertificates',
            'presencialCount',
            'virtualSemipresCount',
            'totalDownloads',
            'issuedCoursesCount',
            'search',
            'courseId',
            'userId',
            'modality',
            'status'
        ));
    }

    /**
     * Store a newly created certificate.
     */
    public function store(CertificateRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);
            $data['download_count'] = 0;

            if (empty($data['certificate_type'])) {
                $course = Course::find($data['course_id']);
                $data['certificate_type'] = $course?->certificate_type ?? 'capacitacion';
                if (empty($data['study_program_id']) && $course?->study_program_id) {
                    $data['study_program_id'] = $course->study_program_id;
                }
                if (empty($data['event_name']) && $course?->event_name) {
                    $data['event_name'] = $course->event_name;
                }
            }

            $certificate = Certificate::create($data);
            $certCode = $certificate->code ?: $certificate->certificate_code;

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "El certificado '{$certCode}' ha sido registrado exitosamente.",
                    'certificate' => $certificate->load(['user', 'course']),
                ], 201);
            }

            return redirect()->route('admin.certificates.index')
                ->with('success', "El certificado '{$certCode}' ha sido registrado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error registrando certificado: '.$e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al registrar el certificado: '.$e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al registrar el certificado.');
        }
    }

    /**
     * Display the specified certificate with its details (scores/modules).
     */
    public function show(Certificate $certificate): JsonResponse
    {
        $certificate->load(['user', 'course.modules', 'details.module', 'studyProgram', 'course.studyProgram']);

        return response()->json($certificate);
    }

    /**
     * Display the official printable certificate document with QR validation.
     */
    public function print(Certificate $certificate): View
    {
        $certificate->load([
            'user',
            'course.modules.itineraries',
            'course.modules',
            'course.itineraries',
            'studyProgram',
            'course.studyProgram',
            'details.module',
        ]);

        $enterprise = Enterprise::first() ?? Enterprise::getDefault();

        return view('admin.certificates.print', compact('certificate', 'enterprise'));
    }

    /**
     * Update the specified certificate.
     */
    public function update(CertificateRequest $request, Certificate $certificate): RedirectResponse|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active');

            $certificate->update($data);
            $certCode = $certificate->code ?: $certificate->certificate_code;

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "El certificado '{$certCode}' ha sido actualizado exitosamente.",
                    'certificate' => $certificate->load(['user', 'course']),
                ], 200);
            }

            return redirect()->route('admin.certificates.index')
                ->with('success', "El certificado '{$certCode}' ha sido actualizado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error actualizando certificado: '.$e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al actualizar el certificado: '.$e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al actualizar el certificado.');
        }
    }

    /**
     * Remove the specified certificate.
     */
    public function destroy(Certificate $certificate): RedirectResponse|JsonResponse
    {
        try {
            $code = $certificate->code ?: $certificate->certificate_code;
            $certificate->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "El certificado '{$code}' ha sido eliminado correctamente.",
                ], 200);
            }

            return redirect()->route('admin.certificates.index')
                ->with('success', "El certificado '{$code}' ha sido eliminado correctamente.");

        } catch (\Exception $e) {
            Log::error('Error eliminando certificado: '.$e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el certificado.',
                ], 500);
            }

            return back()->with('error', 'No se pudo eliminar el certificado.');
        }
    }

    /**
     * Remove the specified certificates in bulk.
     */
    public function bulkDelete(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids) || ! is_array($ids)) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Debe seleccionar al menos un certificado para eliminar.',
                    ], 422);
                }

                return back()->with('error', 'Debe seleccionar al menos un certificado para eliminar.');
            }

            $validIds = array_values(array_filter(array_map('intval', $ids)));
            if (empty($validIds)) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Identificadores de certificados no válidos.',
                    ], 422);
                }

                return back()->with('error', 'Identificadores de certificados no válidos.');
            }

            $count = DB::transaction(function () use ($validIds) {
                CertificateDetail::whereIn('certificate_id', $validIds)->delete();

                return Certificate::whereIn('id', $validIds)->delete();
            });

            if ($count === 0) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontraron los certificados seleccionados para eliminar.',
                    ], 404);
                }

                return back()->with('info', 'No se encontraron los certificados seleccionados.');
            }

            $message = $count === 1
                ? 'Se ha eliminado 1 certificado seleccionado correctamente.'
                : "Se han eliminado exitosamente {$count} certificados seleccionados.";

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'count' => $count,
                ], 200);
            }

            return redirect()->route('admin.certificates.index')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error en eliminación masiva de certificados: '.$e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al eliminar los certificados: '.$e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Ocurrió un error al procesar la eliminación masiva.');
        }
    }

    /**
     * Remove all certificates.
     */
    public function deleteAll(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $total = Certificate::count();

            if ($total === 0) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay certificados registrados para eliminar.',
                    ], 404);
                }

                return back()->with('info', 'No hay certificados registrados para eliminar.');
            }

            DB::transaction(function () {
                CertificateDetail::query()->delete();
                Certificate::query()->delete();
            });

            $message = "Se han eliminado exitosamente todos los certificados ({$total} registros).";

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'count' => $total,
                ], 200);
            }

            return redirect()->route('admin.certificates.index')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error eliminando todos los certificados: '.$e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al eliminar todos los certificados: '.$e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Ocurrió un error al eliminar todos los certificados.');
        }
    }

    /**
     * Toggle the active status of a certificate.
     */
    public function toggleStatus(Certificate $certificate): JsonResponse|RedirectResponse
    {
        $certificate->is_active = ! $certificate->is_active;
        $certificate->save();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $certificate->is_active,
                'message' => 'Estado actualizado correctamente.',
            ]);
        }

        return back()->with('success', 'Estado del certificado actualizado.');
    }

    /**
     * Add or update a module detail (grade/score) for a certificate.
     */
    public function storeDetail(CertificateDetailRequest $request, Certificate $certificate): RedirectResponse|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);

            $match = [];
            if (! empty($data['module_id'])) {
                $match['module_id'] = $data['module_id'];
            } elseif (! empty($data['topic_name'])) {
                $match['topic_name'] = $data['topic_name'];
            }

            $detail = $certificate->details()->updateOrCreate(
                $match,
                [
                    'module_id' => $data['module_id'] ?? null,
                    'topic_name' => $data['topic_name'] ?? null,
                    'order' => $data['order'] ?? null,
                    'score' => $data['score'] ?? null,
                    'is_active' => $data['is_active'],
                ]
            );

            if ($request->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Detalle de módulo registrado correctamente.',
                    'detail' => $detail->load('module'),
                ], 200);
            }

            return back()->with('success', 'Detalle de módulo agregado correctamente.');

        } catch (\Exception $e) {
            Log::error('Error registrando detalle de certificado: '.$e->getMessage());

            if ($request->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar el módulo: '.$e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error al registrar el módulo en el certificado.');
        }
    }

    /**
     * Remove a module detail from a certificate.
     */
    public function destroyDetail(CertificateDetail $detail): RedirectResponse|JsonResponse
    {
        try {
            $detail->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Módulo eliminado del certificado.',
                ], 200);
            }

            return back()->with('success', 'Módulo eliminado del certificado.');

        } catch (\Exception $e) {
            Log::error('Error eliminando detalle de certificado: '.$e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el detalle.',
                ], 500);
            }

            return back()->with('error', 'No se pudo eliminar el detalle.');
        }
    }

    /**
     * Download a pre-filled template guide document for importing certificates.
     * Supports Excel (.xlsx, .xls) and CSV (.csv) formats.
     */
    public function downloadTemplate(Request $request): BinaryFileResponse|StreamedResponse
    {
        $format = strtolower((string) $request->query('format', 'csv'));

        if (in_array($format, ['xlsx', 'excel', 'xls'], true)) {
            $ext = $format === 'xls' ? 'xls' : 'xlsx';
            $excelType = $format === 'xls' ? \Maatwebsite\Excel\Excel::XLS : \Maatwebsite\Excel\Excel::XLSX;
            $filename = 'plantilla_guia_certificados_'.date('Y-m-d').'.'.$ext;

            return Excel::download(new CertificateTemplateExport, $filename, $excelType);
        }

        $filename = 'plantilla_certificados_'.date('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');

            // ── BOM so Excel opens UTF-8 correctly ────────────────────────────
            fwrite($out, "\xEF\xBB\xBF");

            // ── Title block ───────────────────────────────────────────────────
            fputcsv($out, ['PLANTILLA DE IMPORTACIÓN DE CERTIFICADOS — FVC']);
            fputcsv($out, ['Formatos compatibles: Microsoft Excel (.xlsx, .xls) y CSV (.csv)']);
            fputcsv($out, ['Generado:', date('d/m/Y H:i'), '', '', '', '', '', '', '', '', '', '', '', '', '', '']);
            fputcsv($out, ['']);

            // ── Instructions block ────────────────────────────────────────────
            fputcsv($out, ['=== INSTRUCCIONES ===']);
            fputcsv($out, ['1. NO modifiques ni elimines la fila de encabezados (fila con "N°", "DNI / N° Documento", etc.)']);
            fputcsv($out, ['2. Ingresa los datos de cada certificado a partir de la fila siguiente al encabezado.']);
            fputcsv($out, ['3. Las columnas marcadas como REQUERIDO no pueden estar vacías.']);
            fputcsv($out, ['4. Las fechas deben tener formato YYYY-MM-DD  (ej: 2026-03-15).']);
            fputcsv($out, ['5. La Modalidad acepta exactamente: Presencial / Virtual / Semipresencial.']);
            fputcsv($out, ['6. El Código (columna O) es opcional; si se deja vacío se auto-genera como CERT-{DNI}-{secuencia}.']);
            fputcsv($out, ['7. La Condición / Participación (columna P) define el rol (ej: ASISTENTE, PONENTE, ORGANIZADOR). Opcional — por defecto ASISTENTE.']);
            fputcsv($out, ['8. Las columnas J, L y M son ignoradas durante la importación.']);
            fputcsv($out, ['']);

            // ── Column legend ─────────────────────────────────────────────────
            fputcsv($out, ['=== DETALLE DE COLUMNAS ===']);
            fputcsv($out, ['Columna', 'Nombre', 'Requerido', 'Descripción / Ejemplo']);
            fputcsv($out, ['A', 'N°', 'No', 'Número correlativo de fila. Ignorado al importar.']);
            fputcsv($out, ['B', 'DNI / N° Documento', 'SÍ', 'DNI del estudiante. Si no existe en el sistema se creará automáticamente.  Ej: 74123456']);
            fputcsv($out, ['C', 'Apellidos y Nombres', 'No', 'Nombre completo del estudiante. Solo referencia — ignorado (se usa el registro del sistema).']);
            fputcsv($out, ['D', 'Curso', 'SÍ', 'Nombre exacto del curso tal como está registrado en el sistema.  Ej: Excel Avanzado']);
            fputcsv($out, ['E', 'Fecha de Inicio', 'No', 'Fecha de inicio del certificado en formato YYYY-MM-DD.  Ej: 2026-01-10']);
            fputcsv($out, ['F', 'Fecha de Término', 'No', 'Fecha de término del certificado en formato YYYY-MM-DD.  Ej: 2026-03-20']);
            fputcsv($out, ['G', 'Fecha de Emisión', 'SÍ', 'Fecha en que se emite el certificado en formato YYYY-MM-DD.  Ej: 2026-03-21']);
            fputcsv($out, ['H', 'Horas / Duración', 'No', 'Duración del curso.  Ej: 120 Horas']);
            fputcsv($out, ['I', 'Calificación I (numérica)', 'No', 'Nota numérica del Módulo 1 del curso.  Ej: 17']);
            fputcsv($out, ['J', 'Calificación Letras I', '—', '*** IGNORADA *** (calificación en letras del módulo 1)']);
            fputcsv($out, ['K', 'Calificación II (numérica)', 'No', 'Nota numérica del Módulo 2 del curso.  Ej: 19']);
            fputcsv($out, ['L', 'Calificación Letras II', '—', '*** IGNORADA *** (calificación en letras del módulo 2)']);
            fputcsv($out, ['M', 'Promedio', '—', '*** IGNORADA *** (calculado automáticamente por el sistema)']);
            fputcsv($out, ['N', 'Modalidad', 'SÍ', 'Presencial / Virtual / Semipresencial']);
            fputcsv($out, ['O', 'Código', 'No', 'Código identificador del certificado. Opcional — si está vacío se genera CERT-{DNI}-{secuencia}. Ej: CERT-74123456-1']);
            fputcsv($out, ['P', 'Condición / Participación', 'No', 'Calidad de participación: ASISTENTE / PONENTE / ORGANIZADOR / etc. Opcional — si está vacío se asigna ASISTENTE.']);
            fputcsv($out, ['']);

            // ── Actual data header (this is the row the importer reads as header) ──
            fputcsv($out, [
                'N°',
                'DNI / N° Documento',
                'Apellidos y Nombres',
                'Curso',
                'Fecha de Inicio',
                'Fecha de Término',
                'Fecha de Emisión',
                'Horas',
                'Calificación I (numérica)',
                'Calificación Letras I',
                'Calificación II (numérica)',
                'Calificación Letras II',
                'Promedio',
                'Modalidad',
                'Código',
                'Condición / Participación',
            ]);

            // ── Sample data rows ──────────────────────────────────────────────
            fputcsv($out, [
                '1',
                '74123456',
                'García López, María Elena',
                'Excel Avanzado',
                '2026-01-10',
                '2026-03-20',
                '2026-03-21',
                '120 Horas',
                '17',
                'DIECISIETE',
                '19',
                'DIECINUEVE',
                '18',
                'Presencial',
                'CERT-74123456-1',
                'ASISTENTE',
            ]);

            fputcsv($out, [
                '2',
                '71234568',
                'Rodríguez Quispe, Carlos Alberto',
                'Desarrollo Web Full Stack',
                '2026-02-01',
                '2026-04-15',
                '2026-04-16',
                '180 Horas',
                '20',
                'VEINTE',
                '18',
                'DIECIOCHO',
                '19',
                'Virtual',
                '',
                'PONENTE',
            ]);

            fputcsv($out, [
                '3',
                '70987654',
                'Mamani Flores, Ana Lucía',
                'Inteligencia Artificial Aplicada',
                '2026-03-01',
                '2026-05-10',
                '2026-05-12',
                '90 Horas',
                '18',
                'DIECIOCHO',
                '17',
                'DIECISIETE',
                '17.5',
                'Semipresencial',
                'CERT-IA-2026-003',
                'ORGANIZADOR',
            ]);

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    /**
     * Import certificates in bulk from an Excel (.xlsx / .xls) or CSV file.
     *
     * Real column layout (row 1 = header, data from row 2):
     *  A → N° fila (ignorada)         G → Fecha de Emisión
     *  B → DNI (auto-crea usuario)    H → Horas → duration
     *  C → Apellidos y Nombres        I → Calificación Módulo I
     *  D → Curso (lookup)             J → *** IGNORADA *** (letras)
     *  E → Fecha de Inicio            K → Calificación Módulo II
     *  F → Fecha de Término           L → *** IGNORADA *** (letras)
     *                                 M → Promedio (ignorado)
     *                                 N → Modalidad
     */
    public function import(CertificateImportRequest $request): RedirectResponse
    {
        // ── Suppress iconv multibyte notices during PhpSpreadsheet file reading ──
        // PhpSpreadsheet's StringHelper uses iconv() internally when reading cells
        // that contain accented/special characters stored in non-UTF-8 encodings.
        // This error fires BEFORE collection() is called, so it cannot be caught
        // inside the importer. We install a temporary handler that silently drops
        // these specific PHP notices and restores the original handler afterwards.
        $prevErrorHandler = set_error_handler(
            function (int $errno, string $errstr, string $errfile) use (&$prevErrorHandler): bool {
                if (str_contains($errstr, 'iconv') && str_contains($errstr, 'multibyte')) {
                    return true; // suppress — handled by our cleanString() sanitisation
                }
                // Pass anything else to the original handler
                if ($prevErrorHandler !== null) {
                    return (bool) call_user_func($prevErrorHandler, $errno, $errstr, $errfile);
                }

                return false;
            }
        );

        try {
            $importer = new CertificateImport;
            Excel::import($importer, $request->file('file'));

            // ── Build success message ─────────────────────────────────────────
            $parts = [];

            if ($importer->createdUsers > 0) {
                $parts[] = "{$importer->createdUsers} estudiante(s) registrado(s)";
            }

            $parts[] = "{$importer->importedCount} certificado(s) importado(s)";

            if ($importer->detailCount > 0) {
                $parts[] = "{$importer->detailCount} nota(s) de módulo registrada(s)";
            }

            if ($importer->skippedCount > 0) {
                $parts[] = "{$importer->skippedCount} fila(s) omitida(s)";
            }

            $msg = 'Importación completada: '.implode(', ', $parts).'.';

            $redirect = redirect()->route('admin.certificates.index')->with('success', $msg);

            if (! empty($importer->errors)) {
                $redirect = $redirect->with('import_errors', $importer->errors);
            }

            return $redirect;

        } catch (ValidationException $e) {
            $failures = collect($e->failures())
                ->map(fn ($f) => "Fila {$f->row()}: ".implode(', ', $f->errors()))
                ->take(10)
                ->implode(' | ');

            return redirect()
                ->route('admin.certificates.index')
                ->with('error', "Error de validación en el archivo: {$failures}");

        } catch (\Exception $e) {
            Log::error('Error importando certificados: '.$e->getMessage());

            return redirect()
                ->route('admin.certificates.index')
                ->with('error', 'Error al procesar el archivo: '.$e->getMessage());

        } finally {
            restore_error_handler();
        }
    }
}
