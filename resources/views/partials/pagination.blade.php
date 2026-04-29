{{-- resources/views/partials/pagination.blade.php --}}

<nav role="navigation" class="flex items-center justify-center gap-2 mt-8 mb-4">
    {{-- Tombol Previous --}}
    @if ($paginator->onFirstPage())
        <span class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-300 cursor-not-allowed">
            <span class="material-symbols-outlined text-sm">chevron_left</span>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100 transition-all">
            <span class="material-symbols-outlined text-sm">chevron_left</span>
        </a>
    @endif

    {{-- Angka Halaman --}}
    @if($paginator->lastPage() > 0)
        @foreach (range(1, $paginator->lastPage()) as $i)
            @if($i == $paginator->currentPage())
                {{-- Lingkaran Aktif --}}
                <span class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white font-bold shadow-md shadow-blue-200">
                    {{ $i }}
                </span>
            @else
                {{-- Lingkaran Tidak Aktif --}}
                <a href="{{ $paginator->url($i) }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:border-blue-500 hover:text-blue-500 transition-all font-medium">
                    {{ $i }}
                </a>
            @endif
        @endforeach
    @else
        {{-- Tampilan Default jika data kosong/sedikit agar tetap ada angka 1 --}}
        <span class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white font-bold shadow-md shadow-blue-200">1</span>
    @endif

    {{-- Tombol Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100 transition-all">
            <span class="material-symbols-outlined text-sm">chevron_right</span>
        </a>
    @else
        <span class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-300 cursor-not-allowed">
            <span class="material-symbols-outlined text-sm">chevron_right</span>
        </span>
    @endif
</nav>