@extends('layouts.app')

@section('content')

<h3>Edit Menu</h3>

<form action="/products/{{ $product->id }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    @csrf
    @method('PUT')

    <label>Nama Menu</label>
    <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>

    <br>

    <label>Kategori</label>
    <select name="category" class="form-control" required>
        <option value="kopi" {{ $product->category == 'kopi' ? 'selected' : '' }}>Kopi</option>
        <option value="non_kopi" {{ $product->category == 'non_kopi' ? 'selected' : '' }}>Non Kopi</option>
        <option value="snack" {{ $product->category == 'snack' ? 'selected' : '' }}>Snack</option>
    </select>

    <br>

    <label>Harga Jual</label>
    <input type="number" name="selling_price" value="{{ $product->selling_price }}" class="form-control" required>

    <br>

    <label>HPP / Modal</label>
    <input type="number" name="hpp" value="{{ $product->hpp }}" class="form-control" required>

    <br>

    <label>Stok</label>
    <input type="number" name="stock" value="{{ $product->stock }}" class="form-control" required>

    <br>

    <label>Gambar Saat Ini</label><br>
    @if($product->image)
        <img src="{{ asset('storage/' . $product->image) }}" width="120" class="rounded">
    @else
        Tidak ada gambar
    @endif

    <br><br>

    <label>Ganti Gambar</label>
    <input type="file" name="image" class="form-control" accept="image/*">

    <br>

    <label>Status</label>
    <select name="status" class="form-control">
        <option value="available" {{ $product->status == 'available' ? 'selected' : '' }}>Available</option>
        <option value="unavailable" {{ $product->status == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
    </select>

    <br>

    <button type="submit" class="btn btn-primary">Update</button>
</form>

@endsection