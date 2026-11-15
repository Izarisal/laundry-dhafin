<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo (password: "password")
        User::create([
            'name' => 'Admin Laundry', 'email' => 'admin@laundry.test',
            'password' => 'password', 'role' => User::ROLE_ADMIN,
        ]);

        $budi = User::create([
            'name' => 'Budi Santoso', 'email' => 'budi@laundry.test',
            'password' => 'password', 'role' => User::ROLE_PELANGGAN,
        ]);

        // Contoh pesanan (total_harga dihitung otomatis)
        Pelanggan::create([
            'user_id' => $budi->id, 'nama' => 'Budi Santoso', 'alamat' => 'Jl. Melati No. 10, Denpasar',
            'jenis_laundry' => 'laundry kiloan', 'jenis_pengerjaan' => 'cuci gosok',
            'nomor_hp' => '081234567890', 'deskripsi_layanan' => 'Pisahkan baju putih.',
            'total_kilo' => 3.5, 'status_cucian' => Pelanggan::STATUS_DIPROSES,
        ]);
        Pelanggan::create([
            'user_id' => $budi->id, 'nama' => 'Budi Santoso', 'alamat' => 'Jl. Melati No. 10, Denpasar',
            'jenis_laundry' => 'laundry express', 'jenis_pengerjaan' => 'cuci aja',
            'nomor_hp' => '081234567890', 'total_kilo' => 2,
            'status_cucian' => Pelanggan::STATUS_SELESAI,
        ]);
        Pelanggan::create([
            'nama' => 'Siti Aminah (walk-in)', 'alamat' => 'Jl. Kenanga No. 3, Denpasar',
            'jenis_laundry' => 'laundry satuan', 'jenis_pengerjaan' => 'cuci gosok',
            'nomor_hp' => '085711223344', 'deskripsi_layanan' => 'Jas 1 pcs, hati-hati.',
            'total_kilo' => 1.2, 'status_cucian' => Pelanggan::STATUS_SUDAH_DIAMBIL,
        ]);
    }
}
