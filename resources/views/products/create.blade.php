@extends('layouts.app')

@section('content')

<h3>Tambah Menu</h3>

<form action="/products" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    @csrf

    <label>Nama Menu</label>
    <input type="text" name="name" class="form-control" required>

    <br>

    <label>Kategori</label>
    <select name="category" class="form-control" required>
        <option value="kopi">Kopi</option>
        <option value="non_kopi">Non Kopi</option>
        <option value="snack">Snack</option>
    </select>

    <br>

    <label>Harga Jual</label>
    <input type="number" name="selling_price" class="form-control" required>

    <br>

    <label>HPP / Modal</label>
    <input type="number" name="hpp" class="form-control" required>

    <br>

    <label>Stok</label>
    <input type="number" name="stock" class="form-control" required>

    <br>

    <label>Gambar Menu</label>
    <input type="file" name="image" class="form-control" accept="image/*">

    <br>

    <label>Status</label>
    <select name="status" class="form-control">
        <option value="available">Available</option>
        <option value="unavailable">Unavailable</option>
    </select>

    <br>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

@endsection