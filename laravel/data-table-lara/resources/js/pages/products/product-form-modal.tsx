import { router } from '@inertiajs/react';
import { X, Pencil, Plus, RefreshCw } from 'lucide-react';
import { useState } from 'react';
import type { Product, ProductFormData, ModalMode } from './types';
import { PRODUCT_CATEGORIES, STATUS_OPTIONS, INITIAL_FORM } from './types';

interface ProductFormModalProps {
    mode: Exclude<ModalMode, 'delete' | null>;
    product: Product | null;
    onClose: () => void;
}

export function ProductFormModal({ mode, product, onClose }: ProductFormModalProps) {
    const [form, setForm] = useState<ProductFormData>(() => {
        if (mode === 'edit' && product) {
            return {
                name: product.name,
                sku: product.sku,
                description: product.description || '',
                category: product.category,
                price: String(product.price),
                stock: String(product.stock),
                status: product.status,
            };
        }
        return INITIAL_FORM;
    });
    const [errors, setErrors] = useState<Partial<ProductFormData>>({});
    const [submitting, setSubmitting] = useState(false);

    const validate = (): boolean => {
        const newErrors: Partial<ProductFormData> = {};
        if (!form.name.trim()) newErrors.name = 'Nama produk wajib diisi';
        if (!form.sku.trim()) newErrors.sku = 'SKU wajib diisi';
        if (!form.category) newErrors.category = 'Kategori wajib dipilih';
        if (!form.price || isNaN(Number(form.price)) || Number(form.price) < 0)
            newErrors.price = 'Harga harus berupa angka positif';
        if (!form.stock || isNaN(Number(form.stock)) || Number(form.stock) < 0)
            newErrors.stock = 'Stok harus berupa angka positif';
        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleSubmit = () => {
        if (!validate()) return;
        setSubmitting(true);

        const data = { ...form, price: Number(form.price), stock: Number(form.stock) };

        if (mode === 'create') {
            router.post('/products', data, {
                onSuccess: onClose,
                onError: (e) => {
                    setErrors(e as Partial<ProductFormData>);
                    setSubmitting(false);
                },
                onFinish: () => setSubmitting(false),
            });
        } else if (mode === 'edit' && product) {
            router.put(`/products/${product.id}`, data, {
                onSuccess: onClose,
                onError: (e) => {
                    setErrors(e as Partial<ProductFormData>);
                    setSubmitting(false);
                },
                onFinish: () => setSubmitting(false),
            });
        }
    };

    const isEdit = mode === 'edit';

    return (
        <>
            {/* Backdrop */}
            <div className="absolute inset-0 bg-black/70 backdrop-blur-sm" onClick={onClose} />

            {/* Panel */}
            <div className="relative w-full max-w-xl overflow-hidden rounded-2xl border border-white/10 bg-[#0f1117] shadow-2xl shadow-black/50">
                {/* Header */}
                <div className="flex items-center justify-between border-b border-white/10 px-6 py-5">
                    <div className="flex items-center gap-3">
                        <div className="rounded-lg bg-indigo-500/15 p-2">
                            {isEdit ? (
                                <Pencil className="h-5 w-5 text-indigo-400" />
                            ) : (
                                <Plus className="h-5 w-5 text-indigo-400" />
                            )}
                        </div>
                        <div>
                            <h2 className="text-base font-semibold text-white">
                                {isEdit ? 'Edit Produk' : 'Tambah Produk Baru'}
                            </h2>
                            <p className="text-xs text-white/40">Lengkapi semua field yang diperlukan</p>
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
                <div className="max-h-[65vh] overflow-y-auto px-6 py-5">
                    <div className="space-y-4">
                        {/* Name */}
                        <Field label="Nama Produk" required error={errors.name}>
                            <input
                                type="text"
                                value={form.name}
                                onChange={(e) => setForm((p) => ({ ...p, name: e.target.value }))}
                                placeholder="Contoh: iPhone 15 Pro Max"
                                className={inputCls(!!errors.name)}
                            />
                        </Field>

                        {/* SKU + Category */}
                        <div className="grid grid-cols-2 gap-3">
                            <Field label="SKU" required error={errors.sku}>
                                <input
                                    type="text"
                                    value={form.sku}
                                    onChange={(e) => setForm((p) => ({ ...p, sku: e.target.value }))}
                                    placeholder="PRD-0001"
                                    className={inputCls(!!errors.sku)}
                                />
                            </Field>
                            <Field label="Kategori" required error={errors.category}>
                                <select
                                    value={form.category}
                                    onChange={(e) => setForm((p) => ({ ...p, category: e.target.value }))}
                                    className={inputCls(!!errors.category)}
                                >
                                    <option value="" className="bg-[#0f1117]">Pilih kategori</option>
                                    {PRODUCT_CATEGORIES.map((c) => (
                                        <option key={c} value={c} className="bg-[#0f1117]">
                                            {c}
                                        </option>
                                    ))}
                                </select>
                            </Field>
                        </div>

                        {/* Price + Stock */}
                        <div className="grid grid-cols-2 gap-3">
                            <Field label="Harga (Rp)" required error={errors.price}>
                                <input
                                    type="number"
                                    value={form.price}
                                    onChange={(e) => setForm((p) => ({ ...p, price: e.target.value }))}
                                    placeholder="0"
                                    min="0"
                                    className={inputCls(!!errors.price)}
                                />
                            </Field>
                            <Field label="Stok" required error={errors.stock}>
                                <input
                                    type="number"
                                    value={form.stock}
                                    onChange={(e) => setForm((p) => ({ ...p, stock: e.target.value }))}
                                    placeholder="0"
                                    min="0"
                                    className={inputCls(!!errors.stock)}
                                />
                            </Field>
                        </div>

                        {/* Status toggle */}
                        <div>
                            <label className="mb-1.5 block text-xs font-medium text-white/60">Status</label>
                            <div className="flex gap-2">
                                {STATUS_OPTIONS.map((s) => (
                                    <button
                                        key={s}
                                        type="button"
                                        onClick={() => setForm((p) => ({ ...p, status: s }))}
                                        className={`flex-1 rounded-lg border py-2 text-xs font-medium capitalize transition-all
                                            ${form.status === s
                                                ? s === 'active'
                                                    ? 'border-emerald-500/50 bg-emerald-500/20 text-emerald-300'
                                                    : s === 'inactive'
                                                    ? 'border-red-500/50 bg-red-500/20 text-red-300'
                                                    : 'border-amber-500/50 bg-amber-500/20 text-amber-300'
                                                : 'border-white/10 bg-white/5 text-white/40 hover:bg-white/10'
                                            }`}
                                    >
                                        {s}
                                    </button>
                                ))}
                            </div>
                        </div>

                        {/* Description */}
                        <Field label="Deskripsi">
                            <textarea
                                value={form.description}
                                onChange={(e) => setForm((p) => ({ ...p, description: e.target.value }))}
                                placeholder="Deskripsi produk (opsional)..."
                                rows={3}
                                className="w-full resize-none rounded-lg border border-white/10 bg-white/5 px-3.5 py-2.5 text-sm text-white placeholder-white/25 outline-none transition-all focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50"
                            />
                        </Field>
                    </div>
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
                        onClick={handleSubmit}
                        disabled={submitting}
                        className="flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-500 disabled:opacity-50"
                    >
                        {submitting ? (
                            <RefreshCw className="h-4 w-4 animate-spin" />
                        ) : isEdit ? (
                            <Pencil className="h-4 w-4" />
                        ) : (
                            <Plus className="h-4 w-4" />
                        )}
                        {isEdit ? 'Update Produk' : 'Simpan Produk'}
                    </button>
                </div>
            </div>
        </>
    );
}

// ─── Helpers ──────────────────────────────────────────────────────────────────

function inputCls(hasError: boolean) {
    return `w-full rounded-lg border bg-white/5 px-3.5 py-2.5 text-sm text-white placeholder-white/25 outline-none transition-all
        focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50
        ${hasError ? 'border-red-500/50' : 'border-white/10'}`;
}

interface FieldProps {
    label: string;
    required?: boolean;
    error?: string;
    children: React.ReactNode;
}

function Field({ label, required, error, children }: FieldProps) {
    return (
        <div>
            <label className="mb-1.5 block text-xs font-medium text-white/60">
                {label} {required && <span className="text-red-400">*</span>}
            </label>
            {children}
            {error && <p className="mt-1 text-xs text-red-400">{error}</p>}
        </div>
    );
}