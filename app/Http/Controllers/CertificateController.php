<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateDetailRequest;
use App\Http\Requests\CertificateImportRequest;
use App\Http\Requests\CertificateRequest;
use App\Imports\CertificateImport;
use App\Models\Certificate;
use App\Models\CertificateDetail;
use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CertificateController extends Controller
{
    /**
     * Display a listing of certificates.
     */
    public function index(Request $request): View
    {
        $search   = $request->input('search');
        $courseId = $request->input('course_id');
        $userId   = $request->input('user_id');
        $modality = $request->input('modality');
        $status   = $request->input('status');

        $query = Certificate::with(['user', 'course', 'details.module'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('certificate_code', 'LIKE', "%{$search}%")
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
        $courses      = Course::where('is_active', true)->with('modules')->orderBy('name')->get();
        $users        = User::where('is_active', true)->orderBy('names')->get(['id', 'names', 'dni', 'email']);

        // Stat counters
        $totalCertificates    = Certificate::count();
        $activeCertificates   = Certificate::where('is_active', true)->count();
        $presencialCount      = Certificate::where('modality', 'Presencial')->count();
        $virtualSemipresCount = Certificate::whereIn('modality', ['Virtual', 'Semipresencial'])->count();
        $totalDownloads       = (int) Certificate::sum('download_count');
        $issuedCoursesCount   = Certificate::distinct('course_id')->count('course_id');

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

            $certificate = Certificate::create($data);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'     => true,
                    'message'     => "El certificado '{$certificate->certificate_code}' ha sido registrado exitosamente.",
                    'certificate' => $certificate->load(['user', 'course']),
                ], 201);
            }

            return redirect()->route('admin.certificates.index')
                ->with('success', "El certificado '{$certificate->certificate_code}' ha sido registrado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error registrando certificado: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al registrar el certificado: ' . $e->getMessage(),
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
        $certificate->load(['user', 'course.modules', 'details.module']);
        return response()->json($certificate);
    }

    /**
     * Update the specified certificate.
     */
    public function update(CertificateRequest $request, Certificate $certificate): RedirectResponse|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);

            $certificate->update($data);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'     => true,
                    'message'     => "El certificado '{$certificate->certificate_code}' ha sido actualizado exitosamente.",
                    'certificate' => $certificate->load(['user', 'course']),
                ], 200);
            }

            return redirect()->route('admin.certificates.index')
                ->with('success', "El certificado '{$certificate->certificate_code}' ha sido actualizado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error actualizando certificado: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al actualizar el certificado: ' . $e->getMessage(),
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
            $code = $certificate->certificate_code;
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
            Log::error('Error eliminando certificado: ' . $e->getMessage());

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
     * Toggle the active status of a certificate.
     */
    public function toggleStatus(Certificate $certificate): JsonResponse|RedirectResponse
    {
        $certificate->is_active = !$certificate->is_active;
        $certificate->save();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success'   => true,
                'is_active' => $certificate->is_active,
                'message'   => 'Estado actualizado correctamente.',
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

            $detail = $certificate->details()->updateOrCreate(
                ['module_id' => $data['module_id']],
                [
                    'score'     => $data['score'] ?? null,
                    'is_active' => $data['is_active'],
                ]
            );

            if ($request->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Detalle de módulo registrado correctamente.',
                    'detail'  => $detail->load('module'),
                ], 200);
            }

            return back()->with('success', 'Detalle de módulo agregado correctamente.');

        } catch (\Exception $e) {
            Log::error('Error registrando detalle de certificado: ' . $e->getMessage());

            if ($request->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar el módulo: ' . $e->getMessage(),
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
            Log::error('Error eliminando detalle de certificado: ' . $e->getMessage());

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
            $importer = new CertificateImport();
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

            $msg = 'Importación completada: ' . implode(', ', $parts) . '.';

            $redirect = redirect()->route('admin.certificates.index')->with('success', $msg);

            if (! empty($importer->errors)) {
                $redirect = $redirect->with('import_errors', $importer->errors);
            }

            return $redirect;

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = collect($e->failures())
                ->map(fn ($f) => "Fila {$f->row()}: " . implode(', ', $f->errors()))
                ->take(10)
                ->implode(' | ');

            return redirect()
                ->route('admin.certificates.index')
                ->with('error', "Error de validación en el archivo: {$failures}");

        } catch (\Exception $e) {
            Log::error('Error importando certificados: ' . $e->getMessage());

            return redirect()
                ->route('admin.certificates.index')
                ->with('error', 'Error al procesar el archivo: ' . $e->getMessage());

        } finally {
            restore_error_handler();
        }
    }
}
