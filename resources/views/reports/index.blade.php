@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan & Analisis Penjualan')

@push('styles')
<style>
    .report-card {
        border-left: 4px solid;
        transition: all 0.3s;
    }
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .chart-container {
        position: relative;
        height: 400px;
    }
</style>
@endpush

@section('content')
<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control"
                       value="{{ request('date_from', date('Y-m-01')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control"
                       value="{{ request('date_to', date('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Periode Cepat</label>
                <select class="form-select" id="quickPeriod">
                    <option value="">Pilih Periode</option>
                    <option value="today">Hari Ini</option>
                    <option value="yesterday">Kemarin</option>
                    <option value="this_week">Minggu Ini</option>
                    <option value="this_month">Bulan Ini</option>
                    <option value="last_month">Bulan Lalu</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Tampilkan Laporan
                    </button>
                    <button type="button" onclick="exportPDF()" class="btn btn-danger">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card report-card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2">Total Penjualan</h6>
                        <h3 class="text-primary mb-0">Rp 15,450,000</h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> 12.5% vs periode lalu</small>
                    </div>
                    <i class="bi bi-cash-stack text-primary" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card report-card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2">Total Transaksi</h6>
                        <h3 class="text-success mb-0">245</h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> 8.3% vs periode lalu</small>
                    </div>
                    <i class="bi bi-receipt text-success" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card report-card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2">Rata-rata Transaksi</h6>
                        <h3 class="text-warning mb-0">Rp 63,061</h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> 4.2% vs periode lalu</small>
                    </div>
                    <i class="bi bi-calculator text-warning" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card report-card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2">Produk Terjual</h6>
                        <h3 class="text-info mb-0">1,234</h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> 15.7% vs periode lalu</small>
                    </div>
                    <i class="bi bi-box-seam text-info" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-graph-up"></i> Tren Penjualan Harian
                </h5>
                <div class="chart-container">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-pie-chart"></i> Penjualan per Kategori
                </h5>
                <div class="chart-container">
                    <canvas id="categoryPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Products -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-trophy"></i> Produk Terlaris
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="10%">#</th>
                                <th>Produk</th>
                                <th width="20%">Terjual</th>
                                <th width="25%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-warning">1</span></td>
                                <td>Nasi Goreng Spesial</td>
                                <td><span class="badge bg-success">156 unit</span></td>
                                <td class="fw-bold">Rp 2,340,000</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">2</span></td>
                                <td>Mie Ayam Bakso</td>
                                <td><span class="badge bg-success">142 unit</span></td>
                                <td class="fw-bold">Rp 1,704,000</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">3</span></td>
                                <td>Es Teh Manis</td>
                                <td><span class="badge bg-success">198 unit</span></td>
                                <td class="fw-bold">Rp 594,000</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">4</span></td>
                                <td>Sate Ayam</td>
                                <td><span class="badge bg-success">89 unit</span></td>
                                <td class="fw-bold">Rp 1,335,000</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">5</span></td>
                                <td>Kopi Susu</td>
                                <td><span class="badge bg-success">134 unit</span></td>
                                <td class="fw-bold">Rp 536,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-people"></i> Pelanggan Teratas
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="10%">#</th>
                                <th>Nama Pelanggan</th>
                                <th width="20%">Transaksi</th>
                                <th width="30%">Total Belanja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-warning">1</span></td>
                                <td>Budi Santoso</td>
                                <td><span class="badge bg-info">23x</span></td>
                                <td class="fw-bold">Rp 3,450,000</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">2</span></td>
                                <td>Siti Aminah</td>
                                <td><span class="badge bg-info">19x</span></td>
                                <td class="fw-bold">Rp 2,850,000</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">3</span></td>
                                <td>Ahmad Fadli</td>
                                <td><span class="badge bg-info">17x</span></td>
                                <td class="fw-bold">Rp 2,380,000</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">4</span></td>
                                <td>Dewi Lestari</td>
                                <td><span class="badge bg-info">15x</span></td>
                                <td class="fw-bold">Rp 2,100,000</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">5</span></td>
                                <td>Rudi Hermawan</td>
                                <td><span class="badge bg-info">14x</span></td>
                                <td class="fw-bold">Rp 1,960,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Methods -->
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-credit-card"></i> Metode Pembayaran
                </h5>
                <div class="chart-container" style="height: 250px;">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-clock-history"></i> Waktu Transaksi Tersibuk
                </h5>
                <div class="chart-container" style="height: 250px;">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Quick Period Selection
$('#quickPeriod').change(function() {
    const today = new Date();
    let dateFrom, dateTo;

    switch($(this).val()) {
        case 'today':
            dateFrom = dateTo = today.toISOString().split('T')[0];
            break;
        case 'yesterday':
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            dateFrom = dateTo = yesterday.toISOString().split('T')[0];
            break;
        case 'this_week':
            const firstDay = new Date(today.setDate(today.getDate() - today.getDay()));
            dateFrom = firstDay.toISOString().split('T')[0];
            dateTo = new Date().toISOString().split('T')[0];
            break;
        case 'this_month':
            dateFrom = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
            dateTo = new Date().toISOString().split('T')[0];
            break;
        case 'last_month':
            const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            dateFrom = lastMonth.toISOString().split('T')[0];
            dateTo = new Date(today.getFullYear(), today.getMonth(), 0).toISOString().split('T')[0];
            break;
    }

    if(dateFrom && dateTo) {
        $('input[name="date_from"]').val(dateFrom);
        $('input[name="date_to"]').val(dateTo);
    }
});

// Sales Trend Chart
const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [{
            label: 'Penjualan',
            data: [1200000, 1900000, 1500000, 2100000, 2400000, 2800000, 2200000],
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        }
    }
});

// Category Pie Chart
const categoryCtx = document.getElementById('categoryPieChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: ['Makanan', 'Minuman', 'Snack'],
        datasets: [{
            data: [6500000, 4200000, 4750000],
            backgroundColor: ['#667eea', '#38ef7d', '#f5576c']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Payment Method Chart
const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
new Chart(paymentCtx, {
    type: 'bar',
    data: {
        labels: ['Cash', 'Debit', 'QRIS', 'E-Wallet'],
        datasets: [{
            label: 'Jumlah',
            data: [8500000, 3200000, 2100000, 1650000],
            backgroundColor: ['#667eea', '#38ef7d', '#f5576c', '#4facfe']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
    }
});

// Hourly Chart
const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
new Chart(hourlyCtx, {
    type: 'bar',
    data: {
        labels: ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00'],
        datasets: [{
            label: 'Transaksi',
            data: [5, 12, 18, 25, 45, 38, 22, 28, 35, 42, 48, 32],
            backgroundColor: '#667eea'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
    }
});

function exportPDF() {
    alert('Fitur export PDF akan segera tersedia');
}
</script>
@endpush
