@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Laporan Penjualan</h3>

    <div>
        <a href="/reports/export/csv?{{ request()->getQueryString() }}" class="btn btn-success">Download CSV</a>
        <a href="/reports/export/excel?{{ request()->getQueryString() }}" class="btn btn-primary">Download Excel</a>
        <a href="/reports/export/pdf?{{ request()->getQueryString() }}" class="btn btn-danger">Download PDF</a>
        <a href="/reports/print?{{ request()->getQueryString() }}" class="btn btn-secondary">Print View</a>
    </div>
</div>

<div class="card p-3 mb-3 shadow-sm">
    <form method="GET" action="/reports" class="row g-2">
        <div class="col-md-3">
            <input type="date" name="start_date" value="{{ $start }}" class="form-control">
        </div>

        <div class="col-md-3">
            <input type="date" name="end_date" value="{{ $end }}" class="form-control">
        </div>

        <div class="col-md-6">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="/reports?period=today" class="btn btn-outline-primary">Hari Ini</a>
            <a href="/reports?period=week" class="btn btn-outline-primary">Minggu Ini</a>
            <a href="/reports?period=month" class="btn btn-outline-primary">Bulan Ini</a>
            <a href="/reports" class="btn btn-light">Reset</a>
        </div>
    </form>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <h6>Total Order</h6>
            <h4>{{ $totalOrders }}</h4>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <h6>Total Item Terjual</h6>
            <h4>{{ $totalItemsSold }}</h4>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <h6>Pendapatan</h6>
            <h4>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h4>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <h6>Keuntungan</h6>
            <h4>Rp {{ number_format($totalProfit, 0, ',', '.') }}</h4>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h6>Total HPP</h6>
            <h4>Rp {{ number_format($totalHpp, 0, ',', '.') }}</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h6>Total Diskon</h6>
            <h4>Rp {{ number_format($totalDiscount, 0, ',', '.') }}</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h6>Rata-rata Transaksi</h6>
            <h4>Rp {{ number_format($averageTransaction, 0, ',', '.') }}</h4>
        </div>
    </div>
</div>

<h5>Menu Paling Laku</h5>

<table class="table table-bordered bg-white">
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

<h5>Menu Paling Untung</h5>

<table class="table table-bordered bg-white">
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

<h5>Detail Order Selesai</h5>

<table class="table table-bordered bg-white">
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
        <td>{{ \Carbon\Carbon::parse($order->order_time)->format('d-m-Y H:i:s') }}</td>
        <td>
            @foreach($order->items as $item)
                {{ $item->product->name ?? '-' }} x{{ $item->quantity }}@if(!$loop->last), @endif
            @endforeach
        </td>
    </tr>
    @endforeach
</table>

@endsection