@php
    $salesPayload = json_encode([
        'startDate' => $draftStartDate,
        'endDate' => $draftEndDate,
        'status' => $draftStatus,
        'chartLabels' => $chartLabels,
        'chartData' => $chartData,
        'pivotRows' => $pivotRows,
    ]);
@endphp

<div
    id="sales-report-page"
    wire:init="initLoad"
    class="min-h-screen bg-slate-50 p-6 space-y-6"
    data-payload="{{ $salesPayload }}"
>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-semibold text-slate-800 tracking-tight text-2xl">Sales Reporting</h1>
            <p class="text-sm text-slate-600">Executive summary, trend chart, and pivot-based what-if analysis.</p>
        </div>

        <div class="inline-flex items-center gap-2">
            <a href="{{ route('reports.sales') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">
                Sales Report
            </a>
            <a href="{{ route('reports.pivot') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                Pivot Report
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
        <h2 class="text-base font-semibold text-slate-800 mb-4">Filters</h2>
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex flex-col gap-1" wire:ignore>
                <label for="dateRange" class="text-xs font-medium text-slate-500 uppercase tracking-wide">Date Range</label>
                <input
                    id="dateRange"
                    type="text"
                    placeholder="YYYY-MM-DD to YYYY-MM-DD"
                    class="rounded-lg border border-slate-200 text-sm px-3 py-2 min-w-65 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                />
            </div>

            <div class="flex flex-col gap-1 min-w-45">
                <label for="statusFilter" class="text-xs font-medium text-slate-500 uppercase tracking-wide">Status</label>
                <select id="statusFilter" wire:model="draftStatus" class="rounded-lg border border-slate-200 text-sm bg-white px-3 py-2">
                    <option value="">All</option>
                    @foreach ($statuses as $statusOption)
                        <option value="{{ $statusOption }}">
                            {{ str($statusOption)->headline() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button wire:click="applyFilters" type="button" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                Apply
            </button>
            <button wire:click="resetFilters" type="button" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                Reset
            </button>
            <button wire:click="exportCsv" type="button" class="inline-flex items-center gap-2 rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100 transition-colors">
                Export CSV
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($summary as $index => $stat)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col gap-1" wire:key="summary-{{ $index }}">
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                    {{ $stat['label'] }}
                </span>
                <span class="font-mono text-2xl font-bold text-slate-900">
                    {{ $stat['value'] }}
                </span>
                @if (isset($stat['trend']) && ! is_null($stat['trend']))
                    <span @class([
                        'text-xs font-medium px-2 py-0.5 rounded-full w-fit',
                        'bg-emerald-50 text-emerald-600' => $stat['trend'] > 0,
                        'bg-red-50 text-red-500' => $stat['trend'] < 0,
                        'bg-slate-100 text-slate-600' => $stat['trend'] === 0.0,
                    ])>
                        {{ $stat['trend'] >= 0 ? '▲' : '▼' }} {{ abs($stat['trend']) }}%
                    </span>
                @endif
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-800 mb-4">Revenue Over Time</h2>
        <canvas id="revenueChart" height="100" aria-label="Revenue chart" role="img"></canvas>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
        <h2 class="text-base font-semibold text-slate-800 mb-4">Sales Table</h2>

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-sm text-left text-slate-600" aria-label="Sales report table">
                <caption class="sr-only">Sales report records with sortable columns.</caption>
                <thead class="bg-slate-50 text-xs font-medium text-slate-500 uppercase tracking-wide border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 cursor-pointer select-none group" wire:click="sortBy('order_number')">
                            <div class="flex items-center gap-1">
                                Order Number
                                <span class="w-3.5 text-slate-400 group-hover:text-slate-600 transition-colors">{!! $sortField === 'order_number' ? ($sortDir === 'asc' ? '&uarr;' : '&darr;') : '&harr;' !!}</span>
                            </div>
                        </th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3 cursor-pointer select-none group" wire:click="sortBy('status')">
                            <div class="flex items-center gap-1">
                                Status
                                <span class="w-3.5 text-slate-400 group-hover:text-slate-600 transition-colors">{!! $sortField === 'status' ? ($sortDir === 'asc' ? '&uarr;' : '&darr;') : '&harr;' !!}</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 cursor-pointer select-none group" wire:click="sortBy('category')">
                            <div class="flex items-center gap-1">
                                Category
                                <span class="w-3.5 text-slate-400 group-hover:text-slate-600 transition-colors">{!! $sortField === 'category' ? ($sortDir === 'asc' ? '&uarr;' : '&darr;') : '&harr;' !!}</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 cursor-pointer select-none group" wire:click="sortBy('total')">
                            <div class="flex items-center gap-1">
                                Total
                                <span class="w-3.5 text-slate-400 group-hover:text-slate-600 transition-colors">{!! $sortField === 'total' ? ($sortDir === 'asc' ? '&uarr;' : '&darr;') : '&harr;' !!}</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 cursor-pointer select-none group" wire:click="sortBy('created_at')">
                            <div class="flex items-center gap-1">
                                Created At
                                <span class="w-3.5 text-slate-400 group-hover:text-slate-600 transition-colors">{!! $sortField === 'created_at' ? ($sortDir === 'asc' ? '&uarr;' : '&darr;') : '&harr;' !!}</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($rows as $row)
                        <tr class="hover:bg-slate-50 transition-colors" wire:key="row-{{ $row->id }}">
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $row->order_number }}</td>
                            <td class="px-4 py-3">{{ $row->user?->name ?? 'Guest' }}</td>
                            <td class="px-4 py-3">{{ str($row->status)->headline() }}</td>
                            <td class="px-4 py-3">{{ str($row->category)->headline() }}</td>
                            <td class="px-4 py-3 font-medium text-slate-800">Rp {{ number_format((float) $row->total, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">No sales data found for current filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $rows->links() }}
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-800 mb-4">What-If Analysis (Pivot)</h2>
        <div id="pivotOutput" class="overflow-auto"></div>
    </div>
</div>

@push('scripts')
    <script>
        window.ReportSalesPage?.boot($wire);
    </script>
@endpush
