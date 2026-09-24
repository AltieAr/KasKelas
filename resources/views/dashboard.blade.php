<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super App Kelas - Bendahara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Mengarah ke folder public/style.css -->
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"><i class="bi bi-wallet2"></i> Portal Bendahara Kelas</h2>
        <button id="btnLogout" onclick="logout()" class="btn btn-outline-danger d-none">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </div>

    <div id="alertBox" class="alert d-none" role="alert"></div>

    <!-- SECTION LOGIN -->
    <div id="loginSection" class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h4 class="text-center mb-4">Masuk sebagai Admin</h4>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email address</label>
                    <input type="email" id="email" class="form-control" value="admin@kelas.com">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" id="password" class="form-control" value="password">
                </div>
                <button onclick="handleLogin()" class="btn btn-primary w-100 fw-bold">Login API</button>
            </div>
        </div>
    </div>

    <!-- SECTION DASHBOARD -->
    <div id="dashboardSection" class="d-none">

        <!-- Baris Atas: Input Kas & Daftar Siswa -->
        <div class="row g-4">
            <!-- Form Input Kas -->
            <div class="col-lg-4">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle text-success"></i> Input Pembayaran</h5>
                    <div class="mb-3">
                        <label class="form-label">Pilih Siswa</label>
                        <select id="studentSelect" class="form-select">
                            <option value="">-- Memuat data... --</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" id="nominal" class="form-control" placeholder="15000">
                    </div>
                    <button onclick="submitTransaction()" class="btn btn-success w-100 fw-bold">
                        Simpan Pembayaran
                    </button>
                </div>
            </div>

            <!-- Tabel Data Siswa -->
            <div class="col-lg-8">
                <div class="card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold"><i class="bi bi-people-fill text-primary"></i> Daftar Siswa</h5>
                        <button onclick="fetchStudents()" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Refresh Data
                        </button>
                    </div>
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-hover table-bordered table-custom" id="studentsTable">
                            <thead class="sticky-top">
                                <tr>
                                    <th width="10%" class="text-center">Absen</th>
                                    <th width="60%">Nama Siswa</th>
                                    <th width="30%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris Bawah: Laporan Periode (Fitur Baru) -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold"><i class="bi bi-calendar-check text-info"></i> Cek Status Pembayaran per Periode</h5>
                        <div class="d-flex gap-2">
                            <select id="periodSelect" class="form-select w-auto">
                                <option value="">-- Memuat Periode... --</option>
                            </select>
                            <button onclick="cekStatusPeriode()" class="btn btn-info text-white fw-bold">Cek Status</button>
                        </div>
                    </div>

                    <!-- Area Hasil Cek Periode -->
                    <div id="periodStatusArea" class="d-none mt-3">
                        <h6 id="periodTitle" class="fw-bold text-success mb-3"></h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="periodStatusTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">Absen</th>
                                        <th>Nama Siswa</th>
                                        <th class="text-end">Uang Masuk</th>
                                        <th class="text-end">Sisa Tagihan</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Area Rekap 1 Siswa -->
<div id="rekapArea" class="mt-4 d-none">
    <div class="card p-4 border-warning shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-receipt text-warning"></i> Rincian Tagihan:
                <span id="rekapNamaSiswa" class="text-primary"></span>
            </h5>
            <button class="btn-close" onclick="document.getElementById('rekapArea').classList.add('d-none')"></button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="rekapTable">
                <thead class="table-warning">
                    <tr>
                        <th>Periode</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-end">Tagihan</th>
                        <th class="text-end">Terbayar</th>
                        <th class="text-end">Sisa</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data disuntikkan dari JS -->
                </tbody>
            </table>
        </div>
    </div>
</div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Mengarah ke folder public/app.js -->
<script src="{{ asset('app.js') }}"></script>
</body>
</html>
