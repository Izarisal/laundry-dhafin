<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel `pelanggan` = pencatatan transaksi / data pesanan laundry.
     */
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->increments('pelanggan_id');

            // Akun pemilik pesanan (agar pelanggan bisa memantau pesanannya sendiri).
            // Nullable: admin boleh mencatat pesanan walk-in tanpa akun.
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('nama', 100);
            $table->text('alamat');
            $table->enum('jenis_laundry', ['laundry kiloan', 'laundry satuan', 'laundry express']);
            $table->enum('jenis_pengerjaan', ['cuci aja', 'cuci gosok']);
            $table->string('nomor_hp', 20);
            $table->text('deskripsi_layanan')->nullable();
            $table->decimal('total_kilo', 8, 2);
            $table->unsignedBigInteger('total_harga')->default(0);
            $table->enum('status_cucian', ['Diproses', 'Selesai', 'Sudah Diambil'])->default('Diproses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
