<h1>Tambah Meja</h1>

<form action="/tables" method="POST">
    @csrf

    Nomor Meja:
    <input type="text" name="table_number"><br><br>

    Kapasitas:
    <input type="number" name="capacity"><br><br>

    Status:
    <select name="status">
        <option value="available">Available</option>
        <option value="booked">Booked</option>
    </select><br><br>

    <button type="submit">Simpan</button>
</form>