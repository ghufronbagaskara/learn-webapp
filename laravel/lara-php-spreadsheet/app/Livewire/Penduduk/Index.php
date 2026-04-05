<?php

declare(strict_types=1);

namespace App\Livewire\Penduduk;

use App\Models\Penduduk;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Data Penduduk')]
class Index extends Component
{
  use WithPagination;

  public string $search = '';

  public ?int $pendingDeleteId = null;

  public function updatedSearch(): void
  {
    $this->resetPage();
  }

  public function confirmDelete(int $pendudukId): void
  {
    $this->pendingDeleteId = $pendudukId;
  }

  public function cancelDelete(): void
  {
    $this->pendingDeleteId = null;
  }

  public function deletePenduduk(): void
  {
    if ($this->pendingDeleteId === null) {
      return;
    }

    Penduduk::query()->find($this->pendingDeleteId)?->delete();

    $this->pendingDeleteId = null;

    session()->flash('success', 'Data penduduk berhasil dihapus.');
    $this->dispatch('penduduk-updated');
  }

  public function render(): View
  {
    $penduduks = Penduduk::query()
      ->when($this->search !== '', function ($query): void {
        $search = sprintf('%%%s%%', $this->search);

        $query->where(function ($subQuery) use ($search): void {
          $subQuery->where('nama', 'like', $search)
            ->orWhere('alamat', 'like', $search)
            ->orWhere('pekerjaan', 'like', $search);
        });
      })
      ->latest()
      ->paginate(10);

    return view('livewire.penduduk.index', compact('penduduks'));
  }
}
