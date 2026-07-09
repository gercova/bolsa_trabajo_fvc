<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function create(): View {
        //
        return view('admin.programs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse {
        //
        return response()->json([

        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse {
        //
        return response()->json();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View {
        //
        return view('admin.programs.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudyProgram $program): JsonResponse {
        //
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudyProgram $program): JsonResponse {
        //
        $program->delete();
        return response()->json([
            'status'    => true,
            'message'   => 'Programa de estudio eliminado'
        ]);
    }
}
