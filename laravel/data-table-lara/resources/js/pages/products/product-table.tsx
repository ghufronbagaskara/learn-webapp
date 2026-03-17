import { MoreHorizontal, Pencil, Trash2 } from 'lucide-react';
import { StatusBadge, StockBadge } from './product-ui';
import type { Column, Product } from './types';

interface ProductTableProps {
  products: Product[];
  loading: boolean;
  search: string;
  pageIndex: number;
  pageSize: number;
  sortCol: number;
  sortDir: 'asc' | 'desc';
  columns: Column[];
  onSort: (idx: number) => void;
  onEdit: (p: Product) => void;
  onDelete: (p: Product) => void;
}

export function ProductTable({
  products,
  loading,
  sortCol,
  sortDir,
  columns,
  onSort,
  onEdit,
  onDelete,
}: ProductTableProps) {
  const visibleCols = columns.filter((c) => c.visible);

  return (
    <div className="relative overflow-x-auto">
      <table className="w-full text-left text-sm text-white">
        <thead className="border-b border-white/10 bg-white/5 text-[10px] font-bold uppercase tracking-widest text-white/40">
          <tr>
            {visibleCols.map((col) => {
              const isSorted = columns.findIndex((c) => c.key === col.key) === sortCol;
              return (
                <th
                  key={col.key}
                  style={{ width: col.width }}
                  className={`px-5 py-4 font-bold ${col.sortable ? 'cursor-pointer hover:bg-white/5 hover:text-white' : ''}`}
                  onClick={() =>
                    col.sortable && onSort(columns.findIndex((c) => c.key === col.key))
                  }
                >
                  <div className="flex items-center gap-2">
                    {col.label}
                    {col.sortable && isSorted && (
                      <span className="text-indigo-400">{sortDir === 'asc' ? '↑' : '↓'}</span>
                    )}
                  </div>
                </th>
              );
            })}
          </tr>
        </thead>
        <tbody className="divide-y divide-white/[0.05]">
          {loading ? (
            Array.from({ length: 5 }).map((_, i) => (
              <tr key={`skeleton-${i}`} className="animate-pulse">
                {visibleCols.map((col) => (
                  <td key={col.key} className="px-5 py-4">
                    <div className="h-4 w-full rounded bg-white/5" />
                  </td>
                ))}
              </tr>
            ))
          ) : products.length === 0 ? (
            <tr>
              <td colSpan={visibleCols.length} className="px-5 py-12 text-center text-white/30">
                Tidak ada data yang ditemukan.
              </td>
            </tr>
          ) : (
            products.map((product) => (
              <tr key={product.id} className="group transition-colors hover:bg-white/[0.03]">
                {visibleCols.map((col) => (
                  <td key={col.key} className="px-5 py-3.5 align-middle">
                    {renderCell(product, col, onEdit, onDelete)}
                  </td>
                ))}
              </tr>
            ))
          )}
        </tbody>
      </table>
    </div>
  );
}

function renderCell(
  product: Product,
  col: Column,
  onEdit: (p: Product) => void,
  onDelete: (p: Product) => void,
) {
  switch (col.key) {
    case 'no':
      return <span className="font-medium text-white/40">{product.no}</span>;
    case 'name':
      return (
        <div className="flex flex-col">
          <span className="font-semibold text-white group-hover:text-indigo-300 transition-colors">
            {product.name}
          </span>
          <span className="text-[10px] text-white/30">{product.sku}</span>
        </div>
      );
    case 'sku':
      return (
        <code className="rounded bg-white/5 px-1.5 py-0.5 text-xs text-indigo-300/80">
          {product.sku}
        </code>
      );
    case 'category':
      return <span className="text-slate-400">{product.category}</span>;
    case 'formatted_price':
      return (
        <span className="font-mono font-medium text-emerald-400">{product.formatted_price}</span>
      );
    case 'stock':
      return <StockBadge stock={product.stock} />;
    case 'status':
      return <StatusBadge status={product.status} />;
    case 'created_at':
      return <span className="text-white/40">{product.created_at}</span>;
    case 'actions':
      return (
        <div className="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
          <button
            type="button"
            onClick={() => onEdit(product)}
            className="rounded-lg p-2 text-white/40 hover:bg-indigo-500/20 hover:text-indigo-300 transition-all"
          >
            <Pencil className="h-4 w-4" />
          </button>
          <button
            type="button"
            onClick={() => onDelete(product)}
            className="rounded-lg p-2 text-white/40 hover:bg-red-500/20 hover:text-red-400 transition-all"
          >
            <Trash2 className="h-4 w-4" />
          </button>
          <button
            type="button"
            className="rounded-lg p-2 text-white/40 hover:bg-white/10 hover:text-white transition-all"
          >
            <MoreHorizontal className="h-4 w-4" />
          </button>
        </div>
      );
    default:
      return <span>{String(product[col.key as keyof Product])}</span>;
  }
}
