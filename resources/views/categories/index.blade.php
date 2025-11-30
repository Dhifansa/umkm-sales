@extends('layouts.app')

@section('title', 'Daftar Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0"><i class="bi bi-tags"></i> Daftar Kategori</h5>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </a>
        </div>

        <div class="row">
            @forelse($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title">{{ $category->name }}</h5>
                                <span class="badge bg-info">{{ $category->product_count }} Produk</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('categories.edit', $category->id) }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('categories.destroy', $category->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @if($category->description)
                        <p class="card-text text-muted">{{ Str::limit($category->description, 100) }}</p>
                        @else
                        <p class="card-text text-muted fst-italic">Tidak ada deskripsi</p>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> Dibuat: {{ date('d/m/Y', strtotime($category->created_at)) }}
                        </small>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 4rem;"></i>
                    <h5 class="mt-3">Belum ada kategori</h5>
                    <p>Mulai dengan menambahkan kategori produk pertama Anda</p>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Kategori
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
