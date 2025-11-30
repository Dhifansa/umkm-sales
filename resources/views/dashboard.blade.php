@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2 opacity-75">Total Produk</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalProducts }}</h2>
                </div>
                <i class="bi bi-box-seam" style="font-size: 3rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2 opacity-75">Total Pelanggan</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalCustomers }}</h2>
                </div>
                <i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2 opacity-75">Penjualan Hari Ini</h6>
                    <h2 class="mb-0 fw-bold">Rp {{ number_format($todaySales, 0, ',', '.') }}</h2>
                </div>
                <i class="bi bi-cash-stack" style="font-size: 3rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2 opacity-75">Penjualan Bulan Ini</h6>
                    <h2 class="mb-0 fw-bold">Rp {{ number_format($monthlySales, 0, ',', '.') }}</h2>
                </div>
                <i class="bi bi-graph-up-arrow" style="font-size: 3rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Grafik Penjualan 7 Hari Terakhir -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4"><i class="bi bi-graph-up"></i> Grafik Penjualan 7 Hari Terakhir</h5>
                <canvas id="salesChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Produk Terlaris -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4"><i class="bi bi-trophy"></i> Top 5 Produk Terlaris</h5>
                @if(count($topProducts) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($topProducts as $index => $product)
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <span class="badge bg-primary rounded-circle me-2">{{ $index + 1 }}</span>
                                {{ $product->name }}
                            </div>
                            <span class="badge bg-success">{{ $product->total_sold }} terjual</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3">Belum ada data penjualan bulan ini</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Stok Produk Rendah -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4"><i class="bi bi-exclamation-triangle text-warning"></i> Produk dengan Stok Rendah (< 10)</h5>
                @if(count($lowStockProducts) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Produk</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $product->code }}</span></td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <span class="badge {{ $product->stock < 5 ? 'bg-danger' : 'bg-warning' }}">
                                            {{ $product->stock }} unit
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">Semua produk memiliki stok yang cukup</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Data untuk chart
    const salesData = @json($salesChart);

    const labels = salesData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
    });

    const data = salesData.map(item => parseFloat(item.total));

    // Buat chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Penjualan (Rp)',
                data: data,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
