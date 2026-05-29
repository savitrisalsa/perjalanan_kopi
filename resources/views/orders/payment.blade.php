@extends('layouts.app')

@section('content')

<h3>Pembayaran Order</h3>

<div class="card p-4 shadow-sm" style="max-width: 650px;">
    <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
    <p><strong>Total Bayar:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>

    <form action="/orders/{{ $order->id }}/payment" method="POST">
        @csrf

        <label>Metode Pembayaran</label>
        <select name="payment_method" id="payment_method" class="form-control" required onchange="togglePaymentInput()">
            <option value="cash">Cash</option>
            <option value="qris">QRIS</option>
        </select>

        <br>

        <!-- CASH -->
        <div id="cash_input">
            <label>Jumlah Bayar</label>
            <input type="number" name="amount_paid" id="amount_paid" class="form-control" min="{{ $order->total_price }}">
            <small class="text-muted">
                Isi uang dari customer, sistem otomatis hitung kembalian
            </small>
        </div>

        <!-- QRIS -->
        <div id="qris_info" style="display:none;">
            <div class="alert alert-info">
                <strong>Pembayaran QRIS</strong><br>
                Silakan customer scan QR di bawah ini.
            </div>

            <div class="text-center border rounded p-3 bg-white">
                <img src="{{ asset('images/qris.png') }}" style="max-width: 250px;">
                <p class="mt-2">
                    Total: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                </p>
            </div>
        </div>

        <br>

        <button type="submit" class="btn btn-primary" id="submit_button">
            Proses Pembayaran
        </button>

        <a href="/orders" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
function togglePaymentInput() {
    let method = document.getElementById('payment_method').value;

    let cashInput = document.getElementById('cash_input');
    let qrisInfo = document.getElementById('qris_info');
    let amountPaid = document.getElementById('amount_paid');
    let btn = document.getElementById('submit_button');

    if (method === 'cash') {
        cashInput.style.display = 'block';
        qrisInfo.style.display = 'none';
        amountPaid.required = true;
        amountPaid.value = '';
        btn.innerText = 'Proses Pembayaran Cash';
    }

    if (method === 'qris') {
        cashInput.style.display = 'none';
        qrisInfo.style.display = 'block';
        amountPaid.required = false;
        amountPaid.value = '{{ $order->total_price }}';
        btn.innerText = 'Konfirmasi QRIS Sudah Dibayar';
    }
}

togglePaymentInput();
</script>

@endsection