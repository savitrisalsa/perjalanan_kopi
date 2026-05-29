@extends('layouts.app')

@section('content')

<h3>Profil Admin</h3>

<div class="card p-4 shadow-sm" style="max-width: 600px;">
    <form action="/profile" method="POST">
        @csrf
        @method('PUT')

        <label>Nama Admin</label>
        <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>

        <br>

        <label>Username</label>
        <input type="text" name="username" class="form-control" value="{{ auth()->user()->username }}" required>

        <hr>

        <h5>Ganti Password</h5>
        <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>

        <br><br>

        <label>Password Lama</label>
        <input type="password" name="current_password" class="form-control">

        <br>

        <label>Password Baru</label>
        <input type="password" name="new_password" class="form-control">

        <br>

        <label>Konfirmasi Password Baru</label>
        <input type="password" name="new_password_confirmation" class="form-control">

        <br>

        <button class="btn btn-primary">Simpan Perubahan</button>
        <a href="/dashboard" class="btn btn-secondary">Kembali</a>
    </form>
</div>

@endsection