<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClaimsController extends Controller
{
    public function index(Request $request): View
    {
        $query = Claim::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('names', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $claims = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.claims.index', compact('claims'));
    }

    public function show(Claim $claim): JsonResponse
    {
        return response()->json($claim);
    }

    public function status(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'status' => 'required|in:pendiente,leido,respondido,cerrado',
        ]);
        $claim->update($validated);
        return redirect()->back()->with('success', 'Estado actualizado correctamente');
    }
}
