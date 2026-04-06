@php
    $pivotPayload = json_encode([
        'startDate' => $startDate,
        'endDate' => $endDate,
        'status' => $status,
        'pivotRows' => $pivotRows,
    ]);
@endphp

<div
    id="pivot-report-page"
    wire:init="initLoad"
    class="min-h-screen bg-slate-50 p-6 space-y-6"
    data-payload="{{ $pivotPayload }}"
>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-semibold text-slate-800 tracking-tight text-2xl">Pivot Reporting</h1>
            <p class="text-sm text-slate-600">Interactive pivot analysis for category and month aggregation.</p>
        </div>

        <div class="inline-flex items-center gap-2">
            <a href="{{ route('reports.sales') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                Sales Report
            </a>
            <a href="{{ route('reports.pivot') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">
                Pivot Report
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
        <h2 class="text-base font-semibold text-slate-800 mb-4">Filters</h2>
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex flex-col gap-1" wire:ignore>
                <label for="pivotDateRange" class="text-xs font-medium text-slate-500 uppercase tracking-wide">Date Range</label>
                <input
                    id="pivotDateRange"
                    type="text"
                    placeholder="YYYY-MM-DD to YYYY-MM-DD"
                    class="rounded-lg border border-slate-200 text-sm px-3 py-2 min-w-65 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                />
            </div>

            <div class="flex flex-col gap-1 min-w-45" wire:ignore>
                <label for="pivotStatusFilter" class="text-xs font-medium text-slate-500 uppercase tracking-wide">Status</label>
                <select id="pivotStatusFilter" class="rounded-lg border border-slate-200 text-sm bg-white px-3 py-2">
                    <option value="">All</option>
                    @foreach ($statuses as $statusOption)
                        <option value="{{ $statusOption }}" @selected($statusOption === $status)>
                            {{ str($statusOption)->headline() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button wire:click="resetFilters" type="button" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                Reset
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($summary as $index => $stat)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col gap-1" wire:key="pivot-summary-{{ $index }}">
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
        <h2 class="text-base font-semibold text-slate-800 mb-4">What-If Analysis (Pivot)</h2>
        <div id="pivotOnlyOutput" class="overflow-auto"></div>
    </div>
</div>

@push('scripts')
    <script>
        window.ReportPivotPage?.boot($wire);
    </script>
@endpush
