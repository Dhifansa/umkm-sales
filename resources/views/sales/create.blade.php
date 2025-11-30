@extends('layouts.app')

@section('title', 'Kasir / POS')
@section('page-title', 'Point of Sale (Kasir)')

@push('styles')
<style>
    .product-card {
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
        height: 100%;
    }
    .product-card:hover {
        border-color: #667eea;
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }
    .cart-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 10px;
        transition: all 0.3s;
    }
    .cart-item:hover {
        background: #e9ecef;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .total-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        position: sticky;
        top: 20px;
    }
    .btn-number {
        width: 35px;
        height: 35px;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .search-product {
        border-radius: 25px;
        padding: 12px 20px;
        border: 2px solid #e9ecef;
    }
    .search-product:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .product-grid {
        max-height: 600px;
        overflow-y: auto;
    }
    .cart-section {
        max-height: 400px;
        overflow-y: auto;
    }
    .empty-cart {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }
    .product-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <!-- Product Section -->
    <div class="col-lg-7 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="input-group mb-3">
                    <span class="input-group-text border-0 bg-white">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                           id="searchProduct"
                           class="form-control search-product border-start-0"
                           placeholder="Cari produk berdasarkan nama atau kode..."
                           autofocus>
                </div>
            </div>
        </div>

        <div id="productList" class="product-grid mt-3">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat produk...</p>
            </div>
        </div>
    </div>

    <!-- Cart Section -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-cart3"></i> Keranjang Belanja
                </h5>

                <!-- Customer Selection -->
                <div class="mb-3">
                    <label class="form-label">Pelanggan (Opsional)</label>
                    <select id="customerId" class="form-select">
                        <option value="">Umum / Walk-in Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }} - {{ $customer->phone ?? 'No Phone' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>

                <!-- Cart Items -->
                <div id="cartItems" class="cart-section mb-3">
                    <div class="empty-cart">
                        <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                        <p class="mt-2">Keranjang masih kosong</p>
                        <small class="text-muted">Cari dan klik produk untuk menambahkan</small>
                    </div>
                </div>

                <hr>

                <!-- Total Section -->
                <div class="total-section">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span class="fw-bold" id="subtotalDisplay">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Pajak (0%):</span>
                        <span id="taxDisplay">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Diskon:</span>
                        <div class="input-group" style="width: 150px;">
                            <span class="input-group-text bg-white bg-opacity-25 border-0 text-white">Rp</span>
                            <input type="number"
                                   id="discountInput"
                                   class="form-control text-white bg-white bg-opacity-25 border-0 text-end"
                                   value="0"
                                   min="0"
                                   placeholder="0">
                        </div>
                    </div>
                    <hr class="border-white">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">TOTAL:</h5>
                        <h4 class="fw-bold mb-0" id="totalDisplay">Rp 0</h4>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Bayar:</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white bg-opacity-25 border-0 text-white">Rp</span>
                            <input type="number"
                                   id="paidInput"
                                   class="form-control form-control-lg text-white bg-white bg-opacity-25 border-0 fw-bold"
                                   placeholder="0"
                                   min="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kembalian:</label>
                        <h4 class="fw-bold" id="changeDisplay">Rp 0</h4>
                    </div>

                    <div class="d-grid gap-2">
                        <button id="processBtn" class="btn btn-light btn-lg fw-bold" disabled>
                            <i class="bi bi-check-circle"></i> Proses Transaksi
                        </button>
                        <button id="resetBtn" class="btn btn-outline-light">
                            <i class="bi bi-arrow-clockwise"></i> Reset Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Success -->
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                <h3 class="mt-3">Transaksi Berhasil!</h3>
                <p class="text-muted mb-1">Invoice: <span id="invoiceNumber" class="fw-bold"></span></p>
                <p class="text-muted">Tanggal: <span id="saleDate" class="fw-bold"></span></p>

                <div class="my-4">
                    <h5>Total: <span id="modalTotal" class="text-success"></span></h5>
                    <p>Bayar: <span id="modalPaid"></span></p>
                    <p>Kembalian: <span id="modalChange" class="text-primary fw-bold"></span></p>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <a href="#" id="printBtn" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Cetak Struk
                    </a>
                    <button class="btn btn-success" onclick="newTransaction()">
                        <i class="bi bi-plus-circle"></i> Transaksi Baru
                    </button>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                        <i class="bi bi-list"></i> Lihat Semua Transaksi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let cart = [];
let products = [];

$(document).ready(function() {
    loadProducts();

    // Search products
    let searchTimeout;
    $('#searchProduct').on('input', function() {
        clearTimeout(searchTimeout);
        const keyword = $(this).val();

        searchTimeout = setTimeout(function() {
            if (keyword.length >= 2) {
                searchProducts(keyword);
            } else if (keyword.length === 0) {
                displayProducts(products);
            }
        }, 300);
    });

    // Discount calculation
    $('#discountInput').on('input', calculateTotal);

    // Paid calculation
    $('#paidInput').on('input', function() {
        calculateTotal();
        const total = parseFloat($('#totalDisplay').data('total')) || 0;
        const paid = parseFloat($(this).val()) || 0;

        if (paid >= total && cart.length > 0 && total > 0) {
            $('#processBtn').prop('disabled', false);
        } else {
            $('#processBtn').prop('disabled', true);
        }
    });

    // Process transaction
    $('#processBtn').click(processTransaction);

    // Reset cart
    $('#resetBtn').click(function() {
        if (cart.length > 0) {
            if (confirm('Yakin ingin reset keranjang?')) {
                resetCart();
            }
        }
    });
});

function loadProducts() {
    $.get('{{ route("products.search") }}?q=', function(data) {
        products = data;
        displayProducts(data);
    }).fail(function() {
        $('#productList').html(`
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Gagal memuat produk. Refresh halaman.
            </div>
        `);
    });
}

function searchProducts(keyword) {
    $.get('{{ route("products.search") }}?q=' + encodeURIComponent(keyword), function(data) {
        displayProducts(data);
    });
}

function displayProducts(productList) {
    if (productList.length === 0) {
        $('#productList').html(`
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-2">Produk tidak ditemukan</p>
            </div>
        `);
        return;
    }

    const html = '<div class="row g-3">' + productList.map(product => `
        <div class="col-md-6">
            <div class="card product-card" onclick="addToCart(${product.id})">
                <div class="card-body text-center">
                    ${product.image ?
                        `<img src="/storage/${product.image}" class="product-image" alt="${product.name}">` :
                        `<i class="bi bi-box-seam text-primary" style="font-size: 2.5rem;"></i>`
                    }
                    <h6 class="mt-2 mb-1">${product.name}</h6>
                    <small class="text-muted">${product.code}</small>
                    <p class="fw-bold mb-1 mt-2 text-success">Rp ${formatNumber(product.price)}</p>
                    <small class="badge ${product.stock > 10 ? 'bg-success' : product.stock > 0 ? 'bg-warning' : 'bg-danger'}">
                        Stok: ${product.stock}
                    </small>
                </div>
            </div>
        </div>
    `).join('') + '</div>';

    $('#productList').html(html);
}

function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (!product || product.stock <= 0) {
        alert('Stok produk tidak tersedia');
        return;
    }

    const existingItem = cart.find(item => item.product_id === productId);

    if (existingItem) {
        if (existingItem.quantity >= product.stock) {
            alert('Stok tidak mencukupi! Stok tersedia: ' + product.stock);
            return;
        }
        existingItem.quantity++;
    } else {
        cart.push({
            product_id: product.id,
            name: product.name,
            code: product.code,
            price: parseFloat(product.price),
            quantity: 1,
            stock: product.stock
        });
    }

    updateCart();

    // Flash effect
    $('#cartItems').addClass('border border-success');
    setTimeout(() => $('#cartItems').removeClass('border border-success'), 500);
}

function updateCart() {
    if (cart.length === 0) {
        $('#cartItems').html(`
            <div class="empty-cart">
                <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                <p class="mt-2">Keranjang masih kosong</p>
                <small class="text-muted">Cari dan klik produk untuk menambahkan</small>
            </div>
        `);
    } else {
        const html = cart.map((item, index) => `
            <div class="cart-item">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>${item.name}</strong><br>
                        <small class="text-muted">${item.code}</small>
                    </div>
                    <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-secondary btn-number" onclick="decreaseQuantity(${index})">
                            <i class="bi bi-dash"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" disabled style="min-width: 50px;">
                            ${item.quantity}
                        </button>
                        <button class="btn btn-sm btn-primary btn-number" onclick="increaseQuantity(${index})">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">@ Rp ${formatNumber(item.price)}</small>
                        <strong class="text-success">Rp ${formatNumber(item.price * item.quantity)}</strong>
                    </div>
                </div>
            </div>
        `).join('');

        $('#cartItems').html(html);
    }

    calculateTotal();
}

function increaseQuantity(index) {
    if (cart[index].quantity < cart[index].stock) {
        cart[index].quantity++;
        updateCart();
    } else {
        alert('Stok tidak mencukupi! Maksimal: ' + cart[index].stock);
    }
}

function decreaseQuantity(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity--;
        updateCart();
    }
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

function calculateTotal() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = 0;
    const discount = parseFloat($('#discountInput').val()) || 0;
    const total = Math.max(0, subtotal + tax - discount);

    $('#subtotalDisplay').text('Rp ' + formatNumber(subtotal));
    $('#taxDisplay').text('Rp ' + formatNumber(tax));
    $('#totalDisplay').text('Rp ' + formatNumber(total)).data('total', total);

    const paid = parseFloat($('#paidInput').val()) || 0;
    const change = paid - total;

    $('#changeDisplay').text('Rp ' + formatNumber(change >= 0 ? change : 0));

    // Enable/disable process button
    if (paid >= total && cart.length > 0 && total > 0) {
        $('#processBtn').prop('disabled', false);
    } else {
        $('#processBtn').prop('disabled', true);
    }
}

