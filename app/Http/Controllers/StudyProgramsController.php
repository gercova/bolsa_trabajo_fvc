<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudyProgramsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(): View {
        $programs = StudyProgram::where('is_active', true)->get();
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
