<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnersController extends Controller {
    
    public function index(Request $request): View {
        $search = $request->input('search');
        $partners = Partner::when($search, function ($query) use ($search) {
            $query->where('company', 'LIKE', "%{$search}%");
        })->paginate(10);

        return view('admin.partners.index', compact('partners'));
    }

    public function create(): View {
        return view('admin.partners.create');
    }

    public function store(Request $request): JsonResponse {
        $validated = $request->validate([
            'company'   => 'required|string|max:255',
            'image'     => 'required|image|mimes:png,jpg,jpeg,gif,webp|max:2048', // Validación de imagen
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('partners', 'public');
            $validated['image_url'] = $path;
        }

        $partner = Partner::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Partner creado correctamente.',
            'data'    => $partner,
        ], 201);
    }

    public function show(Partner $partner): JsonResponse {
        return response()->json(['success' => true, 'data' => $partner]);
    }

    public function edit(Partner $partner): View {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner): JsonResponse {
        $validated = $request->validate([
            'company'   => 'required|string|max:255',
            'image'     => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($partner->image_url && Storage::disk('public')->exists($partner->image_url)) {
                Storage::disk('public')->delete($partner->image_url);
            }
            $path = $request->file('image')->store('partners', 'public');
            $validated['image_url'] = $path;
        }

        $partner->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Partner actualizado correctamente.',
            'data'    => $partner->fresh(),
        ]);
    }

    public function toggleStatus(Partner $partner): JsonResponse {
        $partner->is_active = !$partner->is_active;
        $partner->save();
        return response()->json(['success' => true, 'message' => 'Estado actualizado.']);
    }

    public function destroy(Partner $partner): JsonResponse {
        // Eliminar la imagen asociada al borrar el registro
        if ($partner->image_url && Storage::disk('public')->exists($partner->image_url)) {
            Storage::disk('public')->delete($partner->image_url);
        }
        $partner->delete();

        return response()->json([
            'success' => true,
            'message' => 'El Partner fue eliminado correctamente'
        ], 200);
    }
}