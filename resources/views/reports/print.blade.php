<!DOCTYPE html>
<html>
<head>
    <title>Print Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="container mt-4">
    <div class="no-print mb-3">
        <a href="/reports" class="btn btn-secondary">Kembali ke Laporan</a>
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>

    <h2>PERJALANAN KOPI</h2>
    <h4>Laporan Penjualan</h4>

    <p>Periode: {{ $start ?? '-' }} sampai {{ $end ?? '-' }}</p>
    <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i:s') }}</p>
    <p>Dicetak Oleh: {{ auth()->user()->name ?? '-' }}</p>

    <table class="table table-bordered">
        <tr>
            <th>Total Order</th>
            <th>Total Item</th>
            <th>Pendapatan</th>
            <th>Total HPP</th>
            <th>Diskon</th>
            <th>Keuntungan</th>
        </tr>
        <tr>
            <td>{{ $totalOrders }}</td>
            <td>{{ $totalItemsSold }}</td>
            <td>Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($totalHpp, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($totalDiscount, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h5>Detail Order Selesai</h5>

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Diskon</th>
            <th>Metode Bayar</th>
            <th>Waktu</th>
        </tr>

        @foreach($orders as $order)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}</td>
            <td>{{ $order->payment->payment_method ?? '-' }}</td>
            <td>{{ $order->order_time }}</td>
        </tr>
        @endforeach
    </table>
</div>

</body>
</html>