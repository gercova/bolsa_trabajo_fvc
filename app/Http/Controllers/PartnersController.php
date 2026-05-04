<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerValidate;
use App\Models\Partner;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnersController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View {
        $search     = $request->input('search');
        $partners   = Partner::when($search, function ($query) use ($search) {
            $query->where('names', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        })->paginate(10);

        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View {
        return view('admin.partners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse {
        $validated  = $request->validated();
        $partner    = Partner::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Partner creado correctamente.',
            'data'    => $partner,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Partner $partner): JsonResponse {
        return response()->json([
            'success'   => true,
            'data'      => $partner,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partner $partner): View {
        return view('admin.partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PartnerValidate $request, Partner $partner): JsonResponse {
        $validated = $request->validated();
        $partner->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Partner actualizado correctamente.',
            'data'    => $partner->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner): JsonResponse {
        $partner->delete();
        return response()->json([
            'success' => true,
            'message' => 'El Partner fue eliminado correctamente'
        ], 200);
    }
}
