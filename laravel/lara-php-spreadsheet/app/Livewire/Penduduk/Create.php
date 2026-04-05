<?php

declare(strict_types=1);

namespace App\Livewire\Penduduk;

use App\Models\Penduduk;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Tambah Penduduk')]
class Create extends Component
{
  public string $nama = '';

  public ?int $usia = null;

  public string $alamat = '';

  public string $pekerjaan = '';

  protected function rules(): array
  {
    return [
      'nama' => ['required', 'string', 'max:255'],
      'usia' => ['required', 'integer', 'between:0,150'],
      'alamat' => ['required', 'string'],
      'pekerjaan' => ['required', 'string', 'max:255'],
    ];
  }

  protected function messages(): array
  {
    return [
      'nama.required' => 'Nama wajib diisi.',
      'nama.max' => 'Nama maksimal 255 karakter.',
      'usia.required' => 'Usia wajib diisi.',
      'usia.integer' => 'Usia harus berupa angka.',
      'usia.between' => 'Usia harus berada di rentang 0 sampai 150.',
      'alamat.required' => 'Alamat wajib diisi.',
      'pekerjaan.required' => 'Pekerjaan wajib diisi.',
      'pekerjaan.max' => 'Pekerjaan maksimal 255 karakter.',
    ];
  }

  public function store(): RedirectResponse
  {
    $validatedData = $this->validate();

    Penduduk::query()->create($validatedData);

    session()->flash('success', 'Data penduduk berhasil ditambahkan.');

    $this->dispatch('penduduk-updated');

    return redirect()->route('penduduk.index');
  }

  public function render(): View
  {
    return view('livewire.penduduk.create');
  }
}
