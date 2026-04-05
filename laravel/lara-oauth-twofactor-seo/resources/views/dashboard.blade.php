<x-layouts::app :title="__('Dashboard')">
    <div class="mx-auto grid w-full max-w-6xl gap-6 md:grid-cols-3">
        <a href="{{ route('blog.create') }}" class="rounded-xl border border-zinc-200 bg-white p-6 hover:border-indigo-300 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">Konten</p>
            <h2 class="mt-2 text-xl font-bold">Tulis Artikel Baru</h2>
            <p class="mt-2 text-sm text-zinc-600">Buat draft atau publikasi artikel lengkap dengan SEO metadata.</p>
        </a>

        <a href="{{ route('categories.index') }}" class="rounded-xl border border-zinc-200 bg-white p-6 hover:border-indigo-300 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">Klasifikasi</p>
            <h2 class="mt-2 text-xl font-bold">Kelola Kategori</h2>
            <p class="mt-2 text-sm text-zinc-600">Atur kategori artikel agar navigasi konten lebih rapi.</p>
        </a>

        <a href="{{ route('comments.index') }}" class="rounded-xl border border-zinc-200 bg-white p-6 hover:border-indigo-300 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">Moderasi</p>
            <h2 class="mt-2 text-xl font-bold">Review Komentar</h2>
            <p class="mt-2 text-sm text-zinc-600">Approve atau tolak komentar sebelum tampil di artikel.</p>
        </a>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 md:col-span-3 dark:bg-zinc-900">
            <h2 class="text-xl font-bold">Status Platform</h2>
            <p class="mt-2 text-sm text-zinc-600">
                OAuth, 2FA TOTP, SEO metadata, sitemap, komentar Livewire, dan newsletter telah terintegrasi dalam platform ini.
            </p>
            <a href="{{ route('blog.index') }}" class="mt-4 inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                Buka Halaman Blog
            </a>
        </div>
    </div>
</x-layouts::app>
