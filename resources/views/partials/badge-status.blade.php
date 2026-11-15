@php
    $warna = match ($status) {
        'Diproses'      => 'warning',
        'Selesai'       => 'success',
        'Sudah Diambil' => 'secondary',
        default         => 'dark',
    };
@endphp
<span class="badge bg-{{ $warna }}">{{ $status }}</span>
