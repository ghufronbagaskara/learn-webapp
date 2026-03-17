import { Head, usePage } from '@inertiajs/react';
import { Package, Plus } from 'lucide-react';
import { useCallback, useEffect, useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

import { ProductPagination } from '../../visibility/product-pagination';
import { ProductModal } from './product-modal';
import { ProductStats } from './product-stats';
import { ProductTable } from './product-table';
import { ProductTableToolbar } from './product-table-toolbar';
import type { ToastData } from './product-ui';
import { Toast } from './product-ui';
import type { Column, ModalState, Product } from './types';
import { DEFAULT_COLUMNS } from './types';

// ─── Types ────────────────────────────────────────────────────────────────────

interface PageProps extends Record<string, unknown> {
  flash?: {
    success?: string;
    error?: string;
  };
}

// ─── Constants ────────────────────────────────────────────────────────────────

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Products', href: '/products' },
];

// ─── Page ─────────────────────────────────────────────────────────────────────

export default function ProductsIndex() {
  const { flash } = usePage<PageProps>().props;

  // ── Datatable state ──────────────────────────────────────────────────────
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [totalRecords, setTotalRecords] = useState(0);
  const [filteredRecords, setFilteredRecords] = useState(0);
  const [search, setSearchState] = useState('');
  const [pageIndex, setPageIndexState] = useState(0);
  const [pageSize, setPageSizeState] = useState(10);
  const [sortCol, setSortCol] = useState(0);
  const [sortDir, setSortDir] = useState<'asc' | 'desc'>('desc');
  const [draw, setDraw] = useState(1);

  // ── UI state ─────────────────────────────────────────────────────────────
  const [modal, setModal] = useState<ModalState>({ mode: null, product: null });
  const [columns, setColumns] = useState<Column[]>(DEFAULT_COLUMNS);
  const [toast, setToast] = useState<ToastData | null>(null);

  // ── Flash → Toast ─────────────────────────────────────────────────────────
  useEffect(() => {
    if (flash?.success) {
      setTimeout(() => setToast({ message: flash.success || '', type: 'success' }), 0);
    }
    if (flash?.error) {
      setTimeout(() => setToast({ message: flash.error || '', type: 'error' }), 0);
    }
  }, [flash]);

  // ── Fetch ─────────────────────────────────────────────────────────────────
  const fetchData = useCallback(() => {
    setLoading(true);
    const params = new URLSearchParams({
      draw: String(draw),
      start: String(pageIndex * pageSize),
      length: String(pageSize),
      'search[value]': search,
      'order[0][column]': String(sortCol),
      'order[0][dir]': sortDir,
    });

    fetch(`/products/datatable?${params}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
    })
      .then((r) => r.json())
      .then((data) => {
        setProducts(data.data);
        setTotalRecords(data.recordsTotal);
        setFilteredRecords(data.recordsFiltered);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, [draw, pageIndex, pageSize, search, sortCol, sortDir]);

  useEffect(() => {
    setTimeout(() => fetchData(), 0);
  }, [fetchData]);

  // ── Handlers ──────────────────────────────────────────────────────────────
  const handleModalClose = useCallback(() => {
    setModal({ mode: null, product: null });
    setDraw((d) => d + 1);
  }, []);

  const handleSearch = useCallback((val: string) => {
    setSearchState(val);
    setPageIndexState(0);
    setDraw((d) => d + 1);
  }, []);

  const handlePageSize = useCallback((size: number) => {
    setPageSizeState(size);
    setPageIndexState(0);
    setDraw((d) => d + 1);
  }, []);

  const handlePageChange = useCallback((idx: number) => {
    setPageIndexState(idx);
    setDraw((d) => d + 1);
  }, []);

  const handleSort = useCallback(
    (colIdx: number) => {
      if (sortCol === colIdx) {
        setSortDir((d) => (d === 'asc' ? 'desc' : 'asc'));
      } else {
        setSortCol(colIdx);
        setSortDir('asc');
      }
      setPageIndexState(0);
      setDraw((d) => d + 1);
    },
    [sortCol],
  );

  const handleToggleColumn = useCallback((key: string) => {
    setColumns((prev) => prev.map((c) => (c.key === key ? { ...c, visible: !c.visible } : c)));
  }, []);

  // ─────────────────────────────────────────────────────────────────────────

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Products" />

      {toast && <Toast {...toast} onClose={() => setToast(null)} />}
      <ProductModal state={modal} onClose={handleModalClose} />

      <div className="space-y-6 px-6 py-6">
        {/* Header */}
        <div className="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
          <div>
            <div className="mb-1 flex items-center gap-2">
              <div className="rounded-lg bg-indigo-500/15 p-2">
                <Package className="h-5 w-5 text-indigo-400" />
              </div>
              <h1 className="text-2xl font-bold text-white">Products</h1>
            </div>
            <p className="ml-10 text-sm text-white/40">
              Kelola semua data produk Anda dengan DataTables
            </p>
          </div>
          <button
            type="button"
            onClick={() => setModal({ mode: 'create', product: null })}
            className="flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-indigo-500/20 transition-all hover:bg-indigo-500 active:scale-95"
          >
            <Plus className="h-4 w-4" />
            Tambah Produk
          </button>
        </div>

        {/* Stats */}
        <ProductStats totalRecords={totalRecords} products={products} />

        {/* DataTable Card */}
        <div className="overflow-hidden rounded-2xl border border-white/10 bg-white/[0.02]">
          <ProductTableToolbar
            search={search}
            pageSize={pageSize}
            loading={loading}
            columns={columns}
            products={products}
            onSearch={handleSearch}
            onPageSizeChange={handlePageSize}
            onRefresh={() => setDraw((d) => d + 1)}
            onToggleColumn={handleToggleColumn}
          />

          <ProductTable
            products={products}
            loading={loading}
            search={search}
            pageIndex={pageIndex}
            pageSize={pageSize}
            sortCol={sortCol}
            sortDir={sortDir}
            columns={columns}
            onSort={handleSort}
            onEdit={(product: Product) => setModal({ mode: 'edit', product })}
            onDelete={(product: Product) => setModal({ mode: 'delete', product })}
          />

          <ProductPagination
            pageIndex={pageIndex}
            pageSize={pageSize}
            totalRecords={totalRecords}
            filteredRecords={filteredRecords}
            search={search}
            onPageChange={handlePageChange}
          />
        </div>
      </div>
    </AppLayout>
  );
}
