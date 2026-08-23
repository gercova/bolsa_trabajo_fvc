<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManagementDocumentRequest;
use App\Models\ManagementDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManagementDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ManagementDocument::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            if ($request->status === 'active' || $request->status === '1') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive' || $request->status === '0') {
                $query->where('is_active', false);
            }
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ManagementDocumentRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $data['file_path'] = $file->store('management-document', 'public');
        }

        if ($request->hasFile('resolution_document_path')) {
            $resolutionFile = $request->file('resolution_document_path');
            $data['resolution_document_path'] = $resolutionFile->store('management-document/resolutions', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');

        ManagementDocument::create($data);

        return redirect()->route('admin.documents.index')
            ->with('success', 'El documento de gestión ha sido creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ManagementDocument $managementDocument)
    {
        return redirect()->route('admin.documents.edit', $managementDocument);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ManagementDocument $managementDocument)
    {
        return view('admin.documents.edit', compact('managementDocument'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ManagementDocumentRequest $request, ManagementDocument $managementDocument)
    {
        $data = $request->validated();

        if ($request->hasFile('file_path')) {
            if ($managementDocument->file_path && Storage::disk('public')->exists($managementDocument->file_path)) {
                Storage::disk('public')->delete($managementDocument->file_path);
            }
            $file = $request->file('file_path');
            $data['file_path'] = $file->store('management-document', 'public');
        } else {
            unset($data['file_path']);
        }

        if ($request->hasFile('resolution_document_path')) {
            if ($managementDocument->resolution_document_path && Storage::disk('public')->exists($managementDocument->resolution_document_path)) {
                Storage::disk('public')->delete($managementDocument->resolution_document_path);
            }
            $resolutionFile = $request->file('resolution_document_path');
            $data['resolution_document_path'] = $resolutionFile->store('management-document/resolutions', 'public');
        } else {
            unset($data['resolution_document_path']);
        }

        $data['is_active'] = $request->boolean('is_active');

        $managementDocument->update($data);

        return redirect()->route('admin.documents.index')
            ->with('success', 'El documento de gestión ha sido actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManagementDocument $managementDocument)
    {
        if ($managementDocument->file_path && Storage::disk('public')->exists($managementDocument->file_path)) {
            Storage::disk('public')->delete($managementDocument->file_path);
        }

        if ($managementDocument->resolution_document_path && Storage::disk('public')->exists($managementDocument->resolution_document_path)) {
            Storage::disk('public')->delete($managementDocument->resolution_document_path);
        }

        $managementDocument->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'El documento de gestión ha sido eliminado correctamente.');
    }

    /**
     * Toggle active status of the specified resource.
     */
    public function toggleStatus(ManagementDocument $managementDocument)
    {
        $managementDocument->update([
            'is_active' => !$managementDocument->is_active
        ]);

        return redirect()->back()
            ->with('success', 'El estado del documento ha sido cambiado exitosamente.');
    }
}
