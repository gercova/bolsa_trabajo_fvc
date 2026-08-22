<?php

namespace App\Http\Controllers;

use App\Http\Requests\TupaCategoryRequest;
use App\Http\Requests\TupaProcedureRequest;
use App\Http\Requests\TupaRequest;
use App\Models\Tupa;
use App\Models\TupaCategory;
use App\Models\TupaProcedure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TupaController extends Controller
{
    /**
     * Display a listing of the resources (TUPA Documents, Categories, Procedures).
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search'           => 'nullable|string|max:255',
            'status'           => 'nullable|string|in:active,inactive',
            'year'             => 'nullable|integer',
            'category_id'      => 'nullable|integer',
            'tupa_id'          => 'nullable|integer',
            'tab'              => 'nullable|string|in:documents,categories,procedures',
            'per_page'         => 'nullable|integer|min:1|max:100',
        ]);

        $activeTab = $validated['tab'] ?? 'documents';

        // 1. Documentos TUPA
        $tupaQuery = Tupa::query();

        if (!empty($validated['search']) && $activeTab === 'documents') {
            $search = $validated['search'];
            $tupaQuery->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($validated['status']) && $activeTab === 'documents') {
            $tupaQuery->where('is_active', $validated['status'] === 'active');
        }

        if (!empty($validated['year']) && $activeTab === 'documents') {
            $tupaQuery->whereYear('effective_start_date', $validated['year']);
        }

        $tupas = $tupaQuery->orderBy('effective_start_date', 'desc')
            ->paginate($validated['per_page'] ?? 10, ['*'], 'tupas_page')
            ->withQueryString();

        // 2. Categorías TUPA (tupa_categories)
        $catQuery = TupaCategory::withCount('procedures');

        if (!empty($validated['search']) && $activeTab === 'categories') {
            $search = $validated['search'];
            $catQuery->where('name', 'LIKE', "%{$search}%");
        }

        if (!empty($validated['status']) && $activeTab === 'categories') {
            $catQuery->where('is_active', $validated['status'] === 'active');
        }

        $categories = $catQuery->orderBy('name', 'asc')
            ->paginate($validated['per_page'] ?? 10, ['*'], 'categories_page')
            ->withQueryString();

        // 3. Procedimientos TUPA (tupa_procedures)
        $procQuery = TupaProcedure::with(['category', 'tupa']);

        if (!empty($validated['search']) && $activeTab === 'procedures') {
            $search = $validated['search'];
            $procQuery->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('office', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($validated['status']) && $activeTab === 'procedures') {
            $procQuery->where('is_active', $validated['status'] === 'active');
        }

        if (!empty($validated['category_id']) && $activeTab === 'procedures') {
            $procQuery->where('category_id', $validated['category_id']);
        }

        if (!empty($validated['tupa_id']) && $activeTab === 'procedures') {
            $procQuery->where('tupa_id', $validated['tupa_id']);
        }

        $procedures = $procQuery->orderBy('code', 'asc')
            ->paginate($validated['per_page'] ?? 10, ['*'], 'procedures_page')
            ->withQueryString();

        $allCategoriesList = TupaCategory::where('is_active', true)->orderBy('name')->get();
        $allTupasList      = Tupa::where('is_active', true)->orderBy('title')->get();

        return view('admin.tupa.index', compact(
            'tupas',
            'categories',
            'procedures',
            'activeTab',
            'allCategoriesList',
            'allTupasList'
        ));
    }

    /**
     * Show the form for creating a new TUPA document.
     */
    public function create(): View
    {
        return view('admin.tupa.create');
    }

    /**
     * Store a newly created TUPA document.
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

        return redirect()->route('admin.tupa.index', ['tab' => 'documents'])->with('success', 'Registro TUPA creado exitosamente.');
    }

    /**
     * Show the form for editing the specified TUPA document.
     */
    public function edit(Tupa $tupa): View
    {
        return view('admin.tupa.edit', compact('tupa'));
    }

    /**
     * Update the specified TUPA document in storage.
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

        return redirect()->route('admin.tupa.index', ['tab' => 'documents'])->with('success', 'Registro TUPA actualizado exitosamente.');
    }

    /**
     * Remove the specified TUPA document from storage.
     */
    public function destroy(Tupa $tupa): RedirectResponse
    {
        if ($tupa->file_path && Storage::disk('public')->exists($tupa->file_path)) {
            Storage::disk('public')->delete($tupa->file_path);
        }

        $tupa->delete();

        return redirect()->route('admin.tupa.index', ['tab' => 'documents'])->with('success', 'Registro TUPA eliminado exitosamente.');
    }

    /**
     * Toggle status of the specified TUPA document.
     */
    public function toggleStatus(Tupa $tupa): RedirectResponse
    {
        $tupa->update([
            'is_active' => !$tupa->is_active,
        ]);

        return redirect()->back()->with('success', 'Estado del registro TUPA actualizado correctamente.');
    }

    // CATEGORÍAS TUPA (tupa_categories)
    public function createCategory(): View
    {
        return view('admin.tupa.categories.create');
    }

    public function storeCategory(TupaCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        TupaCategory::create($validated);

        return redirect()->route('admin.tupa.index', ['tab' => 'categories'])->with('success', 'Categoría TUPA creada exitosamente.');
    }

    public function editCategory(TupaCategory $category): View
    {
        return view('admin.tupa.categories.edit', compact('category'));
    }

    public function updateCategory(TupaCategoryRequest $request, TupaCategory $category): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.tupa.index', ['tab' => 'categories'])->with('success', 'Categoría TUPA actualizada exitosamente.');
    }

    public function destroyCategory(TupaCategory $category): RedirectResponse
    {
        if ($category->procedures()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar la categoría porque tiene procedimientos asociados.');
        }

        $category->delete();

        return redirect()->route('admin.tupa.index', ['tab' => 'categories'])->with('success', 'Categoría TUPA eliminada exitosamente.');
    }

    public function toggleCategoryStatus(TupaCategory $category): RedirectResponse
    {
        $category->update([
            'is_active' => !$category->is_active,
        ]);

        return redirect()->back()->with('success', 'Estado de la categoría actualizado correctamente.');
    }

    
    // PROCEDIMIENTOS TUPA (tupa_procedures)
    public function createProcedure(): View
    {
        $tupas = Tupa::where('is_active', true)->orderBy('title')->get();
        $categories = TupaCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.tupa.procedures.create', compact('tupas', 'categories'));
    }

    public function storeProcedure(TupaProcedureRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (is_string($validated['requirements'])) {
            $validated['requirements'] = array_values(array_filter(
                array_map('trim', explode("\n", $validated['requirements']))
            ));
        }

        $validated['is_active'] = $request->has('is_active');

        TupaProcedure::create($validated);

        return redirect()->route('admin.tupa.index', ['tab' => 'procedures'])->with('success', 'Procedimiento TUPA creado exitosamente.');
    }

    public function editProcedure(TupaProcedure $procedure): View
    {
        $tupas = Tupa::orderBy('title')->get();
        $categories = TupaCategory::orderBy('name')->get();

        return view('admin.tupa.procedures.edit', compact('procedure', 'tupas', 'categories'));
    }

    public function updateProcedure(TupaProcedureRequest $request, TupaProcedure $procedure): RedirectResponse
    {
        $validated = $request->validated();

        if (is_string($validated['requirements'])) {
            $validated['requirements'] = array_values(array_filter(
                array_map('trim', explode("\n", $validated['requirements']))
            ));
        }

        $validated['is_active'] = $request->has('is_active');

        $procedure->update($validated);

        return redirect()->route('admin.tupa.index', ['tab' => 'procedures'])->with('success', 'Procedimiento TUPA actualizado exitosamente.');
    }

    public function destroyProcedure(TupaProcedure $procedure): RedirectResponse
    {
        $procedure->delete();

        return redirect()->route('admin.tupa.index', ['tab' => 'procedures'])->with('success', 'Procedimiento TUPA eliminado exitosamente.');
    }

    public function toggleProcedureStatus(TupaProcedure $procedure): RedirectResponse
    {
        $procedure->update([
            'is_active' => !$procedure->is_active,
        ]);

        return redirect()->back()->with('success', 'Estado del procedimiento actualizado correctamente.');
    }
}
