<?php

namespace App\Http\Controllers;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse {
        //
    }
}
