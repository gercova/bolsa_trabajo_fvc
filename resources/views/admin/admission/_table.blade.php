{{--
    Partial: admin/admission/_table.blade.php
    Variables:
        $items      – LengthAwarePaginator
        $process    – 'cepre' | 'admision'
        $tabLabel   – Display label
        $pageParam  – pagination page param name
--}}
<div class="overflow-x-auto custom-scrollbar">
    <table class="w-full text-left border-collapse min-w-[960px]">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                {{-- Período --}}
                <th class="p-4">
                    <a href="{{ route('admin.exams.index', array_merge(request()->except(['sort_by','sort_order']),
                        ['sort_by'=>'period','sort_order'=>request('sort_by')==='period' && request('sort_order')==='asc' ? 'desc':'asc'])) }}"
                        class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                        Período
                        @if(request('sort_by')==='period')
                            <i class="bi bi-caret-{{ request('sort_order')==='asc' ? 'up' : 'down' }}-fill text-xs"></i>
                        @endif
                    </a>
                </th>
                {{-- Tipo --}}
                <th class="p-4">
                    <a href="{{ route('admin.exams.index', array_merge(request()->except(['sort_by','sort_order']),
                        ['sort_by'=>'type','sort_order'=>request('sort_by')==='type' && request('sort_order')==='asc' ? 'desc':'asc'])) }}"
                        class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                        Tipo
                        @if(request('sort_by')==='type')
                            <i class="bi bi-caret-{{ request('sort_order')==='asc' ? 'up' : 'down' }}-fill text-xs"></i>
                        @endif
                    </a>
                </th>
                {{-- Fecha --}}
                <th class="p-4">
                    <a href="{{ route('admin.exams.index', array_merge(request()->except(['sort_by','sort_order']),
                        ['sort_by'=>'exam_date','sort_order'=>request('sort_by')==='exam_date' && request('sort_order')==='asc' ? 'desc':'asc'])) }}"
                        class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                        Fecha Examen
                        @if(request('sort_by')==='exam_date')
                            <i class="bi bi-caret-{{ request('sort_order')==='asc' ? 'up' : 'down' }}-fill text-xs"></i>
                        @endif
                    </a>
                </th>
                {{-- Vacantes --}}
                <th class="p-4">
                    <a href="{{ route('admin.exams.index', array_merge(request()->except(['sort_by','sort_order']),
                        ['sort_by'=>'total_vacancies','sort_order'=>request('sort_by')==='total_vacancies' && request('sort_order')==='asc' ? 'desc':'asc'])) }}"
                        class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                        Vacantes
                        @if(request('sort_by')==='total_vacancies')
                            <i class="bi bi-caret-{{ request('sort_order')==='asc' ? 'up' : 'down' }}-fill text-xs"></i>
                        @endif
                    </a>
                </th>
                {{-- Precio --}}
                <th class="p-4">
                    <a href="{{ route('admin.exams.index', array_merge(request()->except(['sort_by','sort_order']),
                        ['sort_by'=>'price','sort_order'=>request('sort_by')==='price' && request('sort_order')==='asc' ? 'desc':'asc'])) }}"
                        class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                        Precio (S/)
                        @if(request('sort_by')==='price')
                            <i class="bi bi-caret-{{ request('sort_order')==='asc' ? 'up' : 'down' }}-fill text-xs"></i>
                        @endif
                    </a>
                </th>
                {{-- Estado --}}
                <th class="p-4">
                    <a href="{{ route('admin.exams.index', array_merge(request()->except(['sort_by','sort_order']),
                        ['sort_by'=>'is_active','sort_order'=>request('sort_by')==='is_active' && request('sort_order')==='asc' ? 'desc':'asc'])) }}"
                        class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                        Estado
                        @if(request('sort_by')==='is_active')
                            <i class="bi bi-caret-{{ request('sort_order')==='asc' ? 'up' : 'down' }}-fill text-xs"></i>
                        @endif
                    </a>
                </th>
                <th class="p-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($items as $item)
                <tr class="hover:bg-purple-50/30 transition-colors group">
                    <td class="p-4">
                        <div class="font-semibold text-gray-900">{{ $item->period }}</div>
                        <div class="text-xs text-gray-500 font-normal truncate max-w-[200px]" title="{{ $item->activity }}">{{ $item->activity }}</div>
                        <div class="flex items-center gap-1.5 mt-1">
                            @if ($item->url_pdf)
                                <a href="{{ Storage::url($item->url_pdf) }}" target="_blank"
                                    class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition"
                                    title="Ver Bases / Prospecto PDF">
                                    <i class="bi bi-file-earmark-pdf-fill text-red-500"></i> Bases
                                </a>
                            @endif
                            @if ($item->results_url_pdf)
                                <a href="{{ Storage::url($item->results_url_pdf) }}" target="_blank"
                                    class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition"
                                    title="Ver Resultados Publicados PDF">
                                    <i class="bi bi-award-fill text-emerald-600"></i> Resultados
                                </a>
                            @endif
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                            {{ $item->type === 'ordinario' ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800' }}">
                            <i class="bi bi-{{ $item->type === 'ordinario' ? 'journal-text' : 'star-fill' }} mr-1"></i>
                            {{ ucfirst($item->type) }}
                        </span>
                    </td>
                    <td class="p-4 text-gray-700 text-sm">
                        {{ $item->exam_date ? \Carbon\Carbon::parse($item->exam_date)->format('d/m/Y') : '—' }}
                    </td>
                    <td class="p-4 text-gray-700 text-sm font-medium">{{ $item->total_vacancies }}</td>
                    <td class="p-4 text-gray-900 font-semibold text-sm">
                        <div>S/ {{ number_format($item->price, 2) }}</div>
                        @if ($item->monthly_fee > 0 || $item->tuition_fee > 0)
                            <div class="text-[10px] text-gray-500 font-normal">
                                @if($item->tuition_fee > 0) Mat: S/ {{ number_format($item->tuition_fee, 2) }} @endif
                                @if($item->monthly_fee > 0) · Mens: S/ {{ number_format($item->monthly_fee, 2) }} @endif
                            </div>
                        @endif
                    </td>
                    <td class="p-4">
                        @if ($item->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-800 border border-green-200">
                                <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                Activo
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-800 border border-red-200">
                                <span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>
                                Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <div class="relative inline-block" x-data="{ open: false, posTop: 0, posLeft: 0 }">
                            <button x-ref="trigger" type="button"
                                @click="open = !open; if(open) { const r = $refs.trigger.getBoundingClientRect(); posTop = r.top + r.height + 6; posLeft = r.left + r.width - 224; }"
                                @scroll.window="open = false"
                                class="inline-flex items-center justify-center p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 opacity-0 group-hover:opacity-100"
                                :aria-expanded="open" aria-haspopup="true">
                                <i class="bi bi-three-dots-vertical text-lg"></i>
                            </button>

                            <template x-teleport="body">
                                <div x-show="open" @click.outside="open = false"
                                    @keydown.escape.window="open = false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    :style="`top: ${posTop}px; left: ${posLeft}px`"
                                    class="fixed z-[100] w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-1 ring-1 ring-black/5"
                                    role="menu" x-cloak>
                                    <div class="py-1">
                                        <a href="{{ route('admin.exams.edit', $item) }}"
                                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 flex items-center transition-colors"
                                            role="menuitem">
                                            <i class="bi bi-pencil-square mr-2.5 text-purple-500"></i> Actualizar Datos
                                        </a>

                                        @if ($item->url_pdf)
                                            <a href="{{ Storage::url($item->url_pdf) }}" target="_blank"
                                                class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center transition-colors"
                                                role="menuitem">
                                                <i class="bi bi-file-earmark-pdf mr-2.5 text-blue-500"></i> Ver Bases (PDF)
                                            </a>
                                        @endif

                                        @if ($item->results_url_pdf)
                                            <a href="{{ Storage::url($item->results_url_pdf) }}" target="_blank"
                                                class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 flex items-center transition-colors"
                                                role="menuitem">
                                                <i class="bi bi-award mr-2.5 text-emerald-500"></i> Ver Resultados (PDF)
                                            </a>
                                        @endif

                                        <div class="my-1 border-t border-gray-100"></div>

                                        <form action="{{ route('admin.exams.toggle-status', $item) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 flex items-center transition-colors"
                                                role="menuitem">
                                                <i class="bi {{ $item->is_active ? 'bi-dash-circle text-red-500' : 'bi-check-circle text-green-500' }} mr-2.5"></i>
                                                {{ $item->is_active ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>

                                        <div class="my-1 border-t border-gray-100"></div>

                                        <form action="{{ route('admin.exams.destroy', $item) }}" method="POST" class="m-0"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar este evento? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 flex items-center transition-colors"
                                                role="menuitem">
                                                <i class="bi bi-trash mr-2.5"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-14 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center">
                                <i class="bi bi-inbox text-3xl text-gray-300"></i>
                            </div>
                            <p class="font-semibold text-gray-600">Sin registros de {{ $tabLabel }}</p>
                            <p class="text-sm text-gray-400">
                                @if (request()->hasAny(['search', 'type', 'status', 'date']))
                                    Prueba ajustando los filtros de búsqueda.
                                @else
                                    Crea el primer proceso de {{ $tabLabel }}.
                                @endif
                            </p>
                            @if (request()->hasAny(['search', 'type', 'status', 'date']))
                                <a href="{{ route('admin.exams.index') }}"
                                    class="text-sm font-semibold text-purple-600 hover:underline">
                                    Limpiar filtros
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ===== CUSTOM PAGINATOR ===== --}}
@if ($items->hasPages())
    @php
        $currentPage = $items->currentPage();
        $lastPage    = $items->lastPage();
        $total       = $items->total();
        $from        = $items->firstItem();
        $to          = $items->lastItem();

        // Build page window
        $window  = 2;
        $pages   = [];
        $prevEllipsis = false;
        $nextEllipsis = false;

        for ($p = 1; $p <= $lastPage; $p++) {
            if ($p === 1 || $p === $lastPage || ($p >= $currentPage - $window && $p <= $currentPage + $window)) {
                $pages[] = $p;
            } elseif ($p === $currentPage - $window - 1) {
                $prevEllipsis = true;
                $pages[] = '…';
            } elseif ($p === $currentPage + $window + 1) {
                $nextEllipsis = true;
                $pages[] = '…';
            }
        }
    @endphp

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-gray-100 bg-gray-50/50">
        <p class="text-sm text-gray-500">
            Mostrando <span class="font-bold text-gray-800">{{ $from }}</span>–<span class="font-bold text-gray-800">{{ $to }}</span>
            de <span class="font-bold text-gray-800">{{ $total }}</span> registros
            &nbsp;·&nbsp; Página <span class="font-bold text-purple-700">{{ $currentPage }}</span> de <span class="font-bold text-gray-700">{{ $lastPage }}</span>
        </p>

        <nav aria-label="Paginación {{ $tabLabel }}" class="flex items-center gap-1.5">
            {{-- Previous --}}
            @if ($items->onFirstPage())
                <span class="paginator-btn" aria-disabled="true" tabindex="-1" disabled>
                    <i class="bi bi-chevron-left text-xs"></i>
                </span>
            @else
                <a href="{{ $items->appends(request()->except($pageParam))->previousPageUrl() }}"
                    class="paginator-btn" aria-label="Página anterior">
                    <i class="bi bi-chevron-left text-xs"></i>
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($pages as $page)
                @if ($page === '…')
                    <span class="paginator-btn ellipsis">…</span>
                @elseif ($page === $currentPage)
                    <span class="paginator-btn active" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $items->appends(request()->except($pageParam))->url($page) }}"
                        class="paginator-btn">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($items->hasMorePages())
                <a href="{{ $items->appends(request()->except($pageParam))->nextPageUrl() }}"
                    class="paginator-btn" aria-label="Página siguiente">
                    <i class="bi bi-chevron-right text-xs"></i>
                </a>
            @else
                <span class="paginator-btn" aria-disabled="true" tabindex="-1" disabled>
                    <i class="bi bi-chevron-right text-xs"></i>
                </span>
            @endif
        </nav>
    </div>
@endif
