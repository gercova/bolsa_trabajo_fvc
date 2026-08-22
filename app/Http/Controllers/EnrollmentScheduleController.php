<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentScheduleRequest;
use App\Models\EnrollmentSchedule;
use App\Models\EnrollmentScheduleDetail;
use App\Models\StudyProgram;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnrollmentScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search'       => 'nullable|string|max:255',
            'type'         => 'nullable|string|in:ordinaria,extraordinaria',
            'status'       => 'nullable|string|in:active,inactive',
            'sort_by'      => 'nullable|string|in:id,academic_period,enrollment_type,start_date,end_date,enrollment_fee,is_active,created_at',
            'sort_order'   => 'nullable|string|in:asc,desc',
            'per_page'     => 'nullable|integer|min:1|max:100',
        ]);

        $perPage   = $validated['per_page']   ?? 15;
        $sortBy    = $validated['sort_by']    ?? 'created_at';
        $sortOrder = $validated['sort_order'] ?? 'desc';

        $query = EnrollmentSchedule::with('details.program');

        if (!empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('academic_period', 'LIKE', "%{$search}%")
                  ->orWhere('observations', 'LIKE', "%{$search}%");
            });
        }
        if (!empty($validated['type'])) {
            $query->where('enrollment_type', $validated['type']);
        }
        if (!empty($validated['status'])) {
            $query->where('is_active', $validated['status'] === 'active');
        }

        $schedules = $query->orderBy($sortBy, $sortOrder)->paginate($perPage)->withQueryString();

        return view('admin.enrollment-schedules.index', compact('schedules'));
    }

    public function create(): View
    {
        $programs = StudyProgram::where('is_active', true)->get();
        return view('admin.enrollment-schedules.create', compact('programs'));
    }

    public function store(EnrollmentScheduleRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $details = $validated['details'] ?? [];
        unset($validated['details']);

        $schedule = EnrollmentSchedule::create($validated);

        foreach ($details as $detail) {
            if (!empty($detail['program_id'])) {
                $schedule->details()->create([
                    'program_id'       => $detail['program_id'],
                    'available_slots'  => $detail['available_slots'] ?? 0,
                    'observations'     => $detail['observations'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Cronograma de matrícula creado correctamente.');
    }

    public function edit(EnrollmentSchedule $schedule): View
    {
        $schedule->load('details.program');
        $programs = StudyProgram::where('is_active', true)->get();
        $existingDetails = $schedule->details->keyBy('program_id');

        return view('admin.enrollment-schedules.edit', compact('schedule', 'programs', 'existingDetails'));
    }

    public function update(EnrollmentScheduleRequest $request, EnrollmentSchedule $schedule): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $details = $validated['details'] ?? [];
        unset($validated['details']);

        $schedule->update($validated);

        // Sync details
        $schedule->details()->delete();
        foreach ($details as $detail) {
            if (!empty($detail['program_id'])) {
                $schedule->details()->create([
                    'program_id'      => $detail['program_id'],
                    'available_slots' => $detail['available_slots'] ?? 0,
                    'observations'    => $detail['observations'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Cronograma de matrícula actualizado correctamente.');
    }

    public function destroy(EnrollmentSchedule $schedule): RedirectResponse
    {
        $schedule->details()->delete();
        $schedule->delete();
        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Cronograma de matrícula eliminado correctamente.');
    }

    public function toggleStatus(EnrollmentSchedule $schedule): RedirectResponse
    {
        $schedule->update(['is_active' => !$schedule->is_active]);
        return redirect()->back()
            ->with('success', 'Estado del cronograma actualizado correctamente.');
    }
}
