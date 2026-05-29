@extends('layouts.app')

@section('content')

<div class="card p-4 shadow-sm" style="max-width: 500px;">
    <h4 class="text-center">PERJALANAN KOPI</h4>
    <p class="text-center mb-1">Rasa dalam setiap perjalanan</p>
    <p class="text-center small mb-0">Jln. Srikaton Tengah No.7 RT.06/RW.07</p>
    <p class="text-center small mb-0">WhatsApp: 0857-7746-9269</p>
    <p class="text-center small">Instagram: @perjalanankopi_</p>

    <hr>

    <p><strong>No Order:</strong> {{ $order->id }}</p>
    <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
    <p><strong>Tipe:</strong> {{ $order->order_type }}</p>
    <p><strong>Catatan:</strong> {{ $order->note ?? '-' }}</p>
    <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($order->order_time)->format('d-m-Y H:i:s') }}</p>

    <hr>

    <table class="table table-sm">
        <tr>
            <th>Menu</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>

        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product->name ?? '-' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <hr>

    <p><strong>Diskon:</strong> Rp {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}</p>
    <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
    <p><strong>Metode Bayar:</strong> {{ $order->payment->payment_method ?? '-' }}</p>
    <p><strong>Bayar:</strong> Rp {{ number_format($order->payment->amount_paid ?? 0, 0, ',', '.') }}</p>
    <p><strong>Kembalian:</strong> Rp {{ number_format($order->payment->change_amount ?? 0, 0, ',', '.') }}</p>

    <hr>

    <p class="text-center mb-1">Terima kasih sudah berkunjung.</p>
    <p class="text-center small">Simpan struk ini sebagai bukti pembayaran.</p>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">Print Struk</button>
        <a href="/orders" class="btn btn-secondary">Kembali</a>
    </div>
</div>

@endsection