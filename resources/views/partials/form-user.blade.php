@php $u = $u ?? null; $edit = $edit ?? false; @endphp
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" id="name" name="name" required maxlength="100"
            class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $u->name ?? '') }}">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" required
            class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $u->email ?? '') }}">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="role" class="form-label">Role</label>
        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
            @foreach (\App\Models\User::ROLES as $r)
                <option value="{{ $r }}" @selected(old('role', $u->role ?? 'pelanggan') === $r)>{{ $r }}</option>
            @endforeach
        </select>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="password" class="form-label">Password @if($edit)<small class="text-muted">(kosongkan jika tidak diubah)</small>@endif</label>
        <input type="password" id="password" name="password" @required(! $edit)
            class="form-control @error('password') is-invalid @enderror">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-4">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" @required(! $edit) class="form-control">
    </div>
</div>
