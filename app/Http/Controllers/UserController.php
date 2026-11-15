<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Manajemen akun - khusus admin.
 */
class UserController extends Controller
{
    public function index()
    {
        $data['dataUser'] = User::orderBy('name')->get();
        return view('admin.user.index', $data);
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role'     => ['required', Rule::in(User::ROLES)],
        ]);

        User::create($data); // password di-hash otomatis oleh cast 'hashed'

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $data['dataUser'] = $user;
        return view('admin.user.edit', $data);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role'     => ['required', Rule::in(User::ROLES)],
        ]);

        // Jangan biarkan admin menurunkan role dirinya sendiri (agar tidak terkunci).
        if ($user->id === $request->user()->id && $data['role'] !== User::ROLE_ADMIN) {
            return back()->withInput()->withErrors(['role' => 'Anda tidak dapat mengubah role akun Anda sendiri.']);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['hapus' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }
}
