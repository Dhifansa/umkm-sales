@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Contoh: Makanan & Minuman"
                               required
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="4"
                                  placeholder="Deskripsi kategori (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jelaskan jenis produk apa yang masuk dalam kategori ini</small>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="card mt-3 border-info">
            <div class="card-body">
                <h6 class="card-title text-info">
                    <i class="bi bi-lightbulb"></i> Tips Kategori
                </h6>
                <ul class="mb-0 small">
                    <li>Gunakan nama yang mudah dipahami</li>
                    <li>Kelompokkan produk sejenis dalam satu kategori</li>
                    <li>Hindari membuat terlalu banyak kategori</li>
                    <li>Contoh: Makanan, Minuman, Elektronik, Fashion, dll</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
