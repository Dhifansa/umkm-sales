@extends('layouts.app')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@push('styles')
<style>
    @media print {
        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            font-size: 12px;
            margin: 10px;
        }

        .sidebar, .navbar-custom, .btn, .no-print, .card-header {
            display: none !important;
        }

        .main-content {
            padding: 0 !important;
            margin: 0 !important;
        }

        .card {
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
        }

        .card-body {
            padding: 10px !important;
        }

        .table {
            font-size: 11px;
        }

        h5, h6 {
            font-size: 14px;
            margin: 5px 0;
        }

        .print-header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .print-footer {
            text-align: center;
            border-top: 2px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt"></i> Detail Transaksi
                    </h5>
                    <div>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="bi bi-printer"></i> Cetak Struk
                        </button>
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <!-- Print Header (Only visible when printing) -->
                <div class="print-header d-none d-print-block">
                    <h4 class="mb-1">TOKO UMKM</h4>
                    <p class="mb-0" style="font-size: 10px;">
                        Jl. Contoh No. 123, Kota<br>
                        Telp: 0812-3456-7890
                    </p>
                </div>

                <!-- Invoice Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%"><strong>No Invoice:</strong></td>
                                <td>
                                    <span class="badge bg-primary fs-6">{{ $sale->invoice_number }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>{{ date('d F Y, H:i', strtotime($sale->sale_date)) }} WIB</td>
                            </tr>
                            <tr>
                                <td><strong>Kasir:</strong></td>
                                <td>{{ $sale->user_name }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%"><strong>Pelanggan:</strong></td>
                                <td>{{ $sale->customer_name ?? 'Umum' }}</td>
                            </tr>
                            @if($sale->customer_phone)
                            <tr>
                                <td><strong>No Telepon:</strong></td>
                                <td>{{ $sale->customer_phone }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Lunas
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <!-- Items Table -->
                <h6 class="mb-3"><i class="bi bi-basket"></i> Detail Produk</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="15%">Kode</th>
                                <th>Nama Produk</th>
                                <th width="10%" class="text-center">Qty</th>
                                <th width="15%" class="text-end">Harga</th>
                                <th width="15%" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item->product_code }}</span>
                                </td>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $item->quantity }}</span>
                                </td>
                                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total Item:</strong></td>
                                <td class="text-end">
                                    <span class="badge bg-primary">{{ count($items) }} item</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Total Section -->
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="card bg-light">
                            <div class="card-body">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pajak:</strong></td>
                                        <td class="text-end">Rp {{ number_format($sale->tax, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($sale->discount > 0)
                                    <tr>
                                        <td><strong>Diskon:</strong></td>
                                        <td class="text-end text-danger">
                                            - Rp {{ number_format($sale->discount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endif
                                    <tr class="border-top">
                                        <td><strong class="fs-5">TOTAL:</strong></td>
                                        <td class="text-end">
                                            <strong class="fs-5 text-success">
                                                Rp {{ number_format($sale->total, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr class="table-light">
                                        <td><strong>Jumlah Bayar:</strong></td>
                                        <td class="text-end">Rp {{ number_format($sale->paid, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td><strong>Kembalian:</strong></td>
                                        <td class="text-end">
                                            <strong class="text-success">
                                                Rp {{ number_format($sale->change_amount, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Print Footer (Only visible when printing) -->
                <div class="print-footer d-none d-print-block mt-4">
                    <p class="mb-1" style="font-size: 11px;">
                        Terima kasih atas kunjungan Anda!<br>
                        Barang yang sudah dibeli tidak dapat dikembalikan
                    </p>
                    <p class="mb-0" style="font-size: 10px;">
                        Dicetak: {{ date('d/m/Y H:i') }}
                    </p>
                </div>

                <!-- Additional Info (Not printed) -->
                <div class="mt-4 no-print">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Informasi:</strong> Transaksi ini telah selesai dan tidak dapat diubah.
                        Untuk pembatalan atau pengembalian barang, hubungi administrator.
                    </div>
                </div>

                <!-- Action Buttons Bottom -->
                <div class="d-flex justify-content-between mt-4 no-print">
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Transaksi
                    </a>
                    <div>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="bi bi-printer"></i> Cetak Ulang
                        </button>
                        <a href="{{ route('sales.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Transaksi Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Timeline (Optional) -->
        <div class="card mt-3 no-print">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-clock-history"></i> Riwayat Transaksi
                </h6>
                <div class="timeline">
                    <div class="d-flex mb-2">
                        <div class="me-3">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                        <div>
                            <strong>Transaksi Selesai</strong><br>
                            <small class="text-muted">
                                {{ date('d F Y, H:i', strtotime($sale->sale_date)) }}
                            </small>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="bi bi-receipt text-primary"></i>
                        </div>
                        <div>
                            <strong>Invoice Dibuat</strong><br>
                            <small class="text-muted">
                                {{ date('d F Y, H:i', strtotime($sale->created_at)) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto print (optional - uncomment if needed)
// window.onload = function() {
//     if (window.location.hash === '#print') {
//         window.print();
//     }
// }

// Format numbers for print
document.addEventListener('DOMContentLoaded', function() {
    // Any additional JavaScript for the page
    console.log('Detail transaksi loaded');
});
</script>
@endpush
