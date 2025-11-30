@extends('layouts.app')

@section('title', 'Daftar Pelanggan')
@section('page-title', 'Manajemen Pelanggan')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0"><i class="bi bi-people"></i> Daftar Pelanggan</h5>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Tambah Pelanggan
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Pelanggan</th>
                        <th width="15%">No Telepon</th>
                        <th width="20%">Email</th>
                        <th width="15%">Total Transaksi</th>
                        <th width="15%">Total Belanja</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $index => $customer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary text-white me-2"
                                     style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <strong>{{ strtoupper(substr($customer->name, 0, 1)) }}</strong>
                                </div>
                                <div>
                                    <strong>{{ $customer->name }}</strong>
                                    @if($customer->address)
                                    <br><small class="text-muted">{{ Str::limit($customer->address, 30) }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($customer->phone)
                                <i class="bi bi-telephone"></i> {{ $customer->phone }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($customer->email)
                                <i class="bi bi-envelope"></i> {{ $customer->email }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $customer->total_transactions }} transaksi</span>
                        </td>
                        <td class="fw-bold text-success">
                            Rp {{ number_format($customer->total_spending, 0, ',', '.') }}
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('customers.edit', $customer->id) }}"
                                   class="btn btn-sm btn-warning"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people" style="font-size: 3rem;"></i><br>
                            Belum ada data pelanggan. <a href="{{ route('customers.create') }}">Tambah pelanggan baru</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Customer Stats -->
@if(count($customers) > 0)
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ count($customers) }}</h3>
                <p class="mb-0">Total Pelanggan</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ array_sum(array_column($customers, 'total_transactions')) }}</h3>
                <p class="mb-0">Total Transaksi</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-info">Rp {{ number_format(array_sum(array_column($customers, 'total_spending')), 0, ',', '.') }}</h3>
                <p class="mb-0">Total Belanja</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
