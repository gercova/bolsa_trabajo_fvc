<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScholarshipBeneficiaryRequest;
use App\Models\ScholarshipBeneficiary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ScholarshipBeneficiaryController extends Controller
{
    /**
     * Store a newly created scholarship beneficiary PDF document.
     */
    public function store(ScholarshipBeneficiaryRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = 'padron_' . Str::slug($validated['academic_period']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('scholarships/beneficiaries', $filename, 'public');
            $validated['file_path'] = $path;
            $validated['file_size'] = $file->getSize();
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        if (empty($validated['publication_date'])) {
            $validated['publication_date'] = now()->toDateString();
        }

        $beneficiary = ScholarshipBeneficiary::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Padrón de beneficiarios subido correctamente.',
                'data'    => $beneficiary->load('scholarship'),
            ], 201);
        }

        return redirect()->route('admin.scholarships.index', ['tab' => 'beneficiaries'])
            ->with('success', 'El archivo PDF del padrón de beneficiarios fue subido exitosamente.');
    }

    /**
     * Get beneficiary document details for editing.
     */
    public function edit(ScholarshipBeneficiary $beneficiary): JsonResponse
    {
        $beneficiary->load('scholarship');

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                => $beneficiary->id,
                'academic_period'   => $beneficiary->academic_period,
                'title'             => $beneficiary->title,
                'description'       => $beneficiary->description,
                'resolution_number' => $beneficiary->resolution_number,
                'publication_date'  => $beneficiary->publication_date ? $beneficiary->publication_date->format('Y-m-d') : null,
                'scholarship_id'    => $beneficiary->scholarship_id,
                'is_active'         => $beneficiary->is_active,
                'sort_order'        => $beneficiary->sort_order,
                'file_url'          => $beneficiary->file_url,
                'file_size'         => $beneficiary->formatted_file_size,
            ],
        ]);
    }

    /**
     * Update the specified scholarship beneficiary PDF document.
     */
    public function update(ScholarshipBeneficiaryRequest $request, ScholarshipBeneficiary $beneficiary): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('file')) {
            // Delete old file if present
            if ($beneficiary->file_path && Storage::disk('public')->exists($beneficiary->file_path)) {
                Storage::disk('public')->delete($beneficiary->file_path);
            }

            $file = $request->file('file');
            $filename = 'padron_' . Str::slug($validated['academic_period']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('scholarships/beneficiaries', $filename, 'public');
            $validated['file_path'] = $path;
            $validated['file_size'] = $file->getSize();
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $beneficiary->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Padrón de beneficiarios actualizado correctamente.',
                'data'    => $beneficiary->fresh()->load('scholarship'),
            ]);
        }

        return redirect()->route('admin.scholarships.index', ['tab' => 'beneficiaries'])
            ->with('success', 'El padrón de beneficiarios ha sido actualizado correctamente.');
    }

    /**
     * Toggle active status of beneficiary document.
     */
    public function toggleStatus(ScholarshipBeneficiary $beneficiary): RedirectResponse|JsonResponse
    {
        $beneficiary->update([
            'is_active' => !$beneficiary->is_active,
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Estado del padrón actualizado con éxito.',
                'is_active' => $beneficiary->is_active,
            ]);
        }

        return redirect()->route('admin.scholarships.index', ['tab' => 'beneficiaries'])
            ->with('success', 'Estado del padrón de beneficiarios actualizado correctamente.');
    }

    /**
     * Delete the specified beneficiary record and its PDF file.
     */
    public function destroy(ScholarshipBeneficiary $beneficiary): RedirectResponse|JsonResponse
    {
        if ($beneficiary->file_path && Storage::disk('public')->exists($beneficiary->file_path)) {
            Storage::disk('public')->delete($beneficiary->file_path);
        }

        $beneficiary->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Padrón de beneficiarios eliminado correctamente.',
            ]);
        }

        return redirect()->route('admin.scholarships.index', ['tab' => 'beneficiaries'])
            ->with('success', 'Padrón de beneficiarios eliminado correctamente.');
    }

    /**
     * Download the beneficiary PDF document.
     */
    public function download(ScholarshipBeneficiary $beneficiary): StreamedResponse|RedirectResponse
    {
        if (!$beneficiary->file_path || !Storage::disk('public')->exists($beneficiary->file_path)) {
            return back()->with('error', 'El archivo PDF no se encuentra disponible.');
        }

        $safeName = 'padron_beneficiarios_' . Str::slug($beneficiary->academic_period . '_' . $beneficiary->title) . '.pdf';

        return Storage::disk('public')->download($beneficiary->file_path, $safeName);
    }
}
