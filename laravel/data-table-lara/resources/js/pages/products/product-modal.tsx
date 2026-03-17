import { ProductDeleteModal } from './product-delete-modal';
import { ProductFormModal } from './product-form-modal';
import type { ModalState } from './types';

interface ProductModalProps {
  state: ModalState;
  onClose: () => void;
}

/**
 * Orchestrator — decides which modal to render based on `state.mode`.
 * Renders nothing when mode is null.
 */
export function ProductModal({ state, onClose }: ProductModalProps) {
  if (!state.mode) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
      {state.mode === 'delete' && state.product ? (
        <ProductDeleteModal
          key={`delete-${state.product.id}`}
          product={state.product}
          onClose={onClose}
        />
      ) : state.mode === 'create' || state.mode === 'edit' ? (
        <ProductFormModal
          key={`${state.mode}-${state.product?.id || 'new'}`}
          mode={state.mode}
          product={state.product}
          onClose={onClose}
        />
      ) : null}
    </div>
  );
}
