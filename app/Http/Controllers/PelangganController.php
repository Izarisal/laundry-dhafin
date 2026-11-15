<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * CRUD penuh pesanan laundry - khusus admin (lihat routes/web.php).
 */
class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status_cucian', $request->status);
        }
        if ($request->filled('q')) {
            $kata = $request->q;
            $query->where(function ($q) use ($kata) {
                $q->where('nama', 'like', "%{$kata}%")
                  ->orWhere('nomor_hp', 'like', "%{$kata}%");
            });
        }

        $data['dataPelanggan'] = $query->paginate(10)->withQueryString();
        return view('admin.pelanggan.index', $data);
    }

    public function create()
    {
        $data['dataUser'] = User::where('role', User::ROLE_PELANGGAN)->orderBy('name')->get();
        return view('admin.pelanggan.create', $data);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        Pelanggan::create($data); // total_harga dihitung otomatis di model

        return redirect()->route('pelanggan.index')->with('success', 'Pesanan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        $data['pesanan']  = $pelanggan;
        $data['dataUser'] = User::where('role', User::ROLE_PELANGGAN)->orderBy('name')->get();
        return view('admin.pelanggan.edit', $data);
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $pelanggan->update($this->validasi($request));

        return redirect()->route('pelanggan.index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    /**
     * Ubah status cucian saja (dropdown cepat di tabel).
     */
    public function updateStatus(Request $request, Pelanggan $pelanggan)
    {
        $data = $request->validate([
            'status_cucian' => ['required', Rule::in(Pelanggan::STATUS)],
        ]);

        $pelanggan->update($data);

        return back()->with('success', "Status pesanan {$pelanggan->nama} diubah menjadi {$pelanggan->status_cucian}.");
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')->with('success', 'Pesanan berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'user_id'           => ['nullable', Rule::exists('users', 'id')->where('role', User::ROLE_PELANGGAN)],
            'nama'              => ['required', 'string', 'max:100'],
            'alamat'            => ['required', 'string', 'max:500'],
            'jenis_laundry'     => ['required', Rule::in(Pelanggan::JENIS_LAUNDRY)],
            'jenis_pengerjaan'  => ['required', Rule::in(Pelanggan::JENIS_PENGERJAAN)],
            'nomor_hp'          => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'deskripsi_layanan' => ['nullable', 'string', 'max:1000'],
            'total_kilo'        => ['required', 'numeric', 'min:0.1', 'max:999'],
            'status_cucian'     => ['required', Rule::in(Pelanggan::STATUS)],
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
            'status_cucian'    => 'Status cucian',
        ]);
    }
}
