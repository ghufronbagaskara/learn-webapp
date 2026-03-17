import { AlertCircle, CheckCircle, X } from 'lucide-react';
import { useEffect } from 'react';
import type { Product } from './types';

// ─── Status Badge ─────────────────────────────────────────────────────────────

interface StatusBadgeProps {
  status: Product['status'];
}

export function StatusBadge({ status }: StatusBadgeProps) {
  const config = {
    active: {
      bg: 'bg-emerald-500/15 text-emerald-400 ring-emerald-500/30',
      dot: 'bg-emerald-400',
      label: 'Active',
    },
    inactive: {
      bg: 'bg-red-500/15 text-red-400 ring-red-500/30',
      dot: 'bg-red-400',
      label: 'Inactive',
    },
    draft: {
      bg: 'bg-amber-500/15 text-amber-400 ring-amber-500/30',
      dot: 'bg-amber-400',
      label: 'Draft',
    },
  }[status];

  return (
    <span
      className={`inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset ${config.bg}`}
    >
      <span className={`h-1.5 w-1.5 rounded-full ${config.dot}`} />
      {config.label}
    </span>
  );
}

// ─── Stock Badge ──────────────────────────────────────────────────────────────

interface StockBadgeProps {
  stock: number;
}

export function StockBadge({ stock }: StockBadgeProps) {
  if (stock === 0) {
    return <span className="text-sm font-semibold text-red-400">Habis</span>;
  }
  if (stock <= 10) {
    return (
      <span className="text-sm font-semibold text-amber-400">
        {stock} <span className="text-xs font-normal opacity-70">(Low)</span>
      </span>
    );
  }
  return <span className="text-sm text-slate-200">{stock.toLocaleString('id-ID')}</span>;
}

// ─── Toast ────────────────────────────────────────────────────────────────────

export interface ToastData {
  message: string;
  type: 'success' | 'error';
}

interface ToastProps extends ToastData {
  onClose: () => void;
}

export function Toast({ message, type, onClose }: ToastProps) {
  useEffect(() => {
    const timer = setTimeout(onClose, 4000);
    return () => clearTimeout(timer);
  }, [onClose]);

  return (
    <div
      className={`fixed bottom-6 right-6 z-[100] flex items-center gap-3 rounded-xl px-5 py-4 shadow-2xl transition-all
                ${type === 'success' ? 'bg-emerald-900/95 ring-1 ring-emerald-500/40' : 'bg-red-900/95 ring-1 ring-red-500/40'}`}
    >
      {type === 'success' ? (
        <CheckCircle className="h-5 w-5 shrink-0 text-emerald-400" />
      ) : (
        <AlertCircle className="h-5 w-5 shrink-0 text-red-400" />
      )}
      <p className="text-sm font-medium text-white">{message}</p>
      <button
        type="button"
        onClick={onClose}
        className="ml-2 text-white/50 transition-colors hover:text-white"
      >
        <X className="h-4 w-4" />
      </button>
    </div>
  );
}
