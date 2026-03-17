import { Eye, EyeOff, FileSpreadsheet, FileText, Filter, RefreshCw, Search, X } from 'lucide-react';
import { useState } from 'react';
import type { Column, Product } from './types';

// ─── Column Visibility Panel ──────────────────────────────────────────────────

interface ColumnVisibilityPanelProps {
  columns: Column[];
  onToggle: (key: string) => void;
  onClose: () => void;
}

function ColumnVisibilityPanel({ columns, onToggle, onClose }: ColumnVisibilityPanelProps) {
  return (
    <div className="absolute right-0 top-full z-40 mt-2 w-56 overflow-hidden rounded-xl border border-white/10 bg-[#0f1117] shadow-2xl shadow-black/50">
      <div className="flex items-center justify-between border-b border-white/10 px-4 py-3">
        <span className="text-xs font-semibold uppercase tracking-wider text-white/60">
          Visibilitas Kolom
        </span>
        <button
          type="button"
          onClick={onClose}
          className="text-white/40 transition-colors hover:text-white"
        >
          <X className="h-3.5 w-3.5" />
        </button>
      </div>
      <div className="p-2">
        {columns
          .filter((c) => c.key !== 'actions')
          .map((col) => (
            <button
              type="button"
              key={col.key}
              onClick={() => onToggle(col.key as string)}
              className="flex w-full items-center justify-between rounded-lg px-3 py-2 transition-colors hover:bg-white/5"
            >
              <span
                className={`text-sm transition-colors ${col.visible ? 'text-white' : 'text-white/30'}`}
              >
                {col.label}
              </span>
              {col.visible ? (
                <Eye className="h-3.5 w-3.5 text-indigo-400" />
              ) : (
                <EyeOff className="h-3.5 w-3.5 text-white/20" />
              )}
            </button>
          ))}
      </div>
    </div>
  );
}

// ─── Toolbar ──────────────────────────────────────────────────────────────────

interface ProductTableToolbarProps {
  search: string;
  pageSize: number;
  loading: boolean;
  columns: Column[];
  products: Product[];
  onSearch: (val: string) => void;
  onPageSizeChange: (size: number) => void;
  onRefresh: () => void;
  onToggleColumn: (key: string) => void;
}

export function ProductTableToolbar({
  search,
  pageSize,
  loading,
  columns,
  products,
  onSearch,
  onPageSizeChange,
  onRefresh,
  onToggleColumn,
}: ProductTableToolbarProps) {
  const [showColumnPanel, setShowColumnPanel] = useState(false);

  const exportCSV = () => {
    const visibleCols = columns.filter((c) => c.visible && c.key !== 'actions' && c.key !== 'no');
    const header = visibleCols.map((c) => c.label).join(',');
    const rows = products.map((p) =>
      visibleCols
        .map((c) => {
          const val = p[c.key as keyof Product];
          return typeof val === 'string' && val.includes(',') ? `"${val}"` : val;
        })
        .join(','),
    );
    const csv = [header, ...rows].join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'products.csv';
    a.click();
    URL.revokeObjectURL(url);
  };

  return (
    <div className="flex flex-col items-start justify-between gap-3 border-b border-white/10 px-5 py-4 sm:flex-row sm:items-center">
      {/* Left: page size + export */}
      <div className="flex flex-wrap items-center gap-2">
        <div className="flex items-center gap-2">
          <span className="text-xs text-white/40">Tampilkan</span>
          <select
            value={pageSize}
            onChange={(e) => onPageSizeChange(Number(e.target.value))}
            className="rounded-lg border border-white/10 bg-white/5 px-2.5 py-1.5 text-sm text-white outline-none focus:ring-1 focus:ring-indigo-500/50"
          >
            {[10, 25, 50, 100].map((n) => (
              <option key={`page-size-${n}`} value={n} className="bg-[#0f1117]">
                {n}
              </option>
            ))}
          </select>
          <span className="text-xs text-white/40">entri</span>
        </div>

        <button
          type="button"
          onClick={exportCSV}
          className="flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-white/60 transition-colors hover:bg-white/10 hover:text-white"
        >
          <FileSpreadsheet className="h-3.5 w-3.5" />
          CSV
        </button>
        <button
          type="button"
          onClick={() => window.print()}
          className="flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-white/60 transition-colors hover:bg-white/10 hover:text-white"
        >
          <FileText className="h-3.5 w-3.5" />
          Print
        </button>
      </div>

      {/* Right: search + column visibility + refresh */}
      <div className="flex w-full items-center gap-2 sm:w-auto">
        {/* Search */}
        <div className="relative flex-1 sm:w-64 sm:flex-none">
          <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-white/30" />
          <input
            type="text"
            value={search}
            onChange={(e) => onSearch(e.target.value)}
            placeholder="Cari produk..."
            className="w-full rounded-lg border border-white/10 bg-white/5 py-2 pl-9 pr-4 text-sm text-white placeholder-white/25 outline-none transition-all focus:border-indigo-500/30 focus:ring-2 focus:ring-indigo-500/30"
          />
          {search && (
            <button
              type="button"
              onClick={() => onSearch('')}
              className="absolute right-2.5 top-1/2 -translate-y-1/2 text-white/30 transition-colors hover:text-white"
            >
              <X className="h-3.5 w-3.5" />
            </button>
          )}
        </div>

        {/* Column Visibility */}
        <div className="relative">
          <button
            type="button"
            onClick={() => setShowColumnPanel((v) => !v)}
            title="Visibilitas kolom"
            className={`rounded-lg border p-2 text-sm transition-colors
                            ${
                              showColumnPanel
                                ? 'border-indigo-500/40 bg-indigo-500/20 text-indigo-300'
                                : 'border-white/10 bg-white/5 text-white/50 hover:bg-white/10 hover:text-white'
                            }`}
          >
            <Filter className="h-4 w-4" />
          </button>
          {showColumnPanel && (
            <ColumnVisibilityPanel
              columns={columns}
              onToggle={onToggleColumn}
              onClose={() => setShowColumnPanel(false)}
            />
          )}
        </div>

        {/* Refresh */}
        <button
          type="button"
          onClick={onRefresh}
          title="Refresh"
          className="rounded-lg border border-white/10 bg-white/5 p-2 text-white/50 transition-colors hover:bg-white/10 hover:text-white"
        >
          <RefreshCw className={`h-4 w-4 ${loading ? 'animate-spin' : ''}`} />
        </button>
      </div>
    </div>
  );
}
