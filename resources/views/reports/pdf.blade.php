<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #111;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #111;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
        }

        .info {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        th {
            background: #1f2937;
            color: white;
            border: 1px solid #111;
            padding: 7px;
        }

        td {
            border: 1px solid #111;
            padding: 7px;
        }

        .section-title {
            background: #dbeafe;
            font-weight: bold;
            padding: 7px;
            border: 1px solid #111;
            margin-top: 15px;
        }

        .summary td {
            background: #ecfdf5;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>PERJALANAN KOPI</h2>
    <p>Jln. Srikaton Tengah No.7 RT.06/RW.07 | WA: 0857-7746-9269 | IG: @perjalanankopi_</p>
    <h3>LAPORAN PENJUALAN</h3>
</div>

<div class="info">
    <p><strong>Periode:</strong> {{ $start ?? '-' }} sampai {{ $end ?? '-' }}</p>
    <p><strong>Tanggal Cetak:</strong> {{ now()->format('d-m-Y H:i:s') }}</p>
    <p><strong>Dicetak Oleh:</strong> {{ auth()->user()->name ?? '-' }}</p>
</div>

<div class="section-title">Ringkasan Laporan</div>
<table>
    <tr>
        <th>Total Order</th>
        <th>Total Item</th>
        <th>Pendapatan</th>
        <th>Total HPP</th>
        <th>Diskon</th>
        <th>Keuntungan</th>
    </tr>
    <tr class="summary">
        <td>{{ $totalOrders }}</td>
        <td>{{ $totalItemsSold }}</td>
        <td>Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($totalHpp, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($totalDiscount, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
    </tr>
</table>

<div class="section-title">Menu Paling Laku</div>
<table>
    <tr>
        <th>No</th>
        <th>Nama Menu</th>
        <th>Total Terjual</th>
        <th>Total Pendapatan</th>
        <th>Total Keuntungan</th>
    </tr>

    @foreach($bestSellingMenus as $menu)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $menu['name'] }}</td>
        <td>{{ $menu['total_qty'] }}</td>
        <td>Rp {{ number_format($menu['total_income'], 0, ',', '.') }}</td>
        <td>Rp {{ number_format($menu['total_profit'], 0, ',', '.') }}</td>
    </tr>
    @endforeach
</table>

<div class="section-title">Menu Paling Untung</div>
<table>
    <tr>
        <th>No</th>
        <th>Nama Menu</th>
        <th>Total Terjual</th>
        <th>Total Pendapatan</th>
        <th>Total Keuntungan</th>
    </tr>

    @foreach($mostProfitableMenus as $menu)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $menu['name'] }}</td>
        <td>{{ $menu['total_qty'] }}</td>
        <td>Rp {{ number_format($menu['total_income'], 0, ',', '.') }}</td>
        <td>Rp {{ number_format($menu['total_profit'], 0, ',', '.') }}</td>
    </tr>
    @endforeach
</table>

<div class="section-title">Detail Order Selesai</div>
<table>
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

</body>
</html>