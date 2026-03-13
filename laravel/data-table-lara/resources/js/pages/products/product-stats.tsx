import { AlertCircle, CheckCircle, Package, TrendingUp } from 'lucide-react';
import type { Product } from './types';

interface ProductStatsProps {
  totalRecords: number;
  products: Product[]; // current page products for local stats
}

export function ProductStats({ totalRecords, products }: ProductStatsProps) {
  const activeCount = products.filter((p) => p.status === 'active').length;
  const lowStockCount = products.filter((p) => p.stock <= 10 && p.stock > 0).length;
  const outOfStockCount = products.filter((p) => p.stock === 0).length;

  const stats = [
    {
      label: 'Total Produk',
      value: totalRecords,
      icon: Package,
      color: 'indigo',
      sub: 'semua produk',
    },
    {
      label: 'Aktif',
      value: activeCount,
      icon: CheckCircle,
      color: 'emerald',
      sub: 'halaman ini',
    },
    {
      label: 'Stok Hampir Habis',
      value: lowStockCount,
      icon: AlertCircle,
      color: 'amber',
      sub: '≤ 10 unit',
    },
    {
      label: 'Habis',
      value: outOfStockCount,
      icon: TrendingUp,
      color: 'red',
      sub: '0 unit',
    },
  ];

  const colorMap: Record<string, string> = {
    indigo: 'bg-indigo-500/10 border-indigo-500/20 text-indigo-400',
    emerald: 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400',
    amber: 'bg-amber-500/10 border-amber-500/20 text-amber-400',
    red: 'bg-red-500/10 border-red-500/20 text-red-400',
  };

  return (
    <div className="grid grid-cols-2 gap-4 sm:grid-cols-4">
      {stats.map((stat) => {
        const Icon = stat.icon;
        return (
          <div
            key={stat.label}
            className="flex items-center gap-3 rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3"
          >
            <div className={`rounded-lg border p-2 ${colorMap[stat.color]}`}>
              <Icon className="h-4 w-4" />
            </div>
            <div>
              <p className="text-xl font-bold text-white">{stat.value}</p>
              <p className="text-xs text-white/40">{stat.label}</p>
            </div>
          </div>
        );
      })}
    </div>
  );
}
