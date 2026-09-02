@props(['perPage'])

<select onchange="const url = new URL(window.location); url.searchParams.set('per_page', this.value); url.searchParams.delete('page'); window.location = url;"
    class="border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400">
    @foreach ([10, 25, 50, 100] as $opsi)
        <option value="{{ $opsi }}" @selected($perPage == $opsi)>{{ $opsi }} / halaman</option>
    @endforeach
</select>