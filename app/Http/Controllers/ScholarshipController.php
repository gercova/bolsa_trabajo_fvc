<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScholarshipRequest;
use App\Models\Scholarship;
use App\Models\ScholarshipBeneficiary;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $scholarships = Scholarship::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', $status === 'active' || $status === '1');
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(10, ['*'], 'scholarships_page')
            ->withQueryString();

        // Beneficiaries Query
        $beneficiarySearch = $request->input('beneficiary_search');
        $beneficiaryPeriod = $request->input('beneficiary_period');
        $beneficiaryStatus = $request->input('beneficiary_status');

        $beneficiariesQuery = ScholarshipBeneficiary::with('scholarship')
            ->when($beneficiarySearch, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('resolution_number', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when($beneficiaryPeriod, function ($query, $period) {
                $query->where('academic_period', $period);
            })
            ->when($beneficiaryStatus !== null && $beneficiaryStatus !== '', function ($query) use ($beneficiaryStatus) {
                $query->where('is_active', $beneficiaryStatus === 'active' || $beneficiaryStatus === '1');
            })
            ->orderBy('academic_period', 'desc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');

        $beneficiaries = $beneficiariesQuery->paginate(10, ['*'], 'beneficiaries_page')->withQueryString();

        $allScholarships    = Scholarship::orderBy('sort_order')->orderBy('name')->get();
        $periods            = ScholarshipBeneficiary::distinct()->orderBy('academic_period', 'desc')->pluck('academic_period');
        $totalModalities    = Scholarship::count();
        $totalBeneficiaries = ScholarshipBeneficiary::count();

        // Active tab determination
        $activeTab = $request->input('tab');
        if (!$activeTab) {
            if ($request->hasAny(['beneficiary_search', 'beneficiary_period', 'beneficiary_status', 'beneficiaries_page'])) {
                $activeTab = 'beneficiaries';
            } else {
                $activeTab = 'modalities';
            }
        }

        return view('admin.scholarships.index', compact(
            'scholarships',
            'search',
            'status',
            'beneficiaries',
            'beneficiarySearch',
            'beneficiaryPeriod',
            'beneficiaryStatus',
            'allScholarships',
            'periods',
            'totalModalities',
            'totalBeneficiaries',
            'activeTab'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.scholarships.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScholarshipRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $scholarship = Scholarship::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Modalidad de beca creada con éxito.',
                'data'    => $scholarship,
            ], 201);
        }

        return redirect()->route('admin.scholarships.index')->with('success', 'Modalidad de beca creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scholarship $scholarship): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $scholarship,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scholarship $scholarship): View
    {
        return view('admin.scholarships.edit', compact('scholarship'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScholarshipRequest $request, Scholarship $scholarship): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $scholarship->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Modalidad de beca actualizada correctamente.',
                'data'    => $scholarship->fresh(),
            ]);
        }

        return redirect()->route('admin.scholarships.index')->with('success', 'Modalidad de beca actualizada correctamente.');
    }

    /**
     * Toggle status (is_active) of specified resource.
     */
    public function toggleStatus(Scholarship $scholarship): RedirectResponse|JsonResponse
    {
        $scholarship->update([
            'is_active' => !$scholarship->is_active,
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Estado de la beca actualizado correctamente.',
                'is_active' => $scholarship->is_active,
            ]);
        }

        return back()->with('success', 'Estado de la modalidad de beca actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scholarship $scholarship): RedirectResponse|JsonResponse
    {
        $scholarship->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'La modalidad de beca ha sido eliminada.',
            ]);
        }

        return redirect()->route('admin.scholarships.index')->with('success', 'Modalidad de beca eliminada correctamente.');
    }
}