function processTransaction() {
    const total = parseFloat($('#totalDisplay').data('total'));
    const paid = parseFloat($('#paidInput').val());
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discount = parseFloat($('#discountInput').val()) || 0;

    if (cart.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }

    if (paid < total) {
        alert('Jumlah bayar kurang dari total!');
        $('#paidInput').focus();
        return;
    }

    // Disable button
    $('#processBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

    const data = {
        customer_id: $('#customerId').val() || null,
        items: cart.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity,
            price: item.price
        })),
        subtotal: subtotal,
        tax: 0,
        discount: discount,
        total: total,
        paid: paid,
        _token: '{{ csrf_token() }}'
    };

    $.ajax({
        url: '{{ route("sales.store") }}',
        method: 'POST',
        data: data,
        success: function(response) {
            if (response.success) {
                // Show success modal
                $('#invoiceNumber').text(response.invoice_number);
                $('#saleDate').text(new Date().toLocaleDateString('id-ID'));
                $('#modalTotal').text('Rp ' + formatNumber(total));
                $('#modalPaid').text('Rp ' + formatNumber(paid));
                $('#modalChange').text('Rp ' + formatNumber(paid - total));
                $('#printBtn').attr('href', '/sales/' + response.sale_id);

                $('#successModal').modal('show');
            }
        },
        error: function(xhr) {
            alert('Transaksi gagal: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan'));
            $('#processBtn').prop('disabled', false).html('<i class="bi bi-check-circle"></i> Proses Transaksi');
        }
    });
}

function newTransaction() {
    $('#successModal').modal('hide');
    resetCart();
}

function resetCart() {
    cart = [];
    $('#customerId').val('');
    $('#discountInput').val(0);
    $('#paidInput').val('');
    $('#searchProduct').val('');
    updateCart();
    loadProducts();
    $('#processBtn').html('<i class="bi bi-check-circle"></i> Proses Transaksi');
}

function formatNumber(num) {
    return Math.floor(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endpush
