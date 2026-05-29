<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            background-color: #1f2937;
            color: white;
        }

        .subtitle {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            background-color: #374151;
            color: white;
        }

        .section {
            font-weight: bold;
            background-color: #dbeafe;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #111827;
            color: white;
            font-weight: bold;
            border: 1px solid #000;
            padding: 8px;
        }

        td {
            border: 1px solid #000;
            padding: 8px;
        }

        .summary {
            background-color: #ecfdf5;
            font-weight: bold;
        }
    </style>
</head>
<body>

<table>
    <tr>
        <td colspan="7" class="title">PERJALANAN KOPI</td>
    </tr>
    <tr>
        <td colspan="7" class="subtitle">LAPORAN PENJUALAN</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Periode</strong></td>
        <td colspan="5">{{ $start ?? '-' }} sampai {{ $end ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Tanggal Cetak</strong></td>
        <td colspan="5">{{ now()->format('d-m-Y H:i:s') }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Dicetak Oleh</strong></td>
        <td colspan="5">{{ auth()->user()->name ?? '-' }}</td>
    </tr>
</table>

<br>

<table>
    <tr>
        <td colspan="7" class="section">RINGKASAN LAPORAN</td>
    </tr>
    <tr>
        <th>Total Order</th>
        <th>Total Item Terjual</th>
        <th>Pendapatan</th>
        <th>Total HPP</th>
        <th>Total Diskon</th>
        <th>Keuntungan</th>
        <th>Rata-rata Transaksi</th>
    </tr>
    <tr class="summary">
        <td>{{ $totalOrders }}</td>
        <td>{{ $totalItemsSold }}</td>
        <td>Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($totalHpp, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($totalDiscount, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($averageTransaction, 0, ',', '.') }}</td>
    </tr>
</table>

<br>

<table>
    <tr>
        <td colspan="5" class="section">MENU PALING LAKU</td>
    </tr>
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

<br>

<table>
    <tr>
        <td colspan="5" class="section">MENU PALING UNTUNG</td>
    </tr>
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

<br>

<table>
    <tr>
        <td colspan="7" class="section">DETAIL ORDER SELESAI</td>
    </tr>
    <tr>
        <th>No</th>
        <th>Customer</th>
        <th>Total</th>
        <th>Diskon</th>
        <th>Metode Bayar</th>
        <th>Waktu</th>
        <th>Item Pesanan</th>
    </tr>

    @foreach($orders as $order)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $order->customer_name }}</td>
        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}</td>
        <td>{{ $order->payment->payment_method ?? '-' }}</td>
        <td>{{ $order->order_time }}</td>
        <td>
            @foreach($order->items as $item)
                {{ $item->product->name ?? '-' }} x{{ $item->quantity }}@if(!$loop->last), @endif
            @endforeach
        </td>
    </tr>
    @endforeach
</table>

</body>
</html>