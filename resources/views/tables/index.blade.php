<h1>Daftar Meja</h1>

<a href="/tables/create">Tambah Meja</a>

<table border="1">
    <tr>
        <th>No</th>
        <th>Nomor Meja</th>
        <th>Kapasitas</th>
        <th>Status</th>
    </tr>

    @foreach($tables as $t)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $t->table_number }}</td>
        <td>{{ $t->capacity }}</td>
        <td>{{ $t->status }}</td>
    </tr>
    @endforeach
</table>