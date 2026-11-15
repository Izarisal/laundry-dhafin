<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Admin melihat semua pesanan, pelanggan hanya miliknya sendiri.
        $query = Pelanggan::query();
        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $data['jumlah'] = [
            'total'    => (clone $query)->count(),
            'diproses' => (clone $query)->where('status_cucian', Pelanggan::STATUS_DIPROSES)->count(),
            'selesai'  => (clone $query)->where('status_cucian', Pelanggan::STATUS_SELESAI)->count(),
            'diambil'  => (clone $query)->where('status_cucian', Pelanggan::STATUS_SUDAH_DIAMBIL)->count(),
        ];
        $data['totalHarga'] = (clone $query)->sum('total_harga');
        $data['terbaru']    = (clone $query)->latest()->limit(5)->get();

        return view('admin.dashboard', $data);
    }
}
