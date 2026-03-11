import { useState, useCallback, useRef } from 'react';
import type { Product, DatatableResponse } from '../pages/products/types';

interface UseDatatableOptions {
    url: string;
}

interface DatatableState {
    products: Product[];
    loading: boolean;
    totalRecords: number;
    filteredRecords: number;
    search: string;
    pageIndex: number;
    pageSize: number;
    sortCol: number;
    sortDir: 'asc' | 'desc';
}

interface DatatableActions {
    refresh: () => void;
    setSearch: (val: string) => void;
    setPageIndex: (idx: number) => void;
    setPageSize: (size: number) => void;
    setSort: (colIdx: number) => void;
}

export function useProductDatatable({ url }: UseDatatableOptions): [DatatableState, DatatableActions] {
    const [products, setProducts] = useState<Product[]>([]);
    const [loading, setLoading] = useState(true);
    const [totalRecords, setTotalRecords] = useState(0);
    const [filteredRecords, setFilteredRecords] = useState(0);
    const [search, setSearchState] = useState('');
    const [pageIndex, setPageIndexState] = useState(0);
    const [pageSize, setPageSizeState] = useState(10);
    const [sortCol, setSortCol] = useState(0); // server column index
    const [sortDir, setSortDir] = useState<'asc' | 'desc'>('desc');
    const [draw, setDraw] = useState(1);

    const searchTimeout = useRef<ReturnType<typeof setTimeout> | null>(null);

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

        fetch(`${url}?${params}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
        })
            .then((r) => r.json())
            .then((data: DatatableResponse) => {
                setProducts(data.data);
                setTotalRecords(data.recordsTotal);
                setFilteredRecords(data.recordsFiltered);
                setLoading(false);
            })
            .catch(() => setLoading(false));
    }, [url, draw, pageIndex, pageSize, search, sortCol, sortDir]);

    // We expose refresh as a way to trigger re-fetch externally
    const refresh = useCallback(() => {
        setDraw((d) => d + 1);
        // This will trigger fetchData through the draw dependency
        fetchData();
    }, [fetchData]);

    const setSearch = useCallback(
        (val: string) => {
            setSearchState(val);
            setPageIndexState(0);
            if (searchTimeout.current) clearTimeout(searchTimeout.current);
            searchTimeout.current = setTimeout(() => {
                setDraw((d) => d + 1);
                fetchData();
            }, 400);
        },
        [fetchData],
    );

    const setPageIndex = useCallback(
        (idx: number) => {
            setPageIndexState(idx);
            setDraw((d) => d + 1);
            fetchData();
        },
        [fetchData],
    );

    const setPageSize = useCallback(
        (size: number) => {
            setPageSizeState(size);
            setPageIndexState(0);
            setDraw((d) => d + 1);
            fetchData();
        },
        [fetchData],
    );

    const setSort = useCallback(
        (colIdx: number) => {
            if (sortCol === colIdx) {
                setSortDir((d) => (d === 'asc' ? 'desc' : 'asc'));
            } else {
                setSortCol(colIdx);
                setSortDir('asc');
            }
            setPageIndexState(0);
            setDraw((d) => d + 1);
            fetchData();
        },
        [sortCol, fetchData],
    );

    return [
        { products, loading, totalRecords, filteredRecords, search, pageIndex, pageSize, sortCol, sortDir },
        { refresh, setSearch, setPageIndex, setPageSize, setSort },
    ];
}