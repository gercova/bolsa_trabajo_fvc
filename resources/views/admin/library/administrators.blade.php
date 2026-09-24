@extends('admin.library.layout')

@section('title', 'Administradores - Biblioteca')

@section('library_content')
<div class="p-5 sm:p-7 space-y-6">

    <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <i class="bi bi-people text-teal-800"></i> Administradores de Biblioteca
            </h1>
            <p class="text-xs text-slate-500">Personal con privilegios de gestión de contenidos, publicación y monitoreo de la biblioteca institucional.</p>
        </div>
        <div>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition inline-flex items-center gap-2">
                <i class="bi bi-gear"></i> Gestionar Usuarios
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Administrador</th>
                        <th class="py-3 px-4">Rol</th>
                        <th class="py-3 px-4">Correo Electrónico</th>
                        <th class="py-3 px-4">Teléfono</th>
                        <th class="py-3 px-4 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($admins as $admin)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-900 font-black text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($admin->names, 0, 2)) }}
                                </div>
                                <span class="font-bold text-slate-900">{{ $admin->names }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200">
                                    {{ $admin->role }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-600 font-medium">
                                {{ $admin->email }}
                            </td>
                            <td class="py-3 px-4 text-slate-500">
                                {{ $admin->phone ?? '—' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $admin->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $admin->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
