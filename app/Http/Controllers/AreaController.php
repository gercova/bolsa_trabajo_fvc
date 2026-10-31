<?php

namespace App\Http\Controllers;

use App\Http\Requests\AreaRequest;
use App\Models\Area;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = Area::with(['program', 'user']);

        // Filtro de búsqueda por texto
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%")
                    ->orWhereHas('program', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('names', 'like', "%{$search}%")
                            ->orWhere('last_name1', 'like', "%{$search}%")
                            ->orWhere('last_name2', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filtro por programa de estudio
        if ($request->filled('program_id')) {
            if ($request->program_id === 'general') {
                $query->whereNull('program_id');
            } else {
                $query->where('program_id', $request->program_id);
            }
        }

        // Filtro por responsable
        if ($request->filled('has_user')) {
            if ($request->has_user === 'yes') {
                $query->whereNotNull('user_id');
            } elseif ($request->has_user === 'no') {
                $query->whereNull('user_id');
            }
        }

        // Estadísticas rápidas para tarjetas
        $totalAreas = Area::count();
        $withProgramCount = Area::whereNotNull('program_id')->count();
        $withLeaderCount = Area::whereNotNull('user_id')->count();
        $generalAreasCount = Area::whereNull('program_id')->count();

        $areas = $query->orderBy('name', 'asc')->paginate(12)->withQueryString();
        $programs = StudyProgram::orderBy('name')->get();

        return Inertia::render('Admin/Areas/Index', [
            'areas' => $areas,
            'programs' => $programs,
            'totalAreas' => $totalAreas,
            'withProgramCount' => $withProgramCount,
            'withLeaderCount' => $withLeaderCount,
            'generalAreasCount' => $generalAreasCount,
            'filters' => [
                'search' => $request->search ?? '',
                'program_id' => $request->program_id ?? '',
                'has_user' => $request->has_user ?? '',
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $programs = StudyProgram::orderBy('name')->get();

        // Solo usuarios con rol distinto de estudiante
        $users = User::query()
            ->where(function ($q) {
                $q->whereNull('role')
                    ->orWhereNotIn('role', ['Estudiante', 'estudiante', 'Student', 'student']);
            })
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['Estudiante', 'estudiante', 'Student', 'student']);
            })
            ->where('is_active', true)
            ->orderBy('names')
            ->get();

        return Inertia::render('Admin/Areas/Create', [
            'programs' => $programs,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AreaRequest $request): RedirectResponse
    {
        Area::create($request->validated());

        return redirect()->route('admin.areas.index')
            ->with('success', 'El área institucional ha sido creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area): RedirectResponse
    {
        return redirect()->route('admin.areas.edit', $area);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area): Response
    {
        $programs = StudyProgram::orderBy('name')->get();
        // Solo usuarios con rol distinto de estudiante (o el responsable asignado actualmente)
        $users = User::query()
            ->where(function ($q) use ($area) {
                $q->where(function ($sq) {
                    $sq->whereNull('role')
                        ->orWhereNotIn('role', ['Estudiante', 'estudiante', 'Student', 'student']);
                })
                    ->whereDoesntHave('roles', function ($sq) {
                        $sq->whereIn('name', ['Estudiante', 'estudiante', 'Student', 'student']);
                    });

                if ($area->user_id) {
                    $q->orWhere('id', $area->user_id);
                }
            })
            ->where('is_active', true)
            ->orderBy('names')
            ->get();

        return Inertia::render('Admin/Areas/Edit', [
            'area' => $area,
            'programs' => $programs,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AreaRequest $request, Area $area): RedirectResponse
    {
        $area->update($request->validated());

        return redirect()->route('admin.areas.index')
            ->with('success', 'El área institucional ha sido actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area): RedirectResponse
    {
        $areaName = $area->name;
        $area->delete();

        return redirect()->route('admin.areas.index')
            ->with('success', "El área \"{$areaName}\" ha sido eliminada correctamente.");
    }
}
