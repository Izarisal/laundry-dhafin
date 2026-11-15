{{-- Pemakaian: @include('partials.page-header', ['judul'=>..., 'sub'=>..., 'tombol'=>['url'=>..., 'teks'=>..., 'kelas'=>'btn-success']]) --}}
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">{{ $judul }}</h1>
            @isset($sub)<p class="mb-0">{{ $sub }}</p>@endisset
        </div>
        @isset($tombol)
            <div>
                <a href="{{ $tombol['url'] }}" class="btn {{ $tombol['kelas'] ?? 'btn-primary' }}">{{ $tombol['teks'] }}</a>
            </div>
        @endisset
    </div>
</div>
