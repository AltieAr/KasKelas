const API_BASE_URL = 'http://kas-kelas.test/api';

document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('api_token');
    if (token) showDashboard();
});

async function handleLogin() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    try {
        const response = await fetch(`${API_BASE_URL}/login-admin`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ email, password })
        });
        const result = await response.json();

        if (response.ok) {
            localStorage.setItem('api_token', result.token);
            showAlert('Login berhasil!', 'success');
            showDashboard();
        } else {
            showAlert(result.message || 'Login gagal', 'danger');
        }
    } catch (error) { showAlert('Gagal terhubung ke server', 'danger'); }
}

async function fetchStudents() {
    const token = localStorage.getItem('api_token');
    try {
        const response = await fetch(`${API_BASE_URL}/students`, {
            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
        });

        if (response.status === 401) return logout();

        const result = await response.json();
        const tableBody = document.querySelector('#studentsTable tbody');
        const selectDropdown = document.querySelector('#studentSelect');

        tableBody.innerHTML = '';
        selectDropdown.innerHTML = '<option value="">-- Pilih Siswa --</option>';

        result.data.forEach(student => {
            tableBody.innerHTML += `
                <tr>
                    <td class="text-center">${student.no_absen}</td>
                    <td>${student.nama}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-info" onclick="cekRekap('${student.id}')">
                            <i class="bi bi-search"></i> Rekap
                        </button>
                    </td>
                </tr>`;
            selectDropdown.innerHTML += `<option value="${student.id}">${student.nama}</option>`;
        });
    } catch (error) { console.error('Error:', error); }
}

// Fungsi Baru: Mengambil daftar periode untuk dropdown
async function fetchPeriods() {
    try {
        const response = await fetch(`${API_BASE_URL}/kas-period`, {
            headers: { 'Accept': 'application/json' }
        });
        const result = await response.json();
        const selectDropdown = document.querySelector('#periodSelect');
        selectDropdown.innerHTML = '<option value="">-- Pilih Periode --</option>';

        result.data.forEach(period => {
            selectDropdown.innerHTML += `<option value="${period.id}">${period.nama_periode}</option>`;
        });
    } catch (error) { console.error('Error:', error); }
}

// Fungsi Baru: Mengecek status pembayaran berdasarkan periode yang dipilih
async function cekStatusPeriode() {
    const periodId = document.getElementById('periodSelect').value;
    if (!periodId) return showAlert('Pilih periode terlebih dahulu!', 'warning');

    try {
        const response = await fetch(`${API_BASE_URL}/periods/${periodId}/status`, {
            headers: { 'Accept': 'application/json' }
        });
        const result = await response.json();

        const tableBody = document.querySelector('#periodStatusTable tbody');
        tableBody.innerHTML = '';

        result.data.laporan_siswa.forEach(siswa => {
            let badgeClass = 'bg-danger';
            if(siswa.status === 'Lunas') badgeClass = 'bg-success';
            else if(siswa.status === 'Menyicil') badgeClass = 'bg-warning text-dark';

            tableBody.innerHTML += `
                <tr>
                    <td class="text-center">${siswa.no_absen}</td>
                    <td>${siswa.nama}</td>
                    <td class="text-end">Rp ${Number(siswa.total_terbayar).toLocaleString('id-ID')}</td>
                    <td class="text-end">Rp ${Number(siswa.sisa_tagihan).toLocaleString('id-ID')}</td>
                    <td class="text-center"><span class="badge ${badgeClass}">${siswa.status}</span></td>
                </tr>`;
        });

        document.getElementById('periodStatusArea').classList.remove('d-none');
        document.getElementById('periodTitle').innerText =
            `Status Pembayaran: ${result.data.periode.nama_periode} (Tagihan: Rp ${Number(result.data.periode.nominal_tagihan).toLocaleString('id-ID')})`;

    } catch (error) { console.error('Error:', error); }
}

async function submitTransaction() {
    const student_id = document.getElementById('studentSelect').value;
    const nominal = document.getElementById('nominal').value;

    if (!student_id || !nominal) return showAlert('Isi data lengkap!', 'warning');

    try {
        const response = await fetch(`${API_BASE_URL}/transaction`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ student_id, nominal })
        });

        if (response.ok) {
            showAlert('Pembayaran dialokasikan otomatis!', 'success');
            document.getElementById('nominal').value = '';
        } else {
            showAlert('Gagal menyimpan', 'danger');
        }
    } catch (error) { console.error('Error:', error); }
}

async function cekRekap(studentId) {
    try {
        const response = await fetch(`${API_BASE_URL}/students/${studentId}/kas`, {
            headers: { 'Accept': 'application/json' }
        });
        const result = await response.json();

        // Memasukkan nama siswa ke judul tabel
        document.getElementById('rekapNamaSiswa').innerText = result.data.siswa.nama;

        const tableBody = document.querySelector('#rekapTable tbody');
        tableBody.innerHTML = ''; // Kosongkan isi tabel sebelumnya

        // Looping data tagihan siswa
        result.data.ringkasan_tagihan.forEach(tagihan => {
            let badgeClass = 'bg-danger';
            if (tagihan.status === 'Lunas') badgeClass = 'bg-success';
            else if (tagihan.status === 'Menyicil') badgeClass = 'bg-warning text-dark';

            tableBody.innerHTML += `
                <tr>
                    <td>${tagihan.nama_periode}</td>
                    <td>${tagihan.jatuh_tempo}</td>
                    <td class="text-end">Rp ${Number(tagihan.nominal_tagihan).toLocaleString('id-ID')}</td>
                    <td class="text-end">Rp ${Number(tagihan.total_terbayar).toLocaleString('id-ID')}</td>
                    <td class="text-end">Rp ${Number(tagihan.sisa_tagihan).toLocaleString('id-ID')}</td>
                    <td class="text-center"><span class="badge ${badgeClass}">${tagihan.status}</span></td>
                </tr>`;
        });

        const area = document.getElementById('rekapArea');
        area.classList.remove('d-none');

        // Auto-scroll ke bagian tabel biar nggak perlu scroll manual
        area.scrollIntoView({ behavior: 'smooth' });

    } catch (error) {
        console.error('Error fetching rekap:', error);
    }
}

function showDashboard() {
    document.getElementById('loginSection').classList.add('d-none');
    document.getElementById('btnLogout').classList.remove('d-none');
    document.getElementById('dashboardSection').classList.remove('d-none');
    fetchStudents();
    fetchPeriods(); // Otomatis isi dropdown saat masuk dashboard
}

function logout() {
    localStorage.removeItem('api_token');
    document.getElementById('dashboardSection').classList.add('d-none');
    document.getElementById('btnLogout').classList.add('d-none');
    document.getElementById('loginSection').classList.remove('d-none');
    document.getElementById('rekapArea').classList.add('d-none');
    document.getElementById('periodStatusArea').classList.add('d-none');
    showAlert('Berhasil logout', 'success');
}

function showAlert(message, type) {
    const alertBox = document.getElementById('alertBox');
    alertBox.className = `alert alert-${type} mt-3`;
    alertBox.textContent = message;
    alertBox.classList.remove('d-none');
    setTimeout(() => alertBox.classList.add('d-none'), 3000);
}
