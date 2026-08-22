<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoricalReviewRequest;
use App\Models\HistoricalReview;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HistoricalReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $histories = HistoricalReview::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%")
                      ->orWhere('start_year', 'LIKE', "%{$search}%")
                      ->orWhere('end_year', 'LIKE', "%{$search}%");
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', $status === 'active' || $status === '1');
            })
            ->orderBy('order', 'asc')
            ->orderBy('start_year', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.history.index', compact('histories', 'search', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $nextOrder = (HistoricalReview::max('order') ?? 0) + 1;
        return view('admin.history.create', compact('nextOrder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HistoricalReviewRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $validated['order'] ?? ((HistoricalReview::max('order') ?? 0) + 1);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('history', 'public');
            $validated['image_path'] = $path;
        }

        $history = HistoricalReview::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hito histórico registrado con éxito.',
                'data'    => $history,
            ], 201);
        }

        return redirect()->route('admin.history.index')->with('success', 'Hito histórico registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HistoricalReview $history): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $history,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistoricalReview $history): View
    {
        return view('admin.history.edit', compact('history'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HistoricalReviewRequest $request, HistoricalReview $history): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $validated['order'] ?? $history->order;

        // Handle image removal if requested
        if ($request->boolean('remove_image')) {
            if ($history->image_path && Storage::disk('public')->exists($history->image_path)) {
                Storage::disk('public')->delete($history->image_path);
            }
            $validated['image_path'] = null;
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            if ($history->image_path && Storage::disk('public')->exists($history->image_path)) {
                Storage::disk('public')->delete($history->image_path);
            }
            $path = $request->file('image')->store('history', 'public');
            $validated['image_path'] = $path;
        }

        $history->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hito histórico actualizado correctamente.',
                'data'    => $history->fresh(),
            ]);
        }

        return redirect()->route('admin.history.index')->with('success', 'Hito histórico actualizado correctamente.');
    }

    /**
     * Toggle status (is_active) of specified resource.
     */
    public function toggleStatus(HistoricalReview $history): RedirectResponse|JsonResponse
    {
        $history->update([
            'is_active' => !$history->is_active,
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Estado del hito histórico actualizado.',
                'is_active' => $history->is_active,
            ]);
        }

        return back()->with('success', 'Estado del hito histórico actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HistoricalReview $history): RedirectResponse|JsonResponse
    {
        if ($history->image_path && Storage::disk('public')->exists($history->image_path)) {
            Storage::disk('public')->delete($history->image_path);
        }

        $history->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'El hito histórico ha sido eliminado correctamente.',
            ]);
        }

        return redirect()->route('admin.history.index')->with('success', 'Hito histórico eliminado correctamente.');
    }
}
