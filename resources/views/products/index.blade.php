@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Manajemen Menu dan Stok</h3>
    <a href="/products/create" class="btn btn-primary">+ Tambah Menu</a>
</div>

<div class="card p-3 mb-3 shadow-sm">
    <form method="GET" action="/products" class="row g-2">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Cari nama menu..." value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select name="category" class="form-control">
                <option value="">Semua Kategori</option>
                <option value="kopi" {{ request('category') == 'kopi' ? 'selected' : '' }}>Kopi</option>
                <option value="non_kopi" {{ request('category') == 'non_kopi' ? 'selected' : '' }}>Non Kopi</option>
                <option value="snack" {{ request('category') == 'snack' ? 'selected' : '' }}>Snack</option>
            </select>
        </div>

        <div class="col-md-4">
            <button class="btn btn-secondary">Filter</button>
            <a href="/products" class="btn btn-light">Reset</a>
        </div>
    </form>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Harga Jual</th>
                <th>HPP</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            @foreach($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" width="60" class="rounded">
                    @else
                        -
                    @endif
                </td>

                <td>{{ $product->name }}</td>
                <td>{{ $product->category }}</td>
                <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($product->hpp, 0, ',', '.') }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    @if($product->status == 'available')
                        <span class="badge bg-success">available</span>
                    @else
                        <span class="badge bg-danger">unavailable</span>
                    @endif
                </td>
                <td>
                    <a href="/products/{{ $product->id }}/edit" class="btn btn-sm btn-warning">Edit</a>

                    <form action="/products/{{ $product->id }}" method="POST" style="display:inline;"
                          class="confirm-form"
                          data-title="Hapus Menu"
                          data-message="Yakin hapus menu ini? Data menu akan hilang dari sistem.">
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
    {{ $products->links('pagination::bootstrap-5') }}
</div>

@endsection