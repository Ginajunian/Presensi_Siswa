@props(['paginator'])

<div class="flex items-center justify-between flex-wrap gap-3 px-5 py-4 border-t border-gray-100">
    <div class="flex items-center gap-3 flex-wrap">
        <p class="text-sm text-gray-500">
            @if ($paginator->total() > 0)
                Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
            @else
                Tidak ada data
            @endif
        </p>
        <x-per-page-select :per-page="$paginator->perPage()" />
    </div>

    @if ($paginator->hasPages())
        {{ $paginator->links() }}
    @endif
</div>