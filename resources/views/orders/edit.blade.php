@extends('layouts.app')

@section('content')

<h3>Edit Order</h3>

<form action="/orders/{{ $order->id }}" method="POST" class="card p-4 shadow-sm">
    @csrf
    @method('PUT')

    <label>Nama Customer</label>
    <input type="text" name="customer_name" class="form-control" value="{{ $order->customer_name }}" required>

    <br>

    <label>Tipe Order</label>
    <select name="order_type" class="form-control" required>
        <option value="dine_in" {{ $order->order_type == 'dine_in' ? 'selected' : '' }}>Dine In</option>
        <option value="take_away" {{ $order->order_type == 'take_away' ? 'selected' : '' }}>Take Away</option>
    </select>

    <br>

    <label>Catatan Pesanan</label>
    <textarea name="note" class="form-control">{{ $order->note }}</textarea>

    <br>

    <label>Diskon / Potongan Harga</label>
    <input type="number" name="discount_amount" class="form-control" value="{{ $order->discount_amount ?? 0 }}" min="0">

    <br>

    <h5>Pilih Menu</h5>

    @php
        $selectedItems = $order->items->keyBy('product_id');
    @endphp

    <div class="table-responsive">
        <table class="table table-bordered bg-white">
            <tr>
                <th>Pilih</th>
                <th>Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok Saat Ini</th>
                <th>Qty</th>
            </tr>

            @foreach($products as $product)
            @php
                $selected = $selectedItems->has($product->id);
                $oldQty = $selected ? $selectedItems[$product->id]->quantity : 1;
            @endphp

            <tr>
                <td>
                    <input type="checkbox" name="products[]" value="{{ $product->id }}" {{ $selected ? 'checked' : '' }}>
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category }}</td>
                <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <input type="number" name="quantities[{{ $product->id }}]" value="{{ $oldQty }}" min="1" class="form-control" style="width:100px;">
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <button type="submit" class="btn btn-primary">Update Order</button>
    <a href="/orders" class="btn btn-secondary">Kembali</a>
</form>

@endsection