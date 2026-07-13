<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use App\Http\Requests\StudyProgramValidate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StudyProgramsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = StudyProgram::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'ILIKE', "%$search%")
                ->orWhere('description', 'ILIKE', "%$search%");
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $status = $request->status === 'active';
            $query->where('is_active', $status);
        }

        // Sorting
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $programs = $query->paginate($request->per_page ?? 10);

        return view('admin.programs.index', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.programs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudyProgramValidate $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('logo_path')) {
            $path = $request->file('logo_path')->store('programs', 'public');
            $validated['logo_path'] = $path;
        }

        StudyProgram::create($validated);

        return redirect()->route('admin.programs.index')->with('success', 'Programa de estudio creado correctamente');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudyProgram $program): View
    {
        return view('admin.programs.edit', compact('program'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudyProgramValidate $request, StudyProgram $program): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('logo_path')) {
            if ($program->logo_path) {
                Storage::disk('public')->delete($program->logo_path);
            }
            $path = $request->file('logo_path')->store('programs', 'public');
            $validated['logo_path'] = $path;
        }

        $program->update($validated);

        return redirect()->route('admin.programs.index')->with('success', 'Programa de estudio actualizado correctamente');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(StudyProgram $program): RedirectResponse
    {
        $program->update([
            'is_active' => !$program->is_active
        ]);

        return redirect()->back()->with('success', 'Estado del programa de estudio actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudyProgram $program): RedirectResponse
    {
        if ($program->logo_path) {
            Storage::disk('public')->delete($program->logo_path);
        }
        $program->delete();

        return redirect()->route('admin.programs.index')->with('success', 'Programa de estudio eliminado correctamente');
    }
}
