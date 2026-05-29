@extends('layouts.app')

@section('content')

<h3>Detail Order</h3>

<div class="card p-4 shadow-sm">
    <p><strong>No Order:</strong> {{ $order->id }}</p>
    <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
    <p><strong>Tipe Order:</strong> {{ $order->order_type }}</p>
    <p><strong>Catatan:</strong> {{ $order->note ?? '-' }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($order->order_time)->format('d-m-Y H:i:s') }}</p>
</div>

<br>

<h5>Item Pesanan</h5>

<table class="table table-bordered bg-white">
    <tr>
        <th>No</th>
        <th>Menu</th>
        <th>Qty</th>
        <th>Harga Jual</th>
        <th>HPP</th>
        <th>Subtotal</th>
        <th>Keuntungan Item</th>
    </tr>

    @foreach($order->items as $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->product->name ?? '-' }}</td>
        <td>{{ $item->quantity }}</td>
        <td>Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($item->hpp, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        <td>Rp {{ number_format(($item->selling_price - $item->hpp) * $item->quantity, 0, ',', '.') }}</td>
    </tr>
    @endforeach
</table>

<p><strong>Diskon:</strong> Rp {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}</p>
<p><strong>Total Akhir:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>

@if($order->payment)
    <p><strong>Metode Bayar:</strong> {{ $order->payment->payment_method }}</p>
    <p><strong>Jumlah Bayar:</strong> Rp {{ number_format($order->payment->amount_paid, 0, ',', '.') }}</p>
    <p><strong>Kembalian:</strong> Rp {{ number_format($order->payment->change_amount, 0, ',', '.') }}</p>
@endif

<a href="/orders" class="btn btn-secondary">Kembali</a>

@endsection