<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateDetailRequest;
use App\Http\Requests\CertificateRequest;
use App\Models\Certificate;
use App\Models\CertificateDetail;
use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $status   = $request->input('status');

        $query = Certificate::with(['user', 'course', 'details.module'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('certificate_code', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('names', 'LIKE', "%{$search}%")
                                ->orWhere('dni', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('course', function ($cq) use ($search) {
                            $cq->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('code', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($courseId, fn ($q) => $q->where('course_id', $courseId))
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when($status !== null && $status !== '', function ($q) use ($status) {
                $q->where('is_active', (bool) $status);
            })
            ->orderByDesc('issue_date')
            ->orderByDesc('id');

        $certificates = $query->paginate(10)->appends($request->only(['search', 'course_id', 'user_id', 'status']));
        $courses      = Course::where('is_active', true)->with('modules')->orderBy('name')->get();
        $users        = User::where('is_active', true)->orderBy('names')->get(['id', 'names', 'dni', 'email']);

        // Stat counters
        $totalCertificates  = Certificate::count();
        $activeCertificates = Certificate::where('is_active', true)->count();
        $totalDownloads     = (int) Certificate::sum('download_count');
        $issuedCoursesCount = Certificate::distinct('course_id')->count('course_id');

        return view('admin.certificates.index', compact(
            'certificates',
            'courses',
            'users',
            'totalCertificates',
            'activeCertificates',
            'totalDownloads',
            'issuedCoursesCount',
            'search',
            'courseId',
            'userId',
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

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Detalle de módulo registrado correctamente.',
                    'detail'  => $detail->load('module'),
                ], 200);
            }

            return back()->with('success', 'Detalle de módulo agregado correctamente.');

        } catch (\Exception $e) {
            Log::error('Error registrando detalle de certificado: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
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
}
