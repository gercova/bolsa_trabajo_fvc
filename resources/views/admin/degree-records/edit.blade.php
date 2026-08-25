@extends('layouts.app')
@section('title', 'Editar Registro de Grado/Título')

@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="dashboardApp()">
        @include('admin.components.aside')
        <div class="flex-1 flex flex-col min-w-0">

            <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    <a href="{{ route('admin.degree-records.index') }}"
                        class="text-gray-400 hover:text-purple-600 transition p-1">
                        <i class="bi bi-arrow-left text-lg"></i>
                    </a>
                    <div>
                        <h1 class="text-lg font-extrabold text-gray-800">Editar Registro #{{ $degreeRecord->id }}</h1>
                        <p class="text-xs text-gray-500 truncate max-w-xs">{{ $degreeRecord->full_names }}</p>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-4xl mx-auto">

                    @if ($errors->any())
                        <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4">
                            <p class="text-sm font-bold text-red-700 mb-2"><i class="bi bi-exclamation-triangle mr-1"></i>Por favor corrige los siguientes errores:</p>
                            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.degree-records.update', $degreeRecord) }}" method="POST"
                        class="space-y-6">
                        @csrf
                        @method('PUT')
                        @include('admin.degree-records._form', ['record' => $degreeRecord])
                        <div class="flex items-center gap-3 justify-end">
                            <a href="{{ route('admin.degree-records.index') }}"
                                class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl text-sm font-extrabold shadow-md transition hover:shadow-lg">
                                <i class="bi bi-save mr-1"></i> Actualizar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
@endsection
