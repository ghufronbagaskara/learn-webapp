import { ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from 'lucide-react';

interface ProductPaginationProps {
    pageIndex: number;
    pageSize: number;
    totalRecords: number;
    filteredRecords: number;
    search: string;
    onPageChange: (idx: number) => void;
}

export function ProductPagination({
    pageIndex,
    pageSize,
    totalRecords,
    filteredRecords,
    search,
    onPageChange,
}: ProductPaginationProps) {
    const totalPages = Math.ceil(filteredRecords / pageSize);
    const startEntry = filteredRecords === 0 ? 0 : pageIndex * pageSize + 1;
    const endEntry = Math.min((pageIndex + 1) * pageSize, filteredRecords);

    const pageNumbers = buildPageNumbers(pageIndex, totalPages);

    return (
        <div className="flex flex-col items-center justify-between gap-3 border-t border-white/10 px-5 py-4 sm:flex-row">
            {/* Info */}
            <p className="text-xs text-white/30">
                {filteredRecords === 0 ? (
                    'Tidak ada data'
                ) : (
                    <>
                        Menampilkan {startEntry}–{endEntry} dari{' '}
                        {filteredRecords.toLocaleString('id-ID')} entri
                        {search && totalRecords !== filteredRecords && (
                            <span className="ml-1">
                                (difilter dari {totalRecords.toLocaleString('id-ID')} total entri)
                            </span>
                        )}
                    </>
                )}
            </p>

            {/* Controls */}
            {totalPages > 1 && (
                <div className="flex items-center gap-1">
                    <PageButton
                        onClick={() => onPageChange(0)}
                        disabled={pageIndex === 0}
                        label="First"
                    >
                        <ChevronsLeft className="h-4 w-4" />
                    </PageButton>
                    <PageButton
                        onClick={() => onPageChange(pageIndex - 1)}
                        disabled={pageIndex === 0}
                        label="Prev"
                    >
                        <ChevronLeft className="h-4 w-4" />
                    </PageButton>

                    {pageNumbers.map((p, i) =>
                        p === '...' ? (
                            <span key={`ellipsis-${i}`} className="px-1 text-xs text-white/20">
                                …
                            </span>
                        ) : (
                            <button
                                key={p}
                                onClick={() => onPageChange(p as number)}
                                className={`h-8 min-w-[32px] rounded-lg text-xs font-medium transition-colors
                                    ${p === pageIndex
                                        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20'
                                        : 'text-white/40 hover:bg-white/10 hover:text-white'
                                    }`}
                            >
                                {(p as number) + 1}
                            </button>
                        ),
                    )}

                    <PageButton
                        onClick={() => onPageChange(pageIndex + 1)}
                        disabled={pageIndex >= totalPages - 1}
                        label="Next"
                    >
                        <ChevronRight className="h-4 w-4" />
                    </PageButton>
                    <PageButton
                        onClick={() => onPageChange(totalPages - 1)}
                        disabled={pageIndex >= totalPages - 1}
                        label="Last"
                    >
                        <ChevronsRight className="h-4 w-4" />
                    </PageButton>
                </div>
            )}
        </div>
    );
}

// ─── Helpers ──────────────────────────────────────────────────────────────────

interface PageButtonProps {
    onClick: () => void;
    disabled: boolean;
    label: string;
    children: React.ReactNode;
}

function PageButton({ onClick, disabled, label, children }: PageButtonProps) {
    return (
        <button
            onClick={onClick}
            disabled={disabled}
            aria-label={label}
            className="rounded-lg p-1.5 text-white/40 transition-colors hover:bg-white/10 hover:text-white disabled:cursor-not-allowed disabled:opacity-20"
        >
            {children}
        </button>
    );
}

function buildPageNumbers(current: number, total: number): (number | '...')[] {
    if (total <= 7) return Array.from({ length: total }, (_, i) => i);

    if (current < 4) {
        return [0, 1, 2, 3, 4, '...', total - 1];
    }
    if (current > total - 5) {
        return [0, '...', total - 5, total - 4, total - 3, total - 2, total - 1];
    }
    return [0, '...', current - 1, current, current + 1, '...', total - 1];
}