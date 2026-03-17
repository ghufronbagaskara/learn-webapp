export interface Product {
  id: number;
  no?: string;
  name: string;
  sku: string;
  description: string;
  category: string;
  price: number;
  formatted_price: string;
  stock: number;
  status: 'active' | 'inactive' | 'draft';
  created_at: string;
}

export interface DatatableResponse {
  draw: number;
  recordsTotal: number;
  recordsFiltered: number;
  data: Product[];
}

export interface ProductFormData {
  name: string;
  sku: string;
  description: string;
  category: string;
  price: string;
  stock: string;
  status: 'active' | 'inactive' | 'draft';
}

export type ModalMode = 'create' | 'edit' | 'delete' | null;

export interface ModalState {
  mode: ModalMode;
  product: Product | null;
}

export interface Column {
  key: keyof Product | 'no' | 'actions';
  label: string;
  sortable: boolean;
  visible: boolean;
  serverIndex?: number; // index from server side
  width?: string;
}

export const PRODUCT_CATEGORIES = [
  'Electronics',
  'Fashion',
  'Food & Beverage',
  'Sports',
  'Home & Living',
  'Beauty',
  'Automotive',
  'Books',
] as const;

export const STATUS_OPTIONS = ['active', 'inactive', 'draft'] as const;

export const INITIAL_FORM: ProductFormData = {
  name: '',
  sku: '',
  description: '',
  category: '',
  price: '',
  stock: '',
  status: 'active',
};

export const DEFAULT_COLUMNS: Column[] = [
  { key: 'no', label: 'No', sortable: false, visible: true, width: '60px' },
  {
    key: 'name',
    label: 'Nama Produk',
    sortable: true,
    visible: true,
    serverIndex: 1,
  },
  { key: 'sku', label: 'SKU', sortable: true, visible: true, serverIndex: 2 },
  {
    key: 'category',
    label: 'Kategori',
    sortable: true,
    visible: true,
    serverIndex: 3,
  },
  {
    key: 'formatted_price',
    label: 'Harga',
    sortable: true,
    visible: true,
    serverIndex: 4,
  },
  {
    key: 'stock',
    label: 'Stok',
    sortable: true,
    visible: true,
    serverIndex: 5,
  },
  {
    key: 'status',
    label: 'Status',
    sortable: true,
    visible: true,
    serverIndex: 6,
  },
  {
    key: 'created_at',
    label: 'Dibuat',
    sortable: true,
    visible: false,
    serverIndex: 7,
  },
  {
    key: 'actions',
    label: 'Aksi',
    sortable: false,
    visible: true,
    width: '100px',
  },
];
