<div class="space-y-6">
    <livewire:penduduk.chart />

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <h1 class="card-title text-2xl">Data Penduduk</h1>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <label class="input input-bordered flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-4 w-4 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.1-4.15a7.25 7.25 0 11-14.5 0 7.25 7.25 0 0114.5 0z" />
                        </svg>
                        <input
                            type="text"
                            class="grow"
                            placeholder="Cari nama, alamat, atau pekerjaan"
                            wire:model.live.debounce.300ms="search"
                        >
                    </label>

                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-outline">Ekspor</div>
                        <ul tabindex="0" class="dropdown-content menu z-10 mt-2 w-48 rounded-box bg-base-100 p-2 shadow">
                            <li><a href="{{ route('penduduk.export', ['format' => 'csv']) }}">CSV</a></li>
                            <li><a href="{{ route('penduduk.export', ['format' => 'xls']) }}">XLS</a></li>
                            <li><a href="{{ route('penduduk.export', ['format' => 'xlsx']) }}">XLSX</a></li>
                        </ul>
                    </div>

                    <a href="{{ route('penduduk.create') }}" class="btn btn-primary">Tambah</a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Usia</th>
                            <th>Alamat</th>
                            <th>Pekerjaan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penduduks as $penduduk)
                            <tr wire:key="penduduk-row-{{ $penduduk->id }}">
                                <td>{{ $penduduks->firstItem() + $loop->index }}</td>
                                <td class="font-semibold">{{ $penduduk->nama }}</td>
                                <td>
                                    <span class="badge badge-info badge-outline">{{ $penduduk->usia }} tahun</span>
                                </td>
                                <td>{{ $penduduk->alamat }}</td>
                                <td>{{ $penduduk->pekerjaan }}</td>
                                <td class="text-right">
                                    <div class="join">
                                        <a href="{{ route('penduduk.edit', $penduduk) }}" class="btn btn-sm join-item">Edit</a>
                                        <button type="button" wire:click="confirmDelete({{ $penduduk->id }})" class="btn btn-sm btn-error join-item text-white">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-base-content/70">Data belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $penduduks->links() }}
            </div>
        </div>
    </div>

    <dialog class="modal {{ $pendingDeleteId !== null ? 'modal-open' : '' }}">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Konfirmasi Hapus</h3>
            <p class="py-4">Apakah Anda yakin ingin menghapus data penduduk ini?</p>
            <div class="modal-action">
                <button type="button" wire:click="cancelDelete" class="btn">Batal</button>
                <button type="button" wire:click="deletePenduduk" class="btn btn-error text-white">Ya, Hapus</button>
            </div>
        </div>
    </dialog>
</div>
