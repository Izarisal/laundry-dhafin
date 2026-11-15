<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Sisi pelanggan: membuat pesanan baru & memantau status cucian miliknya.
 * Pelanggan TIDAK bisa mengubah status, mengedit, atau menghapus.
 */
class PesananController extends Controller
{
    public function index(Request $request)
    {
        $data['dataPesanan'] = Pelanggan::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('pesanan.index', $data);
    }

    public function create()
    {
        return view('pesanan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'              => ['required', 'string', 'max:100'],
            'alamat'            => ['required', 'string', 'max:500'],
            'jenis_laundry'     => ['required', Rule::in(Pelanggan::JENIS_LAUNDRY)],
            'jenis_pengerjaan'  => ['required', Rule::in(Pelanggan::JENIS_PENGERJAAN)],
            'nomor_hp'          => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'deskripsi_layanan' => ['nullable', 'string', 'max:1000'],
            'total_kilo'        => ['required', 'numeric', 'min:0.1', 'max:999'],
        ], [
            'required'       => ':attribute wajib diisi.',
            'in'             => ':attribute tidak valid.',
            'numeric'        => ':attribute harus berupa angka.',
            'total_kilo.min' => 'Total kilo minimal 0,1.',
            'nomor_hp.regex' => 'Nomor HP tidak valid (8-20 digit).',
        ], [
            'nama'             => 'Nama',
            'alamat'           => 'Alamat',
            'jenis_laundry'    => 'Jenis laundry',
            'jenis_pengerjaan' => 'Jenis pengerjaan',
            'nomor_hp'         => 'Nomor HP',
            'total_kilo'       => 'Total kilo',
        ]);

        // Pemilik & status ditentukan server, bukan dari input pengguna.
        $data['user_id']       = $request->user()->id;
        $data['status_cucian'] = Pelanggan::STATUS_DIPROSES;

        Pelanggan::create($data);

        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil dibuat. Pantau statusnya di sini.');
    }
}
