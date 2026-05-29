@extends('layouts.app')

@section('content')

<h3>Dashboard Admin</h3>

<div class="row mt-3">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm border-0">
            <h6>Total Order Hari Ini</h6>
            <h3>{{ $totalOrdersToday }}</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow-sm border-0">
            <h6>Pendapatan Hari Ini</h6>
            <h3>Rp {{ number_format($incomeToday, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow-sm border-0">
            <h6>Keuntungan Hari Ini</h6>
            <h3>Rp {{ number_format($profitToday, 0, ',', '.') }}</h3>
        </div>
    </div>
</div>

<br>

<div class="card p-4 shadow-sm border-0 mb-4">
    <h5>Chart Menu Terjual</h5>
    <canvas id="menuChart" height="100"></canvas>
</div>

<div class="row">
    <div class="col-md-6">
        <h5>Menu Paling Laku</h5>

        <table class="table table-bordered bg-white">
            <tr>
                <th>No</th>
                <th>Nama Menu</th>
                <th>Total Terjual</th>
            </tr>

            @foreach($bestSellingMenus as $menu)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $menu->product->name ?? '-' }}</td>
                <td>{{ $menu->total_sold }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="col-md-6">
        <h5>Order Terbaru</h5>

        <table class="table table-bordered bg-white">
            <tr>
                <th>No</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
            </tr>

            @foreach($recentOrders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>{{ $order->status }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

@endsection

@section('script')
<script>
    const ctx = document.getElementById('menuChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Total Terjual',
                data: @json($chartData),
                borderWidth: 1
            }]
        }
    });
</script>
@endsection