@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Order</h3>
    <a href="/orders/create" class="btn btn-primary">+ Buat Order</a>
</div>

<div class="card p-3 mb-3 shadow-sm">
    <form method="GET" action="/orders" class="row g-2">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Cari customer..." value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <div class="col-md-4">
            <button class="btn btn-secondary">Filter</button>
            <a href="/orders" class="btn btn-light">Reset</a>
        </div>
    </form>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <tr>
                <th>No</th>
                <th>Customer</th>
                <th>Tipe</th>
                <th>Total</th>
                <th>Diskon</th>
                <th>Status</th>
                <th>Waktu</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>

            @foreach($orders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->order_type }}</td>
                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}</td>
                <td>
                    @if($order->status == 'pending')
                        <span class="badge bg-warning">pending</span>
                    @elseif($order->status == 'processing')
                        <span class="badge bg-primary">processing</span>
                    @elseif($order->status == 'ready')
                        <span class="badge bg-info">ready</span>
                    @else
                        <span class="badge bg-success">completed</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($order->order_time)->format('d-m-Y H:i:s') }}</td>
                <td>{{ $order->note ?? '-' }}</td>
                <td>
                    <a href="/orders/{{ $order->id }}/detail" class="btn btn-sm btn-info">Detail</a>

                    @if($order->status != 'completed')
                        <a href="/orders/{{ $order->id }}/edit" class="btn btn-sm btn-warning">Edit</a>

                        <form action="/orders/{{ $order->id }}/status" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="processing">
                            <button class="btn btn-sm btn-primary">Proses</button>
                        </form>

                        <form action="/orders/{{ $order->id }}/status" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="ready">
                            <button class="btn btn-sm btn-secondary">Ready</button>
                        </form>

                        <a href="/orders/{{ $order->id }}/payment" class="btn btn-sm btn-success">Bayar</a>
                    @else
                        <a href="/orders/{{ $order->id }}/receipt" class="btn btn-sm btn-secondary">Struk</a>
                    @endif

                    <form action="/orders/{{ $order->id }}" method="POST" style="display:inline;"
                          class="confirm-form"
                          data-title="Hapus Order"
                          data-message="Yakin hapus order ini? Stok pesanan akan dikembalikan.">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>

@endsection