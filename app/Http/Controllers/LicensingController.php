<?php

namespace App\Http\Controllers;

use App\Http\Requests\LicensingPhaseRequest;
use App\Models\LicensingPhase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LicensingController extends Controller
{
    /**
     * Display a listing of the licensing phases.
     */
    public function index(Request $request): View
    {
        $query = LicensingPhase::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('resolution_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('is_active') && $request->is_active !== null && $request->is_active !== '') {
            $query->where('is_active', $request->is_active === '1' || $request->is_active === 'active');
        }

        $phases = $query->orderBy('phase_number', 'asc')->paginate(15)->withQueryString();

        $stats = [
            'total'       => LicensingPhase::count(),
            'completed'   => LicensingPhase::where('status', 'completed')->count(),
            'in_progress' => LicensingPhase::where('status', 'in_progress')->count(),
            'current'     => LicensingPhase::where('is_current', true)->first(),
            'avg_progress'=> round(LicensingPhase::avg('progress_percentage') ?? 0),
        ];

        return view('admin.licensing.index', compact('phases', 'stats'));
    }

    /**
     * Show the form for creating a new licensing phase.
     */
    public function create(): View
    {
        $nextPhaseNumber = (LicensingPhase::max('phase_number') ?? 0) + 1;
        $nextOrder = (LicensingPhase::max('order') ?? 0) + 1;

        return view('admin.licensing.create', compact('nextPhaseNumber', 'nextOrder'));
    }

    /**
     * Store a newly created licensing phase in storage.
     */
    public function store(LicensingPhaseRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $data['file_path'] = $file->store('licensing', 'public');
        }

        $data['is_active']              = $request->boolean('is_active', true);
        $data['is_current']             = $request->boolean('is_current', false);
        $data['progress_percentage']    = $request->input('progress_percentage', 0);
        $data['order']                  = $request->input('order', $request->input('phase_number', 1));

        // Auto-assign stage tag if not provided
        if (empty($data['stage_tag'])) {
            $data['stage_tag'] = match ($data['status']) {
                'completed'   => 'C',
                'in_progress' => 'P',
                'observed'    => 'OBS',
                default       => 'PTE',
            };
        }

        // Process milestones JSON if sent
        if ($request->filled('milestones_json')) {
            $decoded = json_decode($request->milestones_json, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $data['milestones'] = $decoded;
            }
        }
        unset($data['milestones_json']);

        // If this is set as current stage, reset others
        if ($data['is_current']) {
            LicensingPhase::where('is_current', true)->update(['is_current' => false]);
        }

        LicensingPhase::create($data);

        return redirect()->route('admin.licensing.index')
            ->with('success', 'La fase del proceso de licenciamiento ha sido creada exitosamente.');
    }

    /**
     * Show the form for editing the specified licensing phase.
     */
    public function edit(LicensingPhase $licensing): View 
    {
        return view('admin.licensing.edit', compact('licensing'));
    }

    /**
     * Update the specified licensing phase in storage.
     */
    public function update(LicensingPhaseRequest $request, LicensingPhase $licensing): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('file_path')) {
            if ($licensing->file_path && Storage::disk('public')->exists($licensing->file_path)) {
                Storage::disk('public')->delete($licensing->file_path);
            }
            $file = $request->file('file_path');
            $data['file_path'] = $file->store('licensing', 'public');
        } else {
            unset($data['file_path']);
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_current'] = $request->boolean('is_current', false);
        $data['progress_percentage'] = $request->input('progress_percentage', 0);
        $data['order'] = $request->input('order', $request->input('phase_number', 1));

        if (empty($data['stage_tag'])) {
            $data['stage_tag'] = match ($data['status']) {
                'completed'   => 'C',
                'in_progress' => 'P',
                'observed'    => 'OBS',
                default       => 'PTE',
            };
        }

        // Process milestones JSON if sent
        if ($request->filled('milestones_json')) {
            $decoded = json_decode($request->milestones_json, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $data['milestones'] = $decoded;
            }
        }
        unset($data['milestones_json']);

        // If this is set as current stage, reset others
        if ($data['is_current']) {
            LicensingPhase::where('id', '!=', $licensing->id)
                ->where('is_current', true)
                ->update(['is_current' => false]);
        }

        $licensing->update($data);

        return redirect()->route('admin.licensing.index')
            ->with('success', 'La fase del licenciamiento ha sido actualizada exitosamente.');
    }

    /**
     * Remove the specified licensing phase from storage.
     */
    public function destroy(LicensingPhase $licensing): RedirectResponse
    {
        if ($licensing->file_path && Storage::disk('public')->exists($licensing->file_path)) {
            Storage::disk('public')->delete($licensing->file_path);
        }

        $licensing->delete();

        return redirect()->route('admin.licensing.index')
            ->with('success', 'La fase del licenciamiento ha sido eliminada correctamente.');
    }

    /**
     * Toggle active status of the specified phase.
     */
    public function toggleStatus(Request $request, LicensingPhase $licensing): RedirectResponse|JsonResponse
    {
        $licensing->update([
            'is_active' => !$licensing->is_active,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'   => true,
                'is_active' => $licensing->is_active,
                'message'   => 'Estado actualizado correctamente.',
            ]);
        }

        return redirect()->back()
            ->with('success', 'El estado de la fase ha sido modificado.');
    }

    /**
     * Set a specific phase as the primary current stage (P).
     */
    public function setCurrentStage(Request $request, LicensingPhase $licensing): RedirectResponse|JsonResponse
    {
        LicensingPhase::where('is_current', true)->update(['is_current' => false]);

        $licensing->update([
            'is_current' => true,
            'status'     => 'in_progress',
            'stage_tag'  => 'P',
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "La fase {$licensing->phase_number} ha sido establecida como la Etapa Actual (P).",
            ]);
        }

        return redirect()->back()
            ->with('success', "La fase {$licensing->phase_number} ha sido establecida como la Etapa Actual (P).");
    }
}
