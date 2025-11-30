@extends('layouts.app')

@section('title', 'Daftar Transaksi')
@section('page-title', 'Riwayat Transaksi Penjualan')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">
                <i class="bi bi-receipt"></i> Daftar Transaksi
            </h5>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Transaksi Baru (POS)
            </a>
        </div>

        <!-- Filter Section -->
        <div class="card mb-3 bg-light">
            <div class="card-body">
                <form method="GET" action="{{ route('sales.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar"></i> Dari Tanggal
                        </label>
                        <input type="date"
                               name="date_from"
                               class="form-control"
                               value="{{ $dateFrom }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-check"></i> Sampai Tanggal
                        </label>
                        <input type="date"
                               name="date_to"
                               class="form-control"
                               value="{{ $dateTo }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Aksi</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel"></i> Tampilkan
                            </button>
                            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        @if(count($sales) > 0)
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 opacity-75">Total Transaksi</h6>
                                <h3 class="mb-0 fw-bold">{{ count($sales) }}</h3>
                            </div>
                            <i class="bi bi-receipt" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 opacity-75">Total Penjualan</h6>
                                <h3 class="mb-0 fw-bold">
                                    Rp {{ number_format(array_sum(array_column($sales, 'total')), 0, ',', '.') }}
                                </h3>
                            </div>
                            <i class="bi bi-cash-stack" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 opacity-75">Rata-rata Transaksi</h6>
                                <h3 class="mb-0 fw-bold">
                                    Rp {{ count($sales) > 0 ? number_format(array_sum(array_column($sales, 'total')) / count($sales), 0, ',', '.') : 0 }}
                                </h3>
                            </div>
                            <i class="bi bi-calculator" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">
                            <i class="bi bi-receipt"></i> No Invoice
                        </th>
                        <th width="15%">
                            <i class="bi bi-calendar"></i> Tanggal
                        </th>
                        <th>
                            <i class="bi bi-person"></i> Pelanggan
                        </th>
                        <th>
                            <i class="bi bi-person-badge"></i> Kasir
                        </th>
                        <th width="10%" class="text-center">
                            <i class="bi bi-box"></i> Item
                        </th>
                        <th width="15%" class="text-end">
                            <i class="bi bi-cash"></i> Total
                        </th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $index => $sale)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="badge bg-primary">
                                {{ $sale->invoice_number }}
                            </span>
                        </td>
                        <td>
                            <div>
                                <i class="bi bi-calendar3"></i>
                                {{ date('d/m/Y', strtotime($sale->sale_date)) }}
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i>
                                {{ date('H:i', strtotime($sale->sale_date)) }} WIB
                            </small>
                        </td>
                        <td>
                            @if($sale->customer_name)
                                <strong>{{ $sale->customer_name }}</strong>
                            @else
                                <span class="text-muted">
                                    <i class="bi bi-person"></i> Umum
                                </span>
                            @endif
                        </td>
                        <td>
                            <i class="bi bi-person-circle"></i> {{ $sale->user_name }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">
                                {{ $sale->total_items }} item
                            </span>
                        </td>
                        <td class="text-end">
                            <strong class="text-success">
                                Rp {{ number_format($sale->total, 0, ',', '.') }}
                            </strong>
                            @if($sale->discount > 0)
                            <br>
                            <small class="text-muted">
                                <i class="bi bi-tag"></i>
                                Diskon: Rp {{ number_format($sale->discount, 0, ',', '.') }}
                            </small>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('sales.show', $sale->id) }}"
                               class="btn btn-sm btn-info"
                               title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">Belum Ada Transaksi</h5>
                                <p>Tidak ada transaksi dalam periode {{ date('d/m/Y', strtotime($dateFrom)) }} - {{ date('d/m/Y', strtotime($dateTo)) }}</p>
                                <a href="{{ route('sales.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Buat Transaksi Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if(count($sales) > 0)
                <tfoot class="table-light">
                    <tr>
                        <th colspan="6" class="text-end">Total Penjualan:</th>
                        <th class="text-end text-success">
                            Rp {{ number_format(array_sum(array_column($sales, 'total')), 0, ',', '.') }}
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <!-- Info Card -->
        @if(count($sales) > 0)
        <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle"></i>
            <strong>Info:</strong>
            Menampilkan {{ count($sales) }} transaksi dari periode
            <strong>{{ date('d F Y', strtotime($dateFrom)) }}</strong>
            sampai
            <strong>{{ date('d F Y', strtotime($dateTo)) }}</strong>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh every 30 seconds (optional)
// setInterval(function() {
//     location.reload();
// }, 30000);

// Set default dates to today if empty
document.addEventListener('DOMContentLoaded', function() {
    const dateFrom = document.querySelector('input[name="date_from"]');
    const dateTo = document.querySelector('input[name="date_to"]');

    if (!dateFrom.value) {
        // Set to first day of current month
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        dateFrom.value = firstDay.toISOString().split('T')[0];
    }

    if (!dateTo.value) {
        // Set to today
        const today = new Date();
        dateTo.value = today.toISOString().split('T')[0];
    }
});
</script>
@endpush
