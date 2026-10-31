<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PartnersController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $partners = Partner::when($search, function ($query) use ($search) {
            $query->where('company', 'LIKE', "%{$search}%");
        })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Partners/Index', [
            'partners' => $partners,
            'search' => $search ?? '',
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Partners/Create');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('partners', 'public');
            $validated['image_url'] = $path;
        }

        $partner = Partner::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Partner creado correctamente.',
                'data' => $partner,
            ], 201);
        }

        return redirect()->route('admin.partners.index')->with('success', 'Partner registrado exitosamente.');
    }

    public function show(Partner $partner): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $partner]);
    }

    public function edit(Partner $partner): Response
    {
        return Inertia::render('Admin/Partners/Edit', [
            'partner' => $partner,
        ]);
    }

    public function update(Request $request, Partner $partner): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : $partner->is_active;

        if ($request->hasFile('image')) {
            if ($partner->image_url && Storage::disk('public')->exists($partner->image_url)) {
                Storage::disk('public')->delete($partner->image_url);
            }
            $path = $request->file('image')->store('partners', 'public');
            $validated['image_url'] = $path;
        }

        $partner->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Partner actualizado correctamente.',
                'data' => $partner->fresh(),
            ]);
        }

        return redirect()->route('admin.partners.index')->with('success', 'Partner actualizado exitosamente.');
    }

    public function toggleStatus(Partner $partner): RedirectResponse|JsonResponse
    {
        $partner->is_active = ! $partner->is_active;
        $partner->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Estado actualizado.', 'is_active' => $partner->is_active]);
        }

        return back()->with('success', 'Estado del socio actualizado correctamente.');
    }

    public function destroy(Partner $partner): RedirectResponse|JsonResponse
    {
        if ($partner->image_url && Storage::disk('public')->exists($partner->image_url)) {
            Storage::disk('public')->delete($partner->image_url);
        }
        $partner->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'El Partner fue eliminado correctamente',
            ], 200);
        }

        return redirect()->route('admin.partners.index')->with('success', 'Partner eliminado exitosamente.');
    }
}
