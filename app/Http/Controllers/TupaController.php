<?php

namespace App\Http\Controllers;

use App\Http\Requests\TupaRequest;
use App\Models\Tupa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TupaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search'     => 'nullable|string|max:255',
            'status'     => 'nullable|string|in:active,inactive',
            'year'       => 'nullable|integer',
            'sort_by'    => 'nullable|string|in:id,title,effective_start_date,effective_end_date,is_active,created_at',
            'sort_order' => 'nullable|string|in:asc,desc',
            'per_page'   => 'nullable|integer|min:1|max:100',
        ]);

        $query = Tupa::query();

        if (!empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($validated['status'])) {
            $query->where('is_active', $validated['status'] === 'active');
        }

        if (!empty($validated['year'])) {
            $query->whereYear('effective_start_date', $validated['year']);
        }

        $sortBy = $validated['sort_by'] ?? 'effective_start_date';
        $sortOrder = $validated['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $tupas = $query->paginate($validated['per_page'] ?? 10)->withQueryString();

        return view('admin.tupa.index', compact('tupas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.tupa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TupaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('file_path')) {
            $path = $request->file('file_path')->store('tupa', 'public');
            $validated['file_path'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        Tupa::create($validated);

        return redirect()->route('admin.tupa.index')->with('success', 'Registro TUPA creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tupa $tupa): View
    {
        return view('admin.tupa.edit', compact('tupa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TupaRequest $request, Tupa $tupa): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('file_path')) {
            if ($tupa->file_path && Storage::disk('public')->exists($tupa->file_path)) {
                Storage::disk('public')->delete($tupa->file_path);
            }
            $path = $request->file('file_path')->store('tupa', 'public');
            $validated['file_path'] = $path;
        } else {
            unset($validated['file_path']);
        }

        $validated['is_active'] = $request->has('is_active');

        $tupa->update($validated);

        return redirect()->route('admin.tupa.index')->with('success', 'Registro TUPA actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tupa $tupa): RedirectResponse
    {
        if ($tupa->file_path && Storage::disk('public')->exists($tupa->file_path)) {
            Storage::disk('public')->delete($tupa->file_path);
        }

        $tupa->delete();

        return redirect()->route('admin.tupa.index')->with('success', 'Registro TUPA eliminado exitosamente.');
    }

    /**
     * Toggle status of the specified TUPA.
     */
    public function toggleStatus(Tupa $tupa): RedirectResponse
    {
        $tupa->update([
            'is_active' => !$tupa->is_active,
        ]);

        return redirect()->back()->with('success', 'Estado del registro TUPA actualizado correctamente.');
    }
}
