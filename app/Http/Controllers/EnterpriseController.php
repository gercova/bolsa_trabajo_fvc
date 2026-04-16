<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnterpriseValidate;
use App\Models\Enterprise;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EnterpriseController extends Controller {

    /**
     * Show the form for editing the enterprise information.
     */
    public function edit(): View {
        $enterprise = Enterprise::first();
        
        // Si no existe ningún registro, crear uno vacío
        if (!$enterprise) {
            $enterprise = new Enterprise();
        }
        
        return view('admin.enterprise.edit', compact('enterprise'));
    }

    /**
     * Update the enterprise information.
     */
    public function update(EnterpriseValidate $request) {
        $enterprise = Enterprise::first();
        
        if (!$enterprise) {
            $enterprise = new Enterprise();
        }

        $validated = $request->validated();
        
        // $validated = $request->validate([
        //     'ruc' => 'nullable|string|max:20',
        //     'company_name' => 'required|string|max:255',
        //     'trade_name' => 'nullable|string|max:255',
        //     'legal_representative_dni' => 'nullable|string|max:20',
        //     'legal_representative' => 'nullable|string|max:255',
        //     'address' => 'nullable|string|max:500',
        //     'city' => 'nullable|string|max:100',
        //     'business_sector' => 'nullable|string|max:100',
        //     'phrase' => 'nullable|string|max:500',
        //     'description' => 'nullable|string',
        //     'vision' => 'nullable|string',
        //     'mission' => 'nullable|string',
        //     'phone_number_1' => 'nullable|string|max:20',
        //     'phone_number_2' => 'nullable|string|max:20',
        //     'email' => 'nullable|email|max:255',
        //     'facebook_link' => 'nullable|url|max:255',
        //     'linkedin_link' => 'nullable|url|max:255',
        //     'twitter_link' => 'nullable|url|max:255',
        //     'instagram_link' => 'nullable|url|max:255',
        //     'whatsapp_link' => 'nullable|url|max:255',
        //     'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        //     'favicon_path' => 'nullable|image|mimes:ico,png,jpg,svg|max:1024',
        // ]);
        
        // Manejar carga de logo
        if ($request->hasFile('logo_path')) {
            // Eliminar logo anterior si existe
            if ($enterprise->logo_path && !Str::startsWith($enterprise->logo_path, ['http://', 'https://'])) {
                $oldPath = str_replace('/storage/', '', $enterprise->logo_path);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            $logoPath = $request->file('logo_path')->store('enterprise/logos', 'public');
            $validated['logo_path'] = $logoPath;
        }
        
        // Manejar carga de favicon
        if ($request->hasFile('favicon_path')) {
            // Eliminar favicon anterior si existe
            if ($enterprise->favicon_path && !Str::startsWith($enterprise->favicon_path, ['http://', 'https://'])) {
                $oldPath = str_replace('/storage/', '', $enterprise->favicon_path);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            $faviconPath = $request->file('favicon_path')->store('enterprise/favicons', 'public');
            $validated['favicon_path'] = $faviconPath;
        }
        
        $enterprise->fill($validated);
        $enterprise->save();
        
        return redirect()
            ->route('admin.enterprise.edit')
            ->with('success', 'Información de la empresa actualizada exitosamente.');
    }
}