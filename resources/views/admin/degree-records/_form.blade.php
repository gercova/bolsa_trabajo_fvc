{{-- Shared form fields for DegreeRecord create & edit --}}

@php
    $v = fn($field) => old($field, $record?->{$field});
    $err = fn($field) => $errors->first($field);
@endphp

{{-- Section: Institución --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
    <h2 class="text-sm font-extrabold text-gray-700 uppercase tracking-wider border-b border-gray-100 pb-3 flex items-center gap-2">
        <i class="bi bi-building text-purple-500"></i> Datos de la Institución
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Código Modular</label>
            <input type="text" name="modular_code" value="{{ $v('modular_code') }}"
                class="w-full text-sm border {{ $err('modular_code') ? 'border-red-400' : 'border-gray-300' }} rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
            @error('modular_code')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nombre Institución <span class="text-red-500">*</span></label>
            <input type="text" name="institution_name" value="{{ $v('institution_name') }}" required
                class="w-full text-sm border {{ $err('institution_name') ? 'border-red-400' : 'border-gray-300' }} rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
            @error('institution_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Tipo de Gestión</label>
            <input type="text" name="management_type" value="{{ $v('management_type') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Departamento</label>
            <input type="text" name="department" value="{{ $v('department') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nivel Formativo</label>
            <input type="text" name="formative_level" value="{{ $v('formative_level') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Familia Productiva</label>
            <input type="text" name="productive_family" value="{{ $v('productive_family') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
        </div>

        <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Programa de Estudios <span class="text-red-500">*</span></label>
            <input type="text" name="study_program" value="{{ $v('study_program') }}" required
                class="w-full text-sm border {{ $err('study_program') ? 'border-red-400' : 'border-gray-300' }} rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
            @error('study_program')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

    </div>
</div>

{{-- Section: Datos Personales --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
    <h2 class="text-sm font-extrabold text-gray-700 uppercase tracking-wider border-b border-gray-100 pb-3 flex items-center gap-2">
        <i class="bi bi-person-badge text-purple-500"></i> Datos Personales del Egresado
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        <div class="sm:col-span-2 lg:col-span-3">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nombres Completos <span class="text-red-500">*</span></label>
            <input type="text" name="full_names" value="{{ $v('full_names') }}" required
                class="w-full text-sm border {{ $err('full_names') ? 'border-red-400' : 'border-gray-300' }} rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
            @error('full_names')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Tipo de Documento</label>
            <input type="text" name="document_type" value="{{ $v('document_type') }}"
                placeholder="DNI, CE, PAS..."
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Número de Documento</label>
            <input type="text" name="document_number" value="{{ $v('document_number') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-mono">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Sexo</label>
            <select name="gender"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                <option value="">— Seleccionar —</option>
                <option value="MASCULINO" {{ $v('gender') === 'MASCULINO' ? 'selected' : '' }}>Masculino</option>
                <option value="FEMENINO"  {{ $v('gender') === 'FEMENINO'  ? 'selected' : '' }}>Femenino</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Fecha de Nacimiento</label>
            <input type="date" name="birth_date" value="{{ $v('birth_date') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Fecha de Egreso</label>
            <input type="date" name="graduation_date" value="{{ $v('graduation_date') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
        </div>

    </div>
</div>

{{-- Section: Datos del Título --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
    <h2 class="text-sm font-extrabold text-gray-700 uppercase tracking-wider border-b border-gray-100 pb-3 flex items-center gap-2">
        <i class="bi bi-award text-purple-500"></i> Datos del Grado / Título
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">N° Registro Institucional</label>
            <input type="text" name="institutional_registration_number"
                value="{{ $v('institutional_registration_number') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-mono">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Fecha Emisión Diploma</label>
            <input type="date" name="diploma_issue_date" value="{{ $v('diploma_issue_date') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Fecha Registro MINEDU</label>
            <input type="date" name="minedu_registration_date" value="{{ old('minedu_registration_date', $record ? optional($record->minedu_registration_date)->format('Y-m-d') : '') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Código de Título Generado</label>
            <input type="text" name="generated_title_code" value="{{ $v('generated_title_code') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-mono">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Número de Expediente</label>
            <input type="text" name="file_number" value="{{ $v('file_number') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-mono">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Tipo de Registro</label>
            <input type="text" name="registration_type" value="{{ $v('registration_type') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Usuario Especialista</label>
            <input type="text" name="specialist_user" value="{{ $v('specialist_user') }}"
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-mono">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Tipo de Diploma</label>
            <input type="text" name="diploma_type" value="{{ $v('diploma_type') }}"
                placeholder="TÍTULO, GRADO..."
                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
        </div>

    </div>
</div>
