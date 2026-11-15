<?php

namespace Tests\Feature;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaundryTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $o = []): array
    {
        return array_merge([
            'nama' => 'Budi', 'alamat' => 'Jl. Melati 1', 'nomor_hp' => '081234567890',
            'jenis_laundry' => 'laundry kiloan', 'jenis_pengerjaan' => 'cuci gosok',
            'total_kilo' => 3.5, 'deskripsi_layanan' => null,
        ], $o);
    }

    public function test_harga_dihitung_dari_tarif_dan_kilo(): void
    {
        $this->assertSame(31500, Pelanggan::hitungTotalHarga('laundry kiloan', 'cuci gosok', 3.5));
        $this->assertSame(24000, Pelanggan::hitungTotalHarga('laundry express', 'cuci aja', 2));
        $this->assertSame(16800, Pelanggan::hitungTotalHarga('laundry satuan', 'cuci gosok', 1.2));
    }

    public function test_tamu_dialihkan_ke_login(): void
    {
        $this->get('/')->assertRedirect('/dashboard');
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/pelanggan')->assertRedirect(route('login'));
    }

    public function test_register_selalu_membuat_role_pelanggan_dan_login(): void
    {
        $this->post('/register', [
            'name' => 'Ani', 'email' => 'ani@x.test', 'password' => 'rahasia1',
            'password_confirmation' => 'rahasia1', 'role' => 'admin', // coba menyusup
        ])->assertRedirect(route('dashboard'));

        $this->assertSame('pelanggan', User::where('email', 'ani@x.test')->value('role'));
        $this->assertAuthenticated();
    }

    public function test_login_berhasil_dan_gagal(): void
    {
        $u = User::factory()->create();
        $this->post('/login', ['email' => $u->email, 'password' => 'salah'])->assertSessionHasErrors('email');
        $this->post('/login', ['email' => $u->email, 'password' => 'password'])->assertRedirect(route('dashboard'));
    }

    public function test_pelanggan_buat_pesanan_harga_otomatis_dan_status_diproses(): void
    {
        $u = User::factory()->create();

        $this->actingAs($u)->post('/pesanan', $this->payload([
            'total_harga' => 1, 'status_cucian' => 'Selesai', 'user_id' => 999, // diabaikan
        ]))->assertRedirect(route('pesanan.index'));

        $p = Pelanggan::first();
        $this->assertSame(31500, $p->total_harga);
        $this->assertSame('Diproses', $p->status_cucian);
        $this->assertSame($u->id, $p->user_id);
    }

    public function test_pelanggan_hanya_melihat_pesanan_sendiri(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        Pelanggan::create($this->payload(['nama' => 'MILIK-A', 'user_id' => $a->id]));
        Pelanggan::create($this->payload(['nama' => 'MILIK-B', 'user_id' => $b->id]));

        $this->actingAs($a)->get('/pesanan')->assertSee('Rp 31.500')->assertOk();
        $this->actingAs($a)->get('/dashboard')->assertSee('MILIK-A')->assertDontSee('MILIK-B');
    }

    public function test_pelanggan_tidak_bisa_akses_area_admin(): void
    {
        $u = User::factory()->create();
        $p = Pelanggan::create($this->payload(['user_id' => $u->id]));

        $this->actingAs($u)->get('/pelanggan')->assertForbidden();
        $this->actingAs($u)->get('/user')->assertForbidden();
        $this->actingAs($u)->patch("/pelanggan/{$p->pelanggan_id}/status", ['status_cucian' => 'Selesai'])->assertForbidden();
        $this->actingAs($u)->delete("/pelanggan/{$p->pelanggan_id}")->assertForbidden();
        $this->assertSame('Diproses', $p->fresh()->status_cucian);
    }

    public function test_admin_tidak_masuk_area_pelanggan(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get('/pesanan/buat')->assertForbidden();
    }

    public function test_admin_crud_penuh_dan_ubah_status(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $this->get('/pelanggan')->assertOk();
        $this->get('/pelanggan/create')->assertOk();

        $this->post('/pelanggan', $this->payload(['status_cucian' => 'Diproses']))->assertRedirect(route('pelanggan.index'));
        $p = Pelanggan::first();
        $this->assertNull($p->user_id);
        $this->get("/pelanggan/{$p->pelanggan_id}/edit")->assertOk();

        // update -> harga dihitung ulang
        $this->put("/pelanggan/{$p->pelanggan_id}", $this->payload([
            'jenis_laundry' => 'laundry express', 'total_kilo' => 2, 'status_cucian' => 'Diproses',
        ]))->assertRedirect(route('pelanggan.index'));
        $this->assertSame(34000, $p->fresh()->total_harga);

        $this->patch("/pelanggan/{$p->pelanggan_id}/status", ['status_cucian' => 'Selesai']);
        $this->assertSame('Selesai', $p->fresh()->status_cucian);
        $this->patch("/pelanggan/{$p->pelanggan_id}/status", ['status_cucian' => 'Ngawur'])->assertSessionHasErrors();

        $this->get('/pelanggan?status=Selesai&q=Budi')->assertOk()->assertSee('Budi');
        $this->delete("/pelanggan/{$p->pelanggan_id}")->assertRedirect(route('pelanggan.index'));
        $this->assertSame(0, Pelanggan::count());
    }

    public function test_validasi_enum_dan_kilo(): void
    {
        $u = User::factory()->create();
        $this->actingAs($u)->post('/pesanan', $this->payload(['jenis_laundry' => 'xx', 'total_kilo' => 0]))
            ->assertSessionHasErrors(['jenis_laundry', 'total_kilo']);
    }

    public function test_admin_kelola_user_dan_tidak_bisa_hapus_diri_sendiri(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get('/user')->assertOk();
        $this->post('/user', [
            'name' => 'Baru', 'email' => 'baru@x.test', 'password' => 'abcdef',
            'password_confirmation' => 'abcdef', 'role' => 'pelanggan',
        ])->assertRedirect(route('user.index'));
        $this->assertTrue(\Hash::check('abcdef', User::where('email', 'baru@x.test')->first()->password));
        $this->delete("/user/{$admin->id}")->assertSessionHasErrors('hapus');
    }

    public function test_semua_halaman_tampil(): void
    {
        $this->get('/login')->assertOk()->assertSee('Masuk');
        $this->get('/register')->assertOk()->assertSee('Daftar');

        $admin = User::factory()->admin()->create();
        $u = User::factory()->create();
        $this->actingAs($admin);
        $this->get('/dashboard')->assertOk();
        $this->get('/user/create')->assertOk();
        $this->get("/user/{$u->id}/edit")->assertOk()->assertSee($u->email);

        $this->actingAs($u);
        $this->get('/pesanan/buat')->assertOk()->assertSee('Estimasi total harga');
        $this->get('/login')->assertRedirect(route('dashboard'));
    }
}
