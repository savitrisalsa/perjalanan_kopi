<!DOCTYPE html>
<html>
<head>
    <title>Perjalanan Kopi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .container {
                margin-top: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark no-print">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/dashboard">☕ Perjalanan Kopi</a>

        <div>
            <a href="/dashboard" class="btn btn-sm btn-outline-light">Dashboard</a>
            <a href="/products" class="btn btn-sm btn-outline-light">Menu</a>
            <a href="/orders" class="btn btn-sm btn-outline-light">Order</a>
            <a href="/reports" class="btn btn-sm btn-outline-light">Laporan</a>
            <a href="/profile" class="btn btn-sm btn-outline-light">Profil</a>

            <form action="/logout" method="POST" style="display:inline;" class="confirm-form"
                  data-title="Konfirmasi Logout"
                  data-message="Yakin ingin keluar dari sistem?">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5">
    @if(session('success'))
        <div class="alert alert-success no-print">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger no-print">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger no-print">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @yield('content')
</div>

<div class="modal fade no-print" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="confirmModalTitle">Konfirmasi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p id="confirmModalMessage" class="mb-0">Apakah kamu yakin?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmModalButton">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let selectedForm = null;

    document.querySelectorAll('.confirm-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            selectedForm = form;

            document.getElementById('confirmModalTitle').innerText = form.getAttribute('data-title') || 'Konfirmasi';
            document.getElementById('confirmModalMessage').innerText = form.getAttribute('data-message') || 'Apakah kamu yakin ingin melanjutkan?';

            let modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();
        });
    });

    document.getElementById('confirmModalButton').addEventListener('click', function() {
        if (selectedForm) {
            selectedForm.submit();
        }
    });
</script>

@yield('script')

</body>
</html>