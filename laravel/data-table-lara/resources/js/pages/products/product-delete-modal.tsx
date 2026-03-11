import { router } from '@inertiajs/react';
import { X, Trash2, RefreshCw } from 'lucide-react';
import { useState } from 'react';
import type { Product } from './types';

interface ProductDeleteModalProps {
    product: Product;
    onClose: () => void;
}

export function ProductDeleteModal({ product, onClose }: ProductDeleteModalProps) {
    const [submitting, setSubmitting] = useState(false);

    const handleDelete = () => {
        setSubmitting(true);
        router.delete(`/products/${product.id}`, {
            onSuccess: onClose,
            onFinish: () => setSubmitting(false),
        });
    };

    return (
        <>
            {/* Backdrop */}
            <div className="absolute inset-0 bg-black/70 backdrop-blur-sm" onClick={onClose} />

            {/* Panel */}
            <div className="relative w-full max-w-md overflow-hidden rounded-2xl border border-white/10 bg-[#0f1117] shadow-2xl shadow-black/50">
                {/* Header */}
                <div className="flex items-center justify-between border-b border-white/10 px-6 py-5">
                    <div className="flex items-center gap-3">
                        <div className="rounded-lg bg-red-500/15 p-2">
                            <Trash2 className="h-5 w-5 text-red-400" />
                        </div>
                        <div>
                            <h2 className="text-base font-semibold text-white">Hapus Produk</h2>
                            <p className="text-xs text-white/40">Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                    </div>
                    <button
                        onClick={onClose}
                        className="rounded-lg p-1.5 text-white/40 transition-colors hover:bg-white/10 hover:text-white"
                    >
                        <X className="h-5 w-5" />
                    </button>
                </div>

                {/* Body */}
                <div className="px-6 py-8 text-center">
                    <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-500/10">
                        <Trash2 className="h-8 w-8 text-red-400" />
                    </div>
                    <p className="text-white/80">Apakah Anda yakin ingin menghapus produk ini?</p>
                    <div className="mt-3 inline-block rounded-lg bg-white/5 px-4 py-2">
                        <p className="text-sm font-semibold text-white">
                            {product.name}{' '}
                            <span className="font-normal text-white/40">({product.sku})</span>
                        </p>
                    </div>
                    <p className="mt-3 text-xs text-white/30">
                        Data yang sudah dihapus tidak bisa dikembalikan.
                    </p>
                </div>

                {/* Footer */}
                <div className="flex justify-end gap-3 border-t border-white/10 px-6 py-4">
                    <button
                        onClick={onClose}
                        className="rounded-lg px-4 py-2 text-sm font-medium text-white/60 transition-colors hover:bg-white/10 hover:text-white"
                    >
                        Batal
                    </button>
                    <button
                        onClick={handleDelete}
                        disabled={submitting}
                        className="flex items-center gap-2 rounded-lg bg-red-600 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-red-500 disabled:opacity-50"
                    >
                        {submitting ? (
                            <RefreshCw className="h-4 w-4 animate-spin" />
                        ) : (
                            <Trash2 className="h-4 w-4" />
                        )}
                        Hapus Produk
                    </button>
                </div>
            </div>
        </>
    );
}