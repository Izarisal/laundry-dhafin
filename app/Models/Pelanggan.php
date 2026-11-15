<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tabel `pelanggan` menyimpan data pesanan/transaksi laundry.
 */
class Pelanggan extends Model
{
    protected $table      = 'pelanggan';
    protected $primaryKey = 'pelanggan_id';

    public const JENIS_LAUNDRY    = ['laundry kiloan', 'laundry satuan', 'laundry express'];
    public const JENIS_PENGERJAAN = ['cuci aja', 'cuci gosok'];

    public const STATUS_DIPROSES      = 'Diproses';
    public const STATUS_SELESAI       = 'Selesai';
    public const STATUS_SUDAH_DIAMBIL = 'Sudah Diambil';
    public const STATUS = [self::STATUS_DIPROSES, self::STATUS_SELESAI, self::STATUS_SUDAH_DIAMBIL];

    /**
     * Tarif standar (Rupiah per kilo) berdasarkan kombinasi
     * jenis_laundry x jenis_pengerjaan. Ubah angka di sini untuk mengganti tarif.
     */
    public const TARIF = [
        'laundry kiloan'  => ['cuci aja' => 6000,  'cuci gosok' => 9000],
        'laundry satuan'  => ['cuci aja' => 10000, 'cuci gosok' => 14000],
        'laundry express' => ['cuci aja' => 12000, 'cuci gosok' => 17000],
    ];

    // total_harga sengaja TIDAK fillable: selalu dihitung backend.
    protected $fillable = [
        'user_id',
        'nama',
        'alamat',
        'jenis_laundry',
        'jenis_pengerjaan',
        'nomor_hp',
        'deskripsi_layanan',
        'total_kilo',
        'status_cucian',
    ];

    protected function casts(): array
    {
        return [
            'total_kilo'  => 'decimal:2',
            'total_harga' => 'integer',
        ];
    }

    /**
     * Hitung total harga = tarif per kilo x total kilo (dibulatkan ke Rupiah).
     */
    public static function hitungTotalHarga(string $jenisLaundry, string $jenisPengerjaan, float|int|string $totalKilo): int
    {
        $tarif = self::TARIF[$jenisLaundry][$jenisPengerjaan] ?? 0;

        return (int) round($tarif * (float) $totalKilo);
    }

    protected static function booted(): void
    {
        // Setiap kali disimpan (create/update) harga dihitung ulang otomatis.
        static::saving(function (Pelanggan $pesanan) {
            $pesanan->total_harga = self::hitungTotalHarga(
                (string) $pesanan->jenis_laundry,
                (string) $pesanan->jenis_pengerjaan,
                $pesanan->total_kilo ?? 0
            );
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
