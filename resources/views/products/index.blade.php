@extends('layouts.app')

@section('title', 'Daftar Produk')
@section('page-title', 'Manajemen Produk')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0"><i class="bi bi-box-seam"></i> Daftar Produk</h5>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Produk
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th width="12%">Harga</th>
                        <th width="8%">Stok</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $product->code }}</span></td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            @if($product->description)
                            <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $product->category_name ?? 'Tanpa Kategori' }}</span>
                        </td>
                        <td class="fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $product->stock < 10 ? 'bg-danger' : 'bg-success' }}">
                                {{ $product->stock }} unit
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i><br>
                            Belum ada produk. <a href="{{ route('products.create') }}">Tambah produk baru</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
