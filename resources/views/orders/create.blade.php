@extends('layouts.app')

@section('content')

<h3>Buat Pesanan</h3>

<div class="card p-3 mb-3 shadow-sm">
    <form method="GET" action="/orders/create" class="row g-2">
        <div class="col-md-4">
            <select name="category" class="form-control">
                <option value="">Semua Kategori</option>
                <option value="kopi" {{ request('category') == 'kopi' ? 'selected' : '' }}>Kopi</option>
                <option value="non_kopi" {{ request('category') == 'non_kopi' ? 'selected' : '' }}>Non Kopi</option>
                <option value="snack" {{ request('category') == 'snack' ? 'selected' : '' }}>Snack</option>
            </select>
        </div>

        <div class="col-md-4">
            <button class="btn btn-secondary">Filter Menu</button>
            <a href="/orders/create" class="btn btn-light">Reset</a>
        </div>
    </form>
</div>

<form action="/orders" method="POST" class="card p-4 shadow-sm">
    @csrf

    <label>Nama Customer</label>
    <input type="text" name="customer_name" class="form-control" required>

    <br>

    <label>Tipe Order</label>
    <select name="order_type" class="form-control" required>
        <option value="dine_in">Dine In</option>
        <option value="take_away">Take Away</option>
    </select>

    <br>

    <label>Catatan Pesanan</label>
    <textarea name="note" class="form-control" placeholder="Contoh: less ice, gula sedikit, tanpa topping"></textarea>

    <br>

    <label>Diskon / Potongan Harga</label>
    <input type="number" name="discount_amount" class="form-control" value="0" min="0">

    <br>

    <h5>Pilih Menu</h5>

    <div class="table-responsive">
        <table class="table table-bordered bg-white">
            <tr>
                <th>Pilih</th>
                <th>Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Qty</th>
            </tr>

            @foreach($products as $product)
            <tr>
                <td>
                    <input type="checkbox" name="products[]" value="{{ $product->id }}">
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category }}</td>
                <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <input type="number" name="quantities[{{ $product->id }}]" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="width:100px;">
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Order</button>
</form>

@endsection