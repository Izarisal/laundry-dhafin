@extends('layouts.admin.app')

@section('title', 'Kelola User')

@section('content')
    @include('partials.page-header', [
        'judul' => 'Kelola User',
        'sub' => 'Daftar akun admin dan pelanggan.',
        'tombol' => ['url' => route('user.create'), 'teks' => 'Tambah User', 'kelas' => 'btn-success text-white'],
    ])

    <div class="card border-0 shadow mb-4">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0">Nama</th>
                        <th class="border-0">Email</th>
                        <th class="border-0">Role</th>
                        <th class="border-0 rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataUser as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td><span class="badge bg-{{ $u->isAdmin() ? 'danger' : 'info' }}">{{ $u->role }}</span></td>
                            <td>
                                <a href="{{ route('user.edit', $u) }}" class="btn btn-info btn-sm">Edit</a>
                                @if ($u->id !== auth()->id())
                                    <form action="{{ route('user.destroy', $u) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus user {{ e($u->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
