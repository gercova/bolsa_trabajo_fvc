<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClaimsController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Claim::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('names', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $status);
        }

        $claims = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return Inertia::render('Admin/Claims/Index', [
            'claims' => $claims,
            'search' => $search ?? '',
            'status' => $status ?? '',
        ]);
    }

    public function show(Claim $claim): JsonResponse
    {
        return response()->json($claim);
    }

    public function status(Request $request, Claim $claim): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pendiente,leido,respondido,cerrado',
        ]);
        $claim->update($validated);

        return redirect()->back()->with('success', 'Estado del reclamo actualizado correctamente.');
    }
}
